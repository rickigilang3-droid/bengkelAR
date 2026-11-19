<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\Notification;
use App\Models\ServiceApprovalItem;
use App\Models\ServiceChat;
use App\Models\ServiceHistory;
use App\Models\ServiceQueue;
use App\Models\User;
use App\Services\RespondToServiceApproval;
use App\Services\SyncServiceHistoryFromQueue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterconnectedApiController extends Controller
{
    /**
     * Get unread & recent notifications for current user/admin
     */
    public function getNotifications(): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Admin sees all notifications or admin specific, normal user sees user_id specific
        if ($user->role === 'admin') {
            $notifications = Notification::where('user_id', $user->id)
                ->latest()
                ->take(15)
                ->get();
            $unreadCount = Notification::where('user_id', $user->id)
                ->where('is_read', false)
                ->count();
        } else {
            $notifications = Notification::where('user_id', $user->id)
                ->latest()
                ->take(15)
                ->get();
            $unreadCount = Notification::where('user_id', $user->id)
                ->where('is_read', false)
                ->count();
        }

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark single notification as read
     */
    public function markNotificationRead($id): JsonResponse
    {
        $user = Auth::user();
        $notification = Notification::query()->where('user_id', $user->id)->find($id);
        if ($notification) {
            $notification->update(['is_read' => true]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsRead(): JsonResponse
    {
        $user = Auth::user();
        Notification::where('user_id', $user->id)->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Get chat messages for a service queue
     */
    public function getChats($queueId): JsonResponse
    {
        $user = Auth::user();
        $queue = ServiceQueue::with(['user', 'vehicle'])->find($queueId);

        if (! $queue) {
            return response()->json(['error' => 'Antrean tidak ditemukan'], 404);
        }

        // Ensure authorization
        if ($user->role !== 'admin' && $queue->user_id != $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $chats = ServiceChat::where('service_queue_id', $queueId)
            ->with('sender:id,name,role')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark as read for current user
        ServiceChat::where('service_queue_id', $queueId)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'queue' => [
                'id' => $queue->id,
                'vehicle_name' => $queue->vehicle ? ($queue->vehicle->brand . ' ' . $queue->vehicle->model . ' (' . ($queue->vehicle->plate_number ?? $queue->vehicle->license_plate ?? '') . ')') : 'Motor',
                'customer_name' => $queue->user ? $queue->user->name : 'Pelanggan',
                'status' => $queue->status,
            ],
            'chats' => $chats,
        ]);
    }

    /**
     * Send chat message
     */
    public function sendChat(Request $request, $queueId): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'attachment' => 'nullable|image|max:4096',
        ]);

        $user = Auth::user();
        $queue = ServiceQueue::find($queueId);

        if (! $queue) {
            return response()->json(['error' => 'Antrean tidak ditemukan'], 404);
        }

        if ($user->role !== 'admin' && $queue->user_id != $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('chats', 'public');
        }

        $chat = ServiceChat::create([
            'service_queue_id' => $queueId,
            'sender_id' => $user->id,
            'sender_type' => $user->role === 'admin' ? 'admin' : 'user',
            'message' => $request->message,
            'attachment_path' => $attachmentPath ? '/storage/' . $attachmentPath : null,
            'is_read' => false,
        ]);

        // Create notification for recipient
        $recipientIds = $user->role === 'admin'
            ? collect([$queue->user_id])
            : User::query()->where('role', User::ROLE_ADMIN)->pluck('id');

        foreach ($recipientIds as $recipientId) {
            Notification::create([
                'user_id' => $recipientId,
                'title' => $user->role === 'admin' ? 'Pesan baru dari Bengkel' : 'Pesan baru dari ' . $user->name,
                'message' => substr($request->message, 0, 100),
                'type' => 'chat',
                'link' => $user->role === 'admin' ? "/pelanggan/servis/{$queueId}" : "/admin/antrian/{$queueId}",
                'is_read' => false,
            ]);
        }

        return response()->json([
            'success' => true,
            'chat' => $chat->load('sender:id,name,role'),
        ]);
    }

    /**
     * Admin adds a new repair/sparepart approval item
     */
    public function addApprovalItem(Request $request, $queueId): JsonResponse
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'estimated_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Hanya admin yang dapat menambah persetujuan'], 403);
        }

        $queue = ServiceQueue::find($queueId);
        if (! $queue) {
            return response()->json(['error' => 'Antrean tidak ditemukan'], 404);
        }

        $item = ServiceApprovalItem::create([
            'service_queue_id' => $queueId,
            'item_name' => $request->item_name,
            'estimated_cost' => $request->estimated_cost,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        // Notify customer
        Notification::create([
            'user_id' => $queue->user_id,
            'title' => 'Permintaan Persetujuan Perbaikan Tambahan',
            'message' => "Mekanik mengajukan penggantian/perbaikan: {$item->item_name} (Rp " . number_format($item->estimated_cost, 0, ',', '.') . "). Silakan konfirmasi.",
            'type' => 'approval_request',
            'link' => "/pelanggan/servis/{$queue->id}",
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'item' => $item,
        ]);
    }

    /**
     * Customer responds (Approve/Reject) to approval item
     */
    public function respondApprovalItem(
        Request $request,
        $itemId,
        RespondToServiceApproval $responder,
        SyncServiceHistoryFromQueue $historySync
    ): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $user = Auth::user();
        $approvalItem = ServiceApprovalItem::findOrFail($itemId);
        $item = $responder->execute($user, $approvalItem, $validated['status'], $historySync);

        return response()->json([
            'success' => true,
            'item' => $item,
        ]);
    }

    /**
     * Customer live tracking details for active queue
     */
    public function getLiveTracking($queueId): JsonResponse
    {
        $user = Auth::user();
        $queue = ServiceQueue::with(['vehicle', 'approvalItems', 'chats.sender'])->find($queueId);

        if (! $queue) {
            return response()->json(['error' => 'Antrean tidak ditemukan'], 404);
        }

        if ($user->role !== 'admin' && $queue->user_id != $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'queue' => $queue,
            'approval_items' => $queue->approvalItems,
            'chats' => $queue->chats,
        ]);
    }

    /**
     * Customer submits online booking request
     */
    public function submitBookingRequest(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $vehicleId = $request->vehicle_id;
        $vehicle = \App\Models\Vehicle::find($vehicleId);

        if (! $vehicle || $vehicle->user_id != $user->id) {
            $vehicle = \App\Models\Vehicle::firstOrCreate(
                ['user_id' => $user->id, 'plate_number' => 'B ' . rand(1000, 9999) . ' WXR'],
                [
                    'vehicle_type' => 'Motor',
                    'brand' => 'Honda',
                    'model' => 'Vario 150 Esp',
                    'year' => 2022,
                    'color' => 'Hitam',
                    'description' => 'Motor Utama Pelanggan',
                ]
            );
            $vehicleId = $vehicle->id;
        }

        $request->validate([
            'preferred_date' => 'required|date',
            'preferred_time' => 'required|string',
            'service_type' => 'required|string',
            'complaint' => 'required|string',
        ]);

        $booking = BookingRequest::create([
            'user_id' => $user->id,
            'vehicle_id' => $vehicleId,
            'preferred_date' => $request->preferred_date,
            'preferred_time' => $request->preferred_time,
            'service_type' => $request->service_type,
            'complaint' => $request->complaint,
            'status' => 'pending',
        ]);

        // Notify All Admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Permintaan Booking Servis Baru!',
                'message' => "Pelanggan {$user->name} mengajukan booking untuk tanggal {$request->preferred_date} jam {$request->preferred_time}.",
                'type' => 'booking_update',
                'link' => '/admin/antrian',
                'is_read' => false,
            ]);
        }

        // Notify Customer
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Booking Servis Berhasil Terkirim',
            'message' => "Permintaan booking servis Anda untuk tanggal {$request->preferred_date} jam {$request->preferred_time} telah diterima sistem. Menunggu konfirmasi admin.",
            'type' => 'booking_update',
            'link' => '/pelanggan/booking',
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan booking berhasil dikirim! Menunggu konfirmasi admin.',
            'booking' => $booking->load('vehicle'),
        ]);
    }
}
