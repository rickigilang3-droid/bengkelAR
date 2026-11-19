<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceQueue;
use App\Models\ServiceApprovalItem;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\SyncServiceHistoryFromQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminQueueController extends Controller
{
    /**
     * Display the service queue list & bookings filtered by date
     */
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', date('Y-m-d'));

        $waiting = ServiceQueue::waiting()->count();
        $inProgress = ServiceQueue::inProgress()->count();
        $completed = ServiceQueue::completed()->where('completed_at', '>=', now()->startOfDay())->count();
        $cancelled = ServiceQueue::cancelled()->count();

        // Booking requests on the selected date
        $bookingRequestsDate = \App\Models\BookingRequest::with(['user', 'vehicle'])
            ->whereDate('preferred_date', $selectedDate)
            ->orderBy('preferred_time', 'asc')
            ->get();

        $query = ServiceQueue::with(['user', 'vehicle']);

        if ($request->filled('date')) {
            $query->whereDate('entered_at', $selectedDate);
        }

        $queues = $query->orderBy('priority', 'desc')
            ->orderBy('entered_at', 'asc')
            ->paginate(15);

        return view('dashboard.admin.queue.index', [
            'title' => 'Antrean & Jadwal Booking Servis',
            'description' => 'Lihat daftar pelanggan yang melakukan booking sesuai tanggal dan kelola antrean servis.',
            'active' => 'queue',
            'waiting' => $waiting,
            'inProgress' => $inProgress,
            'completed' => $completed,
            'cancelled' => $cancelled,
            'queues' => $queues,
            'selectedDate' => $selectedDate,
            'bookingRequestsDate' => $bookingRequestsDate,
        ]);
    }

    public function chats()
    {
        $queues = ServiceQueue::query()
            ->with(['user', 'vehicle', 'chats' => fn ($query) => $query->reorder()->latest()->limit(1)])
            ->withCount('chats')
            ->orderByDesc('entered_at')
            ->paginate(15);

        return view('dashboard.admin.chats.index', [
            'queues' => $queues,
        ]);
    }

    public function approvals()
    {
        $approvals = ServiceApprovalItem::query()
            ->with(['serviceQueue.user', 'serviceQueue.vehicle'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(15);

        return view('dashboard.admin.approvals.index', [
            'approvals' => $approvals,
        ]);
    }

    /**
     * Accept customer booking request and auto-create ServiceQueue
     */
    public function acceptBooking($id)
    {
        $booking = DB::transaction(function () use ($id) {
            $booking = \App\Models\BookingRequest::query()->lockForUpdate()->findOrFail($id);

            if ($booking->status !== 'pending') {
                return null;
            }

            if ($booking->vehicle->user_id !== $booking->user_id) {
                throw (new ModelNotFoundException())->setModel(\App\Models\BookingRequest::class, [$id]);
            }

            $booking->update(['status' => 'accepted']);
            $queue = ServiceQueue::create([
                'user_id' => $booking->user_id,
                'vehicle_id' => $booking->vehicle_id,
                'complaint' => $booking->service_type . ': ' . $booking->complaint,
                'priority' => 'medium',
                'status' => 'waiting',
                'entered_at' => now(),
            ]);

            \App\Models\Notification::create([
                'user_id' => $booking->user_id,
                'title' => 'Booking Servis Disetujui',
                'message' => 'Pengajuan booking Anda untuk ' . $booking->preferred_date->format('d M Y') . ' (' . $booking->preferred_time . ') telah disetujui dan masuk antrean.',
                'type' => 'status_update',
                'link' => '/pelanggan/servis/' . $queue->id,
                'is_read' => false,
            ]);

            return $booking;
        });

        if ($booking === null) {
            return redirect()->back()->with('error', 'Booking ini sudah diproses sebelumnya.');
        }

        return redirect()->back()->with('success', 'Booking disetujui dan otomatis masuk antrean servis.');
    }

    /**
     * Reject/Cancel customer booking request
     */
    public function rejectBooking($id)
    {
        $booking = DB::transaction(function () use ($id) {
            $booking = \App\Models\BookingRequest::query()->lockForUpdate()->findOrFail($id);
            if ($booking->status !== 'pending') {
                return null;
            }

            $booking->update(['status' => 'cancelled']);
            \App\Models\Notification::create([
                'user_id' => $booking->user_id,
                'title' => 'Booking Servis Dibatalkan',
                'message' => 'Mohon maaf, pengajuan booking Anda pada tanggal ' . $booking->preferred_date->format('d M Y') . ' tidak dapat diproses saat ini.',
                'type' => 'status_update',
                'link' => '/pelanggan/booking',
                'is_read' => false,
            ]);

            return $booking;
        });

        if ($booking === null) {
            return redirect()->back()->with('error', 'Booking ini sudah diproses sebelumnya.');
        }

        return redirect()->back()->with('success', 'Booking berhasil dibatalkan.');
    }

    /**
     * Show form to create a new manual queue (walk-in customers)
     */
    public function create()
    {
        // Ambil data pelanggan dan kendaraan untuk dropdown form
        $customers = User::query()->where('role', User::ROLE_CUSTOMER)->orderBy('name')->get();
        $vehicles = Vehicle::query()->whereHas('user', fn ($query) => $query->where('role', User::ROLE_CUSTOMER))->with('user')->get();

        return view('dashboard.admin.queue.create', [
            'title' => 'Tambah Antrean Servis',
            'active' => 'queue',
            'customers' => $customers,
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * Store new queue to database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', \Illuminate\Validation\Rule::exists('users', 'id')->where('role', User::ROLE_CUSTOMER)],
            'vehicle_id' => 'required|exists:vehicles,id',
            'complaint' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        if (! Vehicle::query()->whereKey($validated['vehicle_id'])->where('user_id', $validated['user_id'])->exists()) {
            return back()->withErrors(['vehicle_id' => 'Kendaraan harus terdaftar atas nama pelanggan yang dipilih.'])->withInput();
        }

        $validated['status'] = 'waiting';
        $validated['entered_at'] = now();

        $queue = ServiceQueue::create($validated);

        // Kirim Notifikasi ke Pelanggan
        \App\Models\Notification::create([
            'user_id' => $queue->user_id,
            'title' => 'Antrean Servis Dibuat',
            'message' => 'Motor Anda telah berhasil masuk ke dalam antrean servis bengkel.',
            'type' => 'status_update',
            'link' => '/pelanggan/servis/' . $queue->id,
            'is_read' => false,
        ]);

        return redirect()->route('admin.queue.index')
                       ->with('success', 'Antrean pelanggan berhasil ditambahkan');
    }

    /**
     * Show details of a specific queue item
     */
    public function show($id)
    {
        $queue = ServiceQueue::with(['user', 'vehicle', 'approvalItems', 'chats.sender'])->findOrFail($id);
        $inventories = \App\Models\Inventory::orderBy('category')->orderBy('name')->get();

        return view('dashboard.admin.queue.show', [
            'title' => 'Detail Antrean',
            'active' => 'queue',
            'queue' => $queue,
            'inventories' => $inventories,
        ]);
    }

    /**
     * Update queue status
     */
    public function updateStatus(Request $request, $id, SyncServiceHistoryFromQueue $historySync)
    {
        $validated = $request->validate([
            'status' => 'required|in:waiting,in_progress,completed,cancelled',
            'priority' => 'nullable|in:low,medium,high',
            'notes' => 'nullable|string',
        ]);

        $queue = ServiceQueue::findOrFail($id);

        $updateData = [
            'status' => $validated['status'],
        ];

        if (isset($validated['notes'])) {
            $updateData['notes'] = $validated['notes'];
        }

        if ($validated['priority'] ?? null) {
            $updateData['priority'] = $validated['priority'];
        }

        $notifTitle = '';
        $notifMessage = '';

        // LOGIKA BISNIS: Jika motor selesai diservis
        if ($validated['status'] === 'completed' && $queue->status !== 'completed') {
            $updateData['completed_at'] = now();
            
            $notifTitle = 'Servis Selesai!';
            $notifMessage = 'Motor Anda telah selesai diservis dan dites. Silakan lakukan pengambilan di bengkel.';
        } elseif ($validated['status'] === 'in_progress') {
            if (!$queue->estimated_completion_at) {
                $updateData['estimated_completion_at'] = now()->addHours(2);
            }
            $notifTitle = 'Motor Sedang Dikerjakan';
            $notifMessage = 'Mekanik kami sedang melakukan pengerjaan/perbaikan pada motor Anda.';
        } elseif ($validated['status'] === 'cancelled') {
            $notifTitle = 'Antrean Dibatalkan';
            $notifMessage = 'Antrean servis motor Anda telah dibatalkan.';
        }

        $queue->update($updateData);
        if ($validated['status'] === 'completed' && $queue->status === 'completed') {
            $historySync->execute($queue);
        }

        if ($notifTitle) {
            \App\Models\Notification::create([
                'user_id' => $queue->user_id,
                'title' => $notifTitle,
                'message' => $notifMessage,
                'type' => 'status_update',
                'link' => '/pelanggan/servis/' . $queue->id,
                'is_read' => false,
            ]);
        }

        // Jika servis selesai, arahkan admin cerdas ke halaman Riwayat agar bisa input Total Harga
        if ($validated['status'] === 'completed') {
            return redirect()->route('admin.history.index')
                           ->with('success', 'Servis selesai. Nota dan total dari perbaikan yang disetujui sudah masuk ke riwayat dan menu pembayaran.');
        }

        return redirect()->route('admin.queue.show', $queue->id)
                       ->with('success', 'Status antrean berhasil diupdate');
    }

    /**
     * Delete specific queue
     */
    public function destroy($id)
    {
        $queue = ServiceQueue::findOrFail($id);
        $queue->delete();

        return redirect()->route('admin.queue.index')
                       ->with('success', 'Data antrean berhasil dihapus');
    }
}
