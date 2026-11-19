<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\ServiceApprovalItem;
use App\Models\ServiceChat;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    public function open(Request $request, Notification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === $request->user()->id, 404);

        $notification->update(['is_read' => true]);
        $target = $notification->link;

        if ($request->user()->role === User::ROLE_CUSTOMER) {
            $target = $this->resolveLegacyCustomerTarget($request->user(), $notification, $target);
        }

        if (! is_string($target) || ! str_starts_with($target, '/') || str_starts_with($target, '//') || str_contains($target, '\\')) {
            $target = $request->user()->role === 'admin'
                ? route('dashboard.admin', absolute: false)
                : route('dashboard.customer', absolute: false);
        }

        return redirect($target);
    }

    private function resolveLegacyCustomerTarget(User $customer, Notification $notification, ?string $target): ?string
    {
        if ($notification->type === 'approval_request' && ($target === '/dashboard' || $target === null)) {
            $approval = ServiceApprovalItem::query()
                ->with('serviceQueue')
                ->whereHas('serviceQueue', fn ($query) => $query->where('user_id', $customer->id))
                ->latest()
                ->get()
                ->first(fn (ServiceApprovalItem $item) => Str::contains($notification->message, $item->item_name));

            if ($approval) {
                return route('customer.service.show', $approval->serviceQueue, absolute: false);
            }
        }

        if ($notification->type === 'chat' && ($target === '/dashboard' || $target === null)) {
            $chat = ServiceChat::query()
                ->with('serviceQueue')
                ->where('sender_type', 'admin')
                ->whereHas('serviceQueue', fn ($query) => $query->where('user_id', $customer->id))
                ->latest()
                ->limit(50)
                ->get()
                ->first(fn (ServiceChat $chat) => str_starts_with($chat->message, $notification->message));

            if ($chat) {
                return route('customer.service.show', $chat->serviceQueue, absolute: false);
            }
        }

        if ($notification->type === 'booking_update' && in_array($target, ['/booking', '/dashboard'], true)) {
            return route('customer.bookings', absolute: false);
        }

        if ($target === '/booking') {
            return route('customer.bookings', absolute: false);
        }

        return $target;
    }
}
