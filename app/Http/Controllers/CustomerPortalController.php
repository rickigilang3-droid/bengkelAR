<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\Notification;
use App\Models\ServiceApprovalItem;
use App\Models\ServiceHistory;
use App\Models\ServiceQueue;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\RespondToServiceApproval;
use App\Services\SyncServiceHistoryFromQueue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerPortalController extends Controller
{
    public function bookings(Request $request): View
    {
        $user = $request->user();

        return view('dashboard.roles.bookings', [
            'vehicles' => $user->vehicles()->orderBy('brand')->orderBy('model')->get(),
            'bookings' => $user->bookingRequests()->with('vehicle')->paginate(10),
        ]);
    }

    public function storeBooking(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vehicle_id' => ['required', 'integer'],
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'preferred_time' => ['required', Rule::in(['09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00'])],
            'service_type' => ['required', 'string', 'max:100'],
            'complaint' => ['required', 'string', 'max:2000'],
        ]);

        $user = $request->user();
        $vehicle = $user->vehicles()->findOrFail($validated['vehicle_id']);

        $booking = $user->bookingRequests()->create([
            ...$validated,
            'status' => 'pending',
        ]);

        User::query()->where('role', User::ROLE_ADMIN)->get()->each(function (User $admin) use ($user, $booking): void {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Permintaan booking baru',
                'message' => $user->name . ' mengajukan servis pada ' . $booking->preferred_date->format('d M Y') . '.',
                'type' => 'booking_update',
                'link' => '/admin/antrian',
                'is_read' => false,
            ]);
        });

        return redirect()->route('customer.bookings')
            ->with('status', 'Permintaan booking untuk ' . $vehicle->brand . ' ' . $vehicle->model . ' berhasil dikirim.');
    }

    public function history(Request $request): View
    {
        $histories = $request->user()
            ->serviceHistories()
            ->with('vehicle')
            ->latest('completed_at')
            ->paginate(10);

        return view('dashboard.roles.history', [
            'histories' => $histories,
            'totalServices' => $request->user()->serviceHistories()->count(),
            'totalSpent' => $request->user()->serviceHistories()->where('payment_status', 'paid')->sum('total_cost'),
        ]);
    }

    public function historyDetail(Request $request, ServiceHistory $serviceHistory): View
    {
        abort_unless($serviceHistory->user_id === $request->user()->id, 404);
        $serviceHistory->load('vehicle');

        return view('dashboard.roles.history-detail', [
            'history' => $serviceHistory,
        ]);
    }

    public function chats(Request $request): View
    {
        $queues = ServiceQueue::query()
            ->where('user_id', $request->user()->id)
            ->with(['vehicle', 'chats' => fn ($query) => $query->reorder()->latest()->limit(1)])
            ->withCount('chats')
            ->orderByDesc('entered_at')
            ->paginate(10);

        return view('dashboard.roles.chats', [
            'queues' => $queues,
        ]);
    }

    public function approvals(Request $request): View
    {
        $approvals = ServiceApprovalItem::query()
            ->whereHas('serviceQueue', fn ($query) => $query->where('user_id', $request->user()->id))
            ->with(['serviceQueue.vehicle'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(10);

        return view('dashboard.roles.approvals', [
            'approvals' => $approvals,
        ]);
    }

    public function service(Request $request, ServiceQueue $serviceQueue): View
    {
        abort_unless($serviceQueue->user_id === $request->user()->id, 404);

        $serviceQueue->load(['vehicle', 'approvalItems', 'chats.sender']);

        return view('dashboard.roles.service', [
            'serviceQueue' => $serviceQueue,
        ]);
    }

    public function respondApproval(
        Request $request,
        ServiceApprovalItem $approvalItem,
        RespondToServiceApproval $responder,
        SyncServiceHistoryFromQueue $historySync
    ): RedirectResponse {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['approved', 'rejected'])],
        ]);

        $responder->execute($request->user(), $approvalItem, $validated['status'], $historySync);

        return redirect()
            ->route('customer.service.show', $approvalItem->service_queue_id)
            ->with('status', $validated['status'] === 'approved'
                ? 'Penggantian barang disetujui. Bengkel akan melanjutkan pekerjaan.'
                : 'Penggantian barang ditolak. Bengkel telah menerima keputusan Anda.');
    }

    public function vehicles(Request $request): View
    {
        return view('dashboard.roles.vehicles', [
            'vehicles' => $request->user()->vehicles()
                ->with(['maintenanceRecords' => fn ($query) => $query->latest('replaced_at')->latest('id')])
                ->withCount('serviceHistories')
                ->latest()
                ->get(),
        ]);
    }

    public function updateOdometer(Request $request, Vehicle $vehicle): RedirectResponse
    {
        abort_unless($vehicle->user_id === $request->user()->id, 404);

        $validated = $request->validate([
            'current_odometer_km' => ['required', 'integer', 'min:0', 'max:9999999'],
        ]);

        if ($vehicle->current_odometer_km !== null && $validated['current_odometer_km'] < $vehicle->current_odometer_km) {
            return back()
                ->withErrors(['current_odometer_km' => 'Kilometer terbaru tidak boleh lebih rendah dari catatan sebelumnya.'])
                ->withInput();
        }

        $vehicle->update($validated);

        return redirect()->route('customer.vehicles')
            ->with('status', 'Kilometer kendaraan berhasil diperbarui.');
    }

    public function storeMaintenanceRecord(Request $request, Vehicle $vehicle): RedirectResponse
    {
        abort_unless($vehicle->user_id === $request->user()->id, 404);

        $validated = $request->validate([
            'part_name' => ['required', 'string', 'max:120'],
            'replaced_at' => ['required', 'date', 'before_or_equal:today'],
            'odometer_km' => ['required', 'integer', 'min:0', 'max:9999999'],
            'interval_km' => ['required', 'integer', 'min:1', 'max:9999999'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($request, $vehicle, $validated): void {
            $vehicle->maintenanceRecords()->create([
                ...$validated,
                'user_id' => $request->user()->id,
            ]);

            if ($vehicle->current_odometer_km === null || $validated['odometer_km'] > $vehicle->current_odometer_km) {
                $vehicle->update(['current_odometer_km' => $validated['odometer_km']]);
            }
        });

        return redirect()->route('customer.vehicles')
            ->with('status', 'Riwayat penggantian berhasil disimpan dan pengingat kilometer diperbarui.');
    }

    public function storeVehicle(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vehicle_type' => ['required', 'string', 'max:50'],
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['required', 'integer', 'min:1950', 'max:' . (now()->year + 1)],
            'color' => ['required', 'string', 'max:50'],
            'plate_number' => ['required', 'string', 'max:20', 'unique:vehicles,plate_number'],
        ]);

        $request->user()->vehicles()->create($validated);

        return redirect()->route('customer.vehicles')->with('status', 'Kendaraan berhasil ditambahkan ke garasi Anda.');
    }

    public function updateVehicle(Request $request, Vehicle $vehicle): RedirectResponse
    {
        abort_unless($vehicle->user_id === $request->user()->id, 404);

        $validated = $request->validate([
            'vehicle_type' => ['required', 'string', 'max:50'],
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['required', 'integer', 'min:1950', 'max:' . (now()->year + 1)],
            'color' => ['required', 'string', 'max:50'],
            'plate_number' => ['required', 'string', 'max:20', 'unique:vehicles,plate_number,' . $vehicle->id],
        ]);

        $vehicle->update($validated);

        return redirect()->route('customer.vehicles')->with('status', 'Informasi kendaraan berhasil diperbarui.');
    }

    public function destroyVehicle(Request $request, Vehicle $vehicle): RedirectResponse
    {
        abort_unless($vehicle->user_id === $request->user()->id, 404);

        if ($vehicle->serviceQueues()->exists() || $vehicle->serviceHistories()->exists() || $vehicle->bookingRequests()->exists()) {
            return redirect()->route('customer.vehicles')
                ->with('error', 'Kendaraan yang memiliki riwayat servis atau booking tidak dapat dihapus.');
        }

        $vehicle->delete();

        return redirect()->route('customer.vehicles')->with('status', 'Kendaraan berhasil dihapus.');
    }

    public function notifications(Request $request): View
    {
        return view('dashboard.roles.notifications', [
            'customerNotifications' => $request->user()->notifications()->paginate(15),
        ]);
    }

    public function markNotificationRead(Request $request, Notification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === $request->user()->id, 404);
        $notification->update(['is_read' => true]);

        return back();
    }

    public function markNotificationsRead(Request $request): RedirectResponse
    {
        $request->user()->notifications()->where('is_read', false)->update(['is_read' => true]);

        return redirect()->route('customer.notifications')->with('status', 'Semua notifikasi telah ditandai dibaca.');
    }
}
