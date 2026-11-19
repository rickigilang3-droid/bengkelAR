<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Inventory;
use App\Models\MaintenanceRecord;
use App\Models\PartReplacement;
use App\Models\ServiceApprovalItem;
use App\Models\User;
use App\Services\SyncServiceHistoryFromQueue;
use Illuminate\Support\Facades\DB;

class RespondToServiceApproval
{
    public function execute(
        User $customer,
        ServiceApprovalItem $approvalItem,
        string $status,
        SyncServiceHistoryFromQueue $historySync
    ): ServiceApprovalItem
    {
        return DB::transaction(function () use ($customer, $approvalItem, $status, $historySync): ServiceApprovalItem {
            $item = ServiceApprovalItem::query()
                ->with('serviceQueue')
                ->lockForUpdate()
                ->findOrFail($approvalItem->id);
            $queue = $item->serviceQueue;

            abort_unless($customer->role === User::ROLE_CUSTOMER && $queue->user_id === $customer->id, 403, 'Anda tidak memiliki akses ke persetujuan ini.');
            abort_if($item->status !== 'pending', 422, 'Persetujuan ini sudah diproses.');
            abort_unless(in_array($status, ['approved', 'rejected'], true), 422, 'Status persetujuan tidak valid.');

            if ($status === 'approved' && $item->inventory_id !== null) {
                $inventory = Inventory::query()->lockForUpdate()->findOrFail($item->inventory_id);
                abort_if($inventory->stock < $item->quantity, 422, 'Stok sparepart tidak mencukupi. Hubungi bengkel sebelum menyetujui.');
                $vehicle = $queue->vehicle()->lockForUpdate()->firstOrFail();
                $odometerKm = $item->odometer_km;
                abort_if($odometerKm === null || $item->interval_km === null, 422, 'Data kilometer atau interval pengingat pengajuan tidak lengkap.');
                abort_if(
                    $vehicle->current_odometer_km !== null && $odometerKm < $vehicle->current_odometer_km,
                    422,
                    'Kilometer kendaraan sudah berubah. Hubungi bengkel untuk memperbarui pengajuan.'
                );

                PartReplacement::query()->create([
                    'service_queue_id' => $queue->id,
                    'vehicle_id' => $vehicle->id,
                    'inventory_id' => $inventory->id,
                    'quantity' => $item->quantity,
                    'unit_price' => (float) $item->estimated_cost / $item->quantity,
                    'subtotal' => $item->estimated_cost,
                    'notes' => $item->notes,
                ]);
                $inventory->decrement('stock', $item->quantity);
                $vehicle->update(['current_odometer_km' => $odometerKm]);
                $queue->update(['odometer_km' => $odometerKm]);

                MaintenanceRecord::query()->create([
                    'user_id' => $queue->user_id,
                    'vehicle_id' => $vehicle->id,
                    'part_name' => $inventory->name,
                    'replaced_at' => today(),
                    'odometer_km' => $odometerKm,
                    'interval_km' => $item->interval_km,
                    'notes' => $item->notes ?? 'Dicatat dari persetujuan servis #' . $queue->id,
                ]);
            }

            $item->update([
                'status' => $status,
                'responded_at' => now(),
            ]);
            $historySync->execute($queue);

            $statusText = $status === 'approved' ? 'MENYETUJUI' : 'MENOLAK';
            User::query()->where('role', User::ROLE_ADMIN)->get()->each(function (User $admin) use ($customer, $item, $queue, $statusText): void {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => "Pelanggan {$statusText} Perbaikan",
                    'message' => "{$customer->name} telah {$statusText} item: {$item->item_name} (Rp " . number_format((float) $item->estimated_cost, 0, ',', '.') . ").",
                    'type' => 'approval_response',
                    'link' => "/admin/antrian/{$queue->id}",
                    'is_read' => false,
                ]);
            });

            if ($item->requested_by !== null) {
                Notification::create([
                    'user_id' => $item->requested_by,
                    'title' => 'Pengajuan sparepart ' . ($status === 'approved' ? 'disetujui' : 'ditolak'),
                    'message' => $customer->name . ' ' . strtolower($statusText) . ' ' . $item->item_name . ' untuk servis #' . $queue->id . '.',
                    'type' => 'approval_response',
                    'link' => '/mekanik/dashboard?status=all',
                    'is_read' => false,
                ]);
            }

            return $item;
        });
    }
}
