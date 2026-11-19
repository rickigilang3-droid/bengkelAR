<?php

namespace App\Http\Controllers;

use App\Models\ServiceQueue;
use App\Models\ServiceHistory;
use App\Models\Inventory;
use App\Models\Notification;
use App\Models\User;
use App\Services\SyncServiceHistoryFromQueue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    public function index(): RedirectResponse
    {
        $role = request()->user()->role;

        return match ($role) {
            User::ROLE_ADMIN => redirect()->route('dashboard.admin'),
            User::ROLE_CUSTOMER => redirect()->route('dashboard.customer'),
            User::ROLE_MECHANIC, User::ROLE_TECHNICIAN => redirect()->route('dashboard.mechanic'),
            default => abort(403, 'Role akun belum memiliki akses dashboard.'),
        };
    }

    public function admin(): View
    {
        $queueCounts = ServiceQueue::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $recentQueues = ServiceQueue::query()
            ->with(['user', 'vehicle'])
            ->latest('entered_at')
            ->take(6)
            ->get();

        return view('dashboard.roles.admin', [
            'customerCount' => User::query()->where('role', User::ROLE_CUSTOMER)->count(),
            'mechanicCount' => User::query()->whereIn('role', [User::ROLE_MECHANIC, User::ROLE_TECHNICIAN])->count(),
            'queueCounts' => $queueCounts,
            'completedTodayCount' => ServiceQueue::query()
                ->where('status', 'completed')
                ->whereDate('completed_at', today())
                ->count(),
            'monthlyRevenue' => ServiceHistory::thisMonth()
                ->where('payment_status', 'paid')
                ->sum('total_cost'),
            'recentQueues' => $recentQueues,
        ]);
    }

    public function customer(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        return view('dashboard.roles.customer', [
            'vehicleCount' => $user->vehicles()->count(),
            'activeQueue' => $user->serviceQueues()
                ->with('vehicle')
                ->whereIn('status', ['waiting', 'in_progress'])
                ->latest('entered_at')
                ->first(),
            'recentHistory' => $user->serviceHistories()
                ->with('vehicle')
                ->latest('completed_at')
                ->take(5)
                ->get(),
        ]);
    }

    public function mechanic(Request $request): View
    {
        $validated = $request->validate([
            'status' => ['nullable', Rule::in(['active', 'waiting', 'in_progress', 'completed', 'cancelled', 'pending_approval', 'all'])],
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $status = $validated['status'] ?? 'active';
        $search = trim($validated['search'] ?? '');
        $query = ServiceQueue::query()
            ->with([
                'user',
                'vehicle',
                'partReplacements.inventory',
                'approvalItems.inventory',
            ]);

        if ($status === 'active') {
            $query->whereIn('status', ['waiting', 'in_progress']);
        } elseif ($status === 'pending_approval') {
            $query->whereIn('status', ['waiting', 'in_progress'])
                ->whereHas('approvalItems', fn (Builder $approvalQuery) => $approvalQuery
                    ->where('status', 'pending')
                    ->where('requested_by', $request->user()->id));
        } elseif ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->where(function (Builder $builder) use ($search): void {
                $builder->where('complaint', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhereHas('user', fn (Builder $userQuery) => $userQuery->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('vehicle', fn (Builder $vehicleQuery) => $vehicleQuery
                        ->where('plate_number', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%"));
            });
        }

        $query
            ->orderByRaw("CASE status WHEN 'in_progress' THEN 0 WHEN 'waiting' THEN 1 ELSE 2 END")
            ->orderByRaw("CASE priority WHEN 'high' THEN 0 WHEN 'medium' THEN 1 ELSE 2 END");
        if ($status === 'completed') {
            $query->orderByDesc('completed_at');
        } else {
            $query->orderBy('entered_at');
        }
        $queues = $query->paginate(10)->withQueryString();

        return view('dashboard.roles.mechanic', [
            'queues' => $queues,
            'inventories' => Inventory::query()->where('stock', '>', 0)->orderBy('name')->get(),
            'queueCounts' => ServiceQueue::query()
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
            'pendingApprovalCount' => \App\Models\ServiceApprovalItem::query()
                ->where('status', 'pending')
                ->where('requested_by', $request->user()->id)
                ->count(),
            'maintenanceGuides' => config('maintenance.guides', []),
            'statusFilter' => $status,
            'search' => $search,
        ]);
    }

    public function updateMechanicNotes(Request $request, ServiceQueue $serviceQueue): RedirectResponse
    {
        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        DB::transaction(function () use ($serviceQueue, $validated): void {
            $queue = ServiceQueue::query()->lockForUpdate()->findOrFail($serviceQueue->id);
            abort_unless(\in_array($queue->status, ['waiting', 'in_progress'], true), 422, 'Catatan hanya dapat diubah pada servis aktif.');
            $queue->update(['notes' => $validated['notes'] ?? null]);
        });

        return back()->with('status', 'Catatan pekerjaan berhasil disimpan.');
    }

    public function recordMechanicOdometer(Request $request, ServiceQueue $serviceQueue): RedirectResponse
    {
        abort_unless(\in_array($serviceQueue->status, ['waiting', 'in_progress'], true), 422, 'Kilometer hanya dapat dicatat pada servis aktif.');

        $validated = $request->validate([
            'odometer_km' => ['required', 'integer', 'min:0', 'max:9999999'],
        ]);

        DB::transaction(function () use ($serviceQueue, $validated): void {
            $queue = ServiceQueue::query()->lockForUpdate()->findOrFail($serviceQueue->id);
            abort_unless(\in_array($queue->status, ['waiting', 'in_progress'], true), 422, 'Kilometer hanya dapat dicatat pada servis aktif.');

            $vehicle = $queue->vehicle()->lockForUpdate()->firstOrFail();
            abort_if(
                $vehicle->current_odometer_km !== null && $validated['odometer_km'] < $vehicle->current_odometer_km,
                422,
                'Kilometer servis tidak boleh lebih rendah dari catatan kendaraan sebelumnya.'
            );

            $queue->update(['odometer_km' => $validated['odometer_km']]);
            $vehicle->update(['current_odometer_km' => $validated['odometer_km']]);
        });

        return back()->with('status', 'Kilometer kendaraan berhasil dicatat.');
    }

    public function proposeMechanicPart(Request $request, ServiceQueue $serviceQueue): RedirectResponse
    {
        abort_unless(\in_array($serviceQueue->status, ['waiting', 'in_progress'], true), 422, 'Sparepart hanya dapat diajukan pada servis aktif.');

        $validated = $request->validate([
            'inventory_id' => ['required', 'integer', 'exists:inventories,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
            'odometer_km' => ['required', 'integer', 'min:0', 'max:9999999'],
            'interval_km' => ['required', 'integer', 'min:1', 'max:9999999'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($request, $serviceQueue, $validated): void {
            $queue = ServiceQueue::query()->lockForUpdate()->findOrFail($serviceQueue->id);
            abort_unless(\in_array($queue->status, ['waiting', 'in_progress'], true), 422, 'Servis sudah tidak aktif.');

            $vehicle = $queue->vehicle()->lockForUpdate()->firstOrFail();
            $inventory = Inventory::query()->lockForUpdate()->findOrFail($validated['inventory_id']);
            abort_if($inventory->stock < $validated['quantity'], 422, 'Stok sparepart tidak mencukupi.');
            abort_if(
                $vehicle->current_odometer_km !== null && $validated['odometer_km'] < $vehicle->current_odometer_km,
                422,
                'Kilometer servis tidak boleh lebih rendah dari catatan kendaraan.'
            );

            $approval = \App\Models\ServiceApprovalItem::query()->create([
                'service_queue_id' => $queue->id,
                'inventory_id' => $inventory->id,
                'requested_by' => $request->user()->id,
                'item_name' => $inventory->name,
                'estimated_cost' => (float) $inventory->selling_price * $validated['quantity'],
                'quantity' => $validated['quantity'],
                'odometer_km' => $validated['odometer_km'],
                'interval_km' => $validated['interval_km'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
            ]);

            Notification::create([
                'user_id' => $queue->user_id,
                'title' => 'Persetujuan sparepart diperlukan',
                'message' => $request->user()->name . ' mengajukan ' . $approval->item_name . ' (×' . $approval->quantity . ') untuk servis #' . $queue->id . '.',
                'type' => 'approval_request',
                'link' => '/pelanggan/servis/' . $queue->id . '#approvals',
                'is_read' => false,
            ]);
        });

        return back()->with('status', 'Pengajuan sparepart terkirim. Stok baru dikurangi setelah pelanggan menyetujui.');
    }

    public function updateQueueStatus(
        Request $request,
        ServiceQueue $serviceQueue,
        SyncServiceHistoryFromQueue $historySync
    ): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['in_progress', 'completed'])],
        ]);

        DB::transaction(function () use ($serviceQueue, $validated, $historySync): void {
            $queue = ServiceQueue::query()->lockForUpdate()->findOrFail($serviceQueue->id);
            $allowedTransitions = [
                'waiting' => ['in_progress'],
                'in_progress' => ['completed'],
            ];
            $currentStatus = (string) $queue->getAttribute('status');

            abort_unless(
                \in_array($validated['status'], $allowedTransitions[$currentStatus] ?? [], true),
                422,
                'Status antrean tidak dapat diubah ke pilihan tersebut.'
            );

            $completedAt = $validated['status'] === 'completed' ? now() : null;
            $queue->update([
                'status' => $validated['status'],
                'completed_at' => $completedAt,
            ]);

            if ($completedAt) {
                $historySync->execute($queue);
            }

            Notification::create([
                'user_id' => $queue->user_id,
                'title' => $completedAt ? 'Servis Selesai' : 'Servis Mulai Dikerjakan',
                'message' => $completedAt
                    ? 'Servis kendaraan Anda telah selesai. Riwayat dan informasi biaya tersedia di portal pelanggan.'
                    : 'Mekanik mulai mengerjakan servis kendaraan Anda.',
                'type' => 'status_update',
                'link' => $completedAt ? '/pelanggan/riwayat' : '/pelanggan/servis/' . $queue->id,
                'is_read' => false,
            ]);
        });

        return back()->with('status', 'Status pekerjaan berhasil diperbarui.');
    }
}
