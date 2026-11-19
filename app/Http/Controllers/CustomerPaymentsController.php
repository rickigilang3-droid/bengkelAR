<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\ServiceHistory;
use App\Models\ServiceQueue;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CustomerPaymentsController extends Controller
{
    public function index(Request $request): View
    {
        $histories = $request->user()
            ->serviceHistories()
            ->with('vehicle')
            ->where('payment_status', '!=', 'paid')
            ->latest('completed_at')
            ->paginate(10);
        $activeCharges = ServiceQueue::query()
            ->where('user_id', $request->user()->id)
            ->whereIn('status', ['waiting', 'in_progress'])
            ->whereHas('approvalItems', fn ($query) => $query->where('status', 'approved'))
            ->with([
                'vehicle',
                'approvalItems' => fn ($query) => $query->where('status', 'approved')->orderBy('created_at'),
            ])
            ->latest('entered_at')
            ->get();

        return view('dashboard.roles.payments', [
            'histories' => $histories,
            'activeCharges' => $activeCharges,
        ]);
    }

    public function submit(Request $request, ServiceHistory $serviceHistory): RedirectResponse
    {
        abort_unless($serviceHistory->user_id === $request->user()->id, 404);

        if ($serviceHistory->payment_status === 'paid') {
            return back()->withErrors(['payment' => 'Pembayaran servis ini sudah lunas.']);
        }

        if ((float) $serviceHistory->total_cost <= 0) {
            return back()->withErrors(['payment' => 'Tagihan belum tersedia. Silakan hubungi bengkel jika servis Anda sudah selesai.']);
        }

        if ($serviceHistory->payment_status === 'pending' && $serviceHistory->payment_submitted_at) {
            return back()->withErrors(['payment' => 'Pembayaran Anda sedang menunggu verifikasi admin.']);
        }

        $validated = $request->validate([
            'payment_channel' => ['required', 'in:cash,bank_transfer,qris,dana,ovo,gopay,shopeepay,linkaja'],
            'payment_proof' => ['nullable', 'required_unless:payment_channel,cash', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $previousProof = $serviceHistory->payment_proof_path;
        $proofPath = $validated['payment_channel'] === 'cash'
            ? null
            : ($request->file('payment_proof')?->store('payment-proofs', 'local') ?? $previousProof);
        $paymentMethod = $validated['payment_channel'] === 'cash' ? 'cash' : 'transfer';
        $paymentChannel = $validated['payment_channel'];

        $serviceHistory->update([
            'payment_method' => $paymentMethod,
            'payment_channel' => $validated['payment_channel'],
            'payment_status' => 'pending',
            'payment_proof_path' => $proofPath,
            'payment_submitted_at' => now(),
        ]);

        if ($previousProof && $proofPath !== $previousProof) {
            Storage::disk('local')->delete($previousProof);
        }

        $serviceId = (int) $serviceHistory->getAttribute('id');
        User::query()->where('role', User::ROLE_ADMIN)->get()->each(function (User $admin) use ($request, $serviceId, $paymentChannel): void {
            Notification::create([
                'user_id' => (int) $admin->getAttribute('id'),
                'title' => $paymentChannel === 'cash' ? 'Pembayaran tunai menunggu konfirmasi' : 'Pembayaran menunggu verifikasi',
                'message' => $request->user()->name . ' memilih ' . str_replace('_', ' ', strtoupper($paymentChannel)) . ' untuk servis #' . $serviceId . '.',
                'type' => 'payment_update',
                'link' => '/admin/pembayaran',
                'is_read' => false,
            ]);
        });

        return redirect()->route('customer.payments')
            ->with('status', 'Pembayaran berhasil dikirim dan sedang menunggu verifikasi bengkel.');
    }
}
