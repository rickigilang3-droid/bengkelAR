<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminNotificationsController extends Controller
{
    public function index(Request $request): View
    {
        return view('dashboard.admin.notifications.index', [
            'adminNotifications' => $request->user()->notifications()->paginate(15),
        ]);
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $request->user()->notifications()->where('is_read', false)->update(['is_read' => true]);

        return redirect()->route('admin.notifications.index')->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}
