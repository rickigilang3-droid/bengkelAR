<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\ServiceHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminPaymentsController extends Controller
{
    public function index(): View
    {
        $histories = ServiceHistory::query()
            ->with(['user', 'vehicle'])
            ->orderByRaw("CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END")
            ->orderByRaw('CASE WHEN payment_submitted_at IS NULL THEN 1 ELSE 0 END')
            ->latest('completed_at')
            ->paginate(15);

        return view('dashboard.admin.payments.index', [
            'histories' => $histories,
            'awaitingVerification' => ServiceHistory::query()
                ->where('payment_status', 'pending')
                ->whereNotNull('payment_submitted_at')
                ->count(),
            'unpaidTotal' => ServiceHistory::query()
                ->where('payment_status', '!=', 'paid')
                ->where('total_cost', '>', 0)
                ->sum('total_cost'),
        ]);
    }

    public function update(Request $request, ServiceHistory $serviceHistory): RedirectResponse
    {
        $validated = $request->validate([
            'total_cost' => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'payment_channel' => ['required_if:payment_status,paid', 'nullable', Rule::in(['cash', 'bank_transfer', 'qris', 'dana', 'ovo', 'gopay', 'shopeepay', 'linkaja'])],
            'payment_status' => ['required', Rule::in(['pending', 'paid', 'failed'])],
        ]);

        if ($validated['payment_status'] === 'paid' && (float) $validated['total_cost'] <= 0) {
            return back()->withErrors(['total_cost' => 'Total biaya harus lebih dari Rp0 sebelum pembayaran dapat dinyatakan lunas.']);
        }

        $previousStatus = $serviceHistory->payment_status;
        $previousCost = (float) $serviceHistory->total_cost;
        $paymentChannel = $validated['payment_channel'] ?? $serviceHistory->payment_channel;
        $serviceHistory->update([
            'total_cost' => $validated['total_cost'],
            'payment_channel' => $paymentChannel,
            'payment_method' => $paymentChannel === null
                ? $serviceHistory->payment_method
                : ($paymentChannel === 'cash' ? 'cash' : 'transfer'),
            'payment_status' => $validated['payment_status'],
        ]);

        if ($previousStatus !== $serviceHistory->payment_status || $previousCost !== (float) $serviceHistory->total_cost) {
            $message = match ($serviceHistory->payment_status) {
                'paid' => 'Pembayaran servis #' . $serviceHistory->id . ' telah diverifikasi lunas.',
                'failed' => 'Pembayaran servis #' . $serviceHistory->id . ' perlu diperbaiki. Silakan kirim ulang melalui menu pembayaran.',
                default => 'Tagihan servis #' . $serviceHistory->id . ' diperbarui menjadi Rp' . number_format((float) $serviceHistory->total_cost, 0, ',', '.') . '.',
            };

            Notification::create([
                'user_id' => $serviceHistory->user_id,
                'title' => $serviceHistory->payment_status === 'paid' ? 'Pembayaran terverifikasi' : 'Pembaruan pembayaran servis',
                'message' => $message,
                'type' => 'payment_update',
                'link' => '/pelanggan/pembayaran',
                'is_read' => false,
            ]);
        }

        return redirect()->route('admin.payments')
            ->with('success', 'Tagihan dan status pembayaran berhasil diperbarui.');
    }

    public function proof(ServiceHistory $serviceHistory)
    {
        $proofPath = $serviceHistory->payment_proof_path;
        abort_unless(\is_string($proofPath) && $proofPath !== '', 404);

        $disk = Storage::disk('local');
        abort_unless($disk->exists($proofPath), 404);
        $stream = $disk->readStream($proofPath);
        abort_if($stream === false, 404);

        return response()->streamDownload(function () use ($stream): void {
            try {
                fpassthru($stream);
            } finally {
                fclose($stream);
            }
        }, basename($proofPath), [
            'Content-Type' => 'application/octet-stream',
        ]);
    }
}
