<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\ServiceHistory;
use App\Models\ServiceQueue;
use Illuminate\Support\Facades\DB;

class SyncServiceHistoryFromQueue
{
    public function execute(ServiceQueue $serviceQueue): ?ServiceHistory
    {
        return DB::transaction(function () use ($serviceQueue): ?ServiceHistory {
            $queue = ServiceQueue::query()
                ->with(['approvalItems' => fn ($query) => $query->where('status', 'approved')])
                ->lockForUpdate()
                ->findOrFail($serviceQueue->getKey());

            if ($queue->status !== 'completed') {
                return null;
            }

            $history = ServiceHistory::query()->where([
                'service_queue_id' => $queue->getKey(),
            ])->first();

            if (! $history) {
                $history = ServiceHistory::query()
                    ->whereNull('service_queue_id')
                    ->where('user_id', $queue->user_id)
                    ->where('vehicle_id', $queue->vehicle_id)
                    ->where('entered_at', $queue->entered_at ?? $queue->created_at)
                    ->first();
            }

            $history ??= new ServiceHistory(['service_queue_id' => $queue->getKey()]);
            $history->service_queue_id = $queue->getKey();

            $wasSubmittedOrPaid = $history->exists
                && ($history->payment_status === 'paid' || $history->payment_submitted_at !== null);
            $oldTotal = (float) ($history->total_cost ?? 0);

            $approvedItems = $queue->approvalItems;
            $itemizedDescription = $approvedItems->isEmpty()
                ? 'Belum ada sparepart atau perbaikan tambahan yang disetujui.'
                : $approvedItems->map(function ($item): string {
                    $line = '- ' . $item->item_name . ' — Rp' . number_format((float) $item->estimated_cost, 0, ',', '.');

                    return $item->notes ? $line . "\n  Keterangan: " . $item->notes : $line;
                })->implode("\n");
            $complaint = trim((string) $queue->complaint);
            $description = ($complaint !== '' ? "Keluhan / pekerjaan: {$complaint}\n\n" : '')
                . "Sparepart dan perbaikan yang disetujui:\n{$itemizedDescription}";
            $newTotal = (float) $approvedItems->sum('estimated_cost');
            $serviceType = str_contains($complaint, ':')
                ? trim(explode(':', $complaint, 2)[0])
                : 'Servis Umum';

            $history->fill([
                'user_id' => $queue->user_id,
                'vehicle_id' => $queue->vehicle_id,
                'service_type' => $serviceType !== '' ? $serviceType : 'Servis Umum',
                'description' => $description,
                'entered_at' => $queue->entered_at ?? $queue->created_at,
                'completed_at' => $queue->completed_at ?? now(),
                'total_cost' => $newTotal,
                'notes' => $queue->notes,
            ]);

            if ($wasSubmittedOrPaid && $oldTotal !== $newTotal) {
                $history->payment_status = 'pending';
                $history->payment_method = null;
                $history->payment_channel = null;
                $history->payment_proof_path = null;
                $history->payment_submitted_at = null;
            }

            $history->save();

            if ($wasSubmittedOrPaid && $oldTotal !== $newTotal) {
                Notification::create([
                    'user_id' => $queue->user_id,
                    'title' => 'Tagihan servis diperbarui',
                    'message' => 'Total tagihan servis #' . $queue->id . ' berubah menjadi Rp' . number_format($newTotal, 0, ',', '.') . ' setelah persetujuan perbaikan diperbarui.',
                    'type' => 'payment_update',
                    'link' => '/pelanggan/pembayaran',
                    'is_read' => false,
                ]);
            }

            return $history;
        });
    }
}
