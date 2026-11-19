<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminCustomersController;
use App\Http\Controllers\Admin\AdminHistoryController;
use App\Http\Controllers\Admin\AdminInventoryController;
use App\Http\Controllers\Admin\AdminMechanicsController;
use App\Http\Controllers\Admin\AdminNotificationsController;
use App\Http\Controllers\Admin\AdminQueueController;
use App\Http\Controllers\Admin\AdminPaymentsController;
use App\Http\Controllers\Admin\AdminRemindersController;
use App\Http\Controllers\CustomerPortalController;
use App\Http\Controllers\CustomerPaymentsController;
use App\Http\Controllers\CustomerInventoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InterconnectedApiController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('dashboard.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin');
});

Route::middleware(['auth', 'verified', 'role:pelanggan'])->prefix('pelanggan')->name('dashboard.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'customer'])->name('customer');
});

Route::middleware(['auth', 'verified', 'role:pelanggan'])->prefix('pelanggan')->name('customer.')->group(function () {
    Route::get('/booking', [CustomerPortalController::class, 'bookings'])->name('bookings');
    Route::post('/booking', [CustomerPortalController::class, 'storeBooking'])->name('bookings.store');
    Route::get('/riwayat', [CustomerPortalController::class, 'history'])->name('history');
    Route::get('/riwayat/{serviceHistory}', [CustomerPortalController::class, 'historyDetail'])->name('history.show');
    Route::get('/pembayaran', [CustomerPaymentsController::class, 'index'])->name('payments');
    Route::post('/pembayaran/{serviceHistory}', [CustomerPaymentsController::class, 'submit'])->name('payments.submit');
    Route::get('/chat', [CustomerPortalController::class, 'chats'])->name('chats');
    Route::get('/persetujuan-sparepart', [CustomerPortalController::class, 'approvals'])->name('approvals');
    Route::get('/kendaraan', [CustomerPortalController::class, 'vehicles'])->name('vehicles');
    Route::post('/kendaraan', [CustomerPortalController::class, 'storeVehicle'])->name('vehicles.store');
    Route::put('/kendaraan/{vehicle}', [CustomerPortalController::class, 'updateVehicle'])->name('vehicles.update');
    Route::put('/kendaraan/{vehicle}/kilometer', [CustomerPortalController::class, 'updateOdometer'])->name('vehicles.odometer');
    Route::post('/kendaraan/{vehicle}/penggantian', [CustomerPortalController::class, 'storeMaintenanceRecord'])->name('vehicles.maintenance.store');
    Route::delete('/kendaraan/{vehicle}', [CustomerPortalController::class, 'destroyVehicle'])->name('vehicles.destroy');
    Route::get('/notifikasi', [CustomerPortalController::class, 'notifications'])->name('notifications');
    Route::get('/notifikasi/{notification}/buka', [NotificationController::class, 'open'])->name('notifications.open');
    Route::get('/notifikasi/{notification}/baca', [CustomerPortalController::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifikasi/baca-semua', [CustomerPortalController::class, 'markNotificationsRead'])->name('notifications.read-all');
    Route::get('/servis/{serviceQueue}', [CustomerPortalController::class, 'service'])->name('service.show');
    Route::post('/persetujuan/{approvalItem}', [CustomerPortalController::class, 'respondApproval'])->name('approvals.respond');
});

Route::middleware(['auth', 'verified', 'role:pelanggan'])->get('/inventaris', [CustomerInventoryController::class, 'index'])
    ->name('customer.inventory');

Route::middleware(['auth', 'role:admin,pelanggan'])->prefix('api')->group(function () {
    Route::get('/notifications', [InterconnectedApiController::class, 'getNotifications']);
    Route::post('/notifications/read-all', [InterconnectedApiController::class, 'markAllNotificationsRead']);
    Route::post('/notifications/{id}/read', [InterconnectedApiController::class, 'markNotificationRead']);
    Route::get('/chats/{queueId}', [InterconnectedApiController::class, 'getChats']);
    Route::post('/chats/{queueId}', [InterconnectedApiController::class, 'sendChat']);
    Route::post('/approvals/{queueId}', [InterconnectedApiController::class, 'addApprovalItem']);
    Route::post('/approvals/{itemId}/respond', [InterconnectedApiController::class, 'respondApprovalItem']);
    Route::get('/tracking/{queueId}', [InterconnectedApiController::class, 'getLiveTracking']);
});

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('dashboard.admin'))->name('dashboard');
    Route::get('/notifikasi', [AdminNotificationsController::class, 'index'])->name('notifications.index');
    Route::get('/chat', [AdminQueueController::class, 'chats'])->name('chats');
    Route::get('/persetujuan-sparepart', [AdminQueueController::class, 'approvals'])->name('approvals');
    Route::get('/notifikasi/{notification}/buka', [NotificationController::class, 'open'])->name('notifications.open');
    Route::post('/notifikasi/baca-semua', [AdminNotificationsController::class, 'markAllRead'])->name('notifications.read-all');

    Route::get('/antrian', [AdminQueueController::class, 'index'])->name('queue.index');
    Route::get('/antrian/create', [AdminQueueController::class, 'create'])->name('queue.create');
    Route::post('/antrian', [AdminQueueController::class, 'store'])->name('queue.store');
    Route::get('/antrian/{id}', [AdminQueueController::class, 'show'])->name('queue.show');
    Route::put('/antrian/{id}/status', [AdminQueueController::class, 'updateStatus'])->name('queue.updateStatus');
    Route::delete('/antrian/{id}', [AdminQueueController::class, 'destroy'])->name('queue.destroy');
    Route::post('/booking-request/{id}/accept', [AdminQueueController::class, 'acceptBooking'])->name('booking.accept');
    Route::post('/booking-request/{id}/reject', [AdminQueueController::class, 'rejectBooking'])->name('booking.reject');

    Route::get('/customers', [AdminCustomersController::class, 'index'])->name('customers.index');
    Route::get('/mechanics', [AdminMechanicsController::class, 'index'])->name('mechanics.index');
    Route::post('/mechanics', [AdminMechanicsController::class, 'store'])->name('mechanics.store');
    Route::get('/customers/{id}', [AdminCustomersController::class, 'show'])->name('customers.show');
    Route::put('/customers/{id}', [AdminCustomersController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{id}', [AdminCustomersController::class, 'destroy'])->name('customers.destroy');
    Route::post('/customers/{user_id}/vehicles', [AdminCustomersController::class, 'addVehicle'])->name('vehicles.store');
    Route::put('/admin-vehicles/{id}', [AdminCustomersController::class, 'updateVehicle'])->name('vehicles.update');
    Route::delete('/admin-vehicles/{id}', [AdminCustomersController::class, 'deleteVehicle'])->name('vehicles.destroy');

    Route::get('/history', [AdminHistoryController::class, 'index'])->name('history.index');
    Route::get('/history/export', [AdminHistoryController::class, 'export'])->name('history.export');
    Route::get('/history-stats', [AdminHistoryController::class, 'statistics'])->name('history.stats');
    Route::get('/history/{id}', [AdminHistoryController::class, 'show'])->name('history.show');

    Route::get('/pembayaran', [AdminPaymentsController::class, 'index'])->name('payments');
    Route::put('/pembayaran/{serviceHistory}', [AdminPaymentsController::class, 'update'])->name('payments.update');
    Route::get('/pembayaran/{serviceHistory}/bukti', [AdminPaymentsController::class, 'proof'])->name('payments.proof');

    Route::get('/reminders', [AdminRemindersController::class, 'index'])->name('reminders.index');
    Route::get('/reminders/create', [AdminRemindersController::class, 'create'])->name('reminders.create');
    Route::post('/reminders', [AdminRemindersController::class, 'store'])->name('reminders.store');
    Route::get('/reminders/{id}/edit', [AdminRemindersController::class, 'edit'])->name('reminders.edit');
    Route::put('/reminders/{id}', [AdminRemindersController::class, 'update'])->name('reminders.update');
    Route::delete('/reminders/{id}', [AdminRemindersController::class, 'destroy'])->name('reminders.destroy');
    Route::post('/reminders/{id}/send', [AdminRemindersController::class, 'sendNotification'])->name('reminders.send');

    Route::get('/inventory', [AdminInventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory', [AdminInventoryController::class, 'store'])->name('inventory.store');
    Route::put('/inventory/{id}', [AdminInventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/{id}', [AdminInventoryController::class, 'destroy'])->name('inventory.destroy');
});

Route::middleware(['auth', 'verified', 'role:mekanik,teknisi'])->prefix('mekanik')->name('dashboard.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'mechanic'])->name('mechanic');
    Route::patch('/queues/{serviceQueue}/status', [DashboardController::class, 'updateQueueStatus'])
        ->name('mechanic.queue-status');
    Route::patch('/queues/{serviceQueue}/notes', [DashboardController::class, 'updateMechanicNotes'])
        ->name('mechanic.queue-notes');
    Route::patch('/queues/{serviceQueue}/odometer', [DashboardController::class, 'recordMechanicOdometer'])
        ->name('mechanic.queue-odometer');
    Route::post('/queues/{serviceQueue}/parts', [DashboardController::class, 'proposeMechanicPart'])
        ->name('mechanic.queue-parts');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
