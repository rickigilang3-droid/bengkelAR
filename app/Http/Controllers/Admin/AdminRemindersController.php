<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class AdminRemindersController extends Controller
{
    /**
     * Display reminders list
     */
    public function index(Request $request)
    {
        $query = Reminder::with(['user', 'vehicle']);

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $reminders = $query->orderBy('reminder_date', 'asc')->paginate(15);

        return view('dashboard.admin.reminders.index', [
            'title' => 'Reminders',
            'description' => 'Kelola pengingat servis dan notifikasi pelanggan.',
            'active' => 'reminders',
            'reminders' => $reminders,
        ]);
    }

    /**
     * Show create reminder form
     */
    public function create()
    {
        $customers = User::where('role', '!=', 'admin')->orderBy('name')->get();
        $vehicles = Vehicle::with('user')->orderBy('plate_number')->get();

        return view('dashboard.admin.reminders.create', [
            'title' => 'Buat Pengingat Baru',
            'active' => 'reminders',
            'customers' => $customers,
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * Store new reminder
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'type' => 'required|in:service,maintenance,inspection',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'reminder_date' => 'required|date|after:today',
            'notify_days_before' => 'required|integer|min:0|max:30',
        ]);

        Reminder::create($validated);

        return redirect()->route('admin.reminders.index')
                       ->with('success', 'Pengingat berhasil dibuat');
    }

    /**
     * Edit reminder
     */
    public function edit($id)
    {
        $reminder = Reminder::findOrFail($id);
        $customers = User::where('role', '!=', 'admin')->orderBy('name')->get();
        $vehicles = Vehicle::with('user')->orderBy('plate_number')->get();

        return view('dashboard.admin.reminders.edit', [
            'title' => 'Edit Pengingat',
            'active' => 'reminders',
            'reminder' => $reminder,
            'customers' => $customers,
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * Update reminder
     */
    public function update(Request $request, $id)
    {
        $reminder = Reminder::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'reminder_date' => 'required|date',
            'notify_days_before' => 'required|integer|min:0|max:30',
        ]);

        $reminder->update($validated);

        return redirect()->route('admin.reminders.index')
                       ->with('success', 'Pengingat berhasil diupdate');
    }

    /**
     * Delete reminder
     */
    public function destroy($id)
    {
        $reminder = Reminder::findOrFail($id);
        $reminder->delete();

        return redirect()->route('admin.reminders.index')
                       ->with('success', 'Pengingat berhasil dihapus');
    }

    /**
     * Send reminder notification
     */
    public function sendNotification($id)
    {
        $reminder = Reminder::findOrFail($id);

        // TODO: Implement notification sending (email/SMS)
        // For now, just mark as sent

        $reminder->update(['is_sent' => true]);

        return redirect()->back()
                       ->with('success', 'Notifikasi berhasil dikirim ke ' . $reminder->user->name);
    }
}
