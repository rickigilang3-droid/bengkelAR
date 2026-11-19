@php
    $currentUser = auth()->user();
    $role = $currentUser?->getAttribute('role');
    $roleLabel = match ($role) {
        'admin' => 'Administrator',
        'pelanggan' => 'Pelanggan',
        'mekanik', 'teknisi' => 'Mekanik / teknisi',
        default => 'Pengguna',
    };

    $menuItems = match ($role) {
        'admin' => [
            ['label' => 'Ringkasan', 'route' => 'dashboard.admin', 'pattern' => 'dashboard.admin', 'icon' => 'grid_view'],
            ['label' => 'Antrean servis', 'route' => 'admin.queue.index', 'pattern' => 'admin.queue.*', 'icon' => 'format_list_numbered'],
            ['label' => 'Chat pelanggan', 'route' => 'admin.chats', 'pattern' => 'admin.chats', 'icon' => 'chat'],
            ['label' => 'Persetujuan sparepart', 'route' => 'admin.approvals', 'pattern' => 'admin.approvals', 'icon' => 'fact_check'],
            ['label' => 'Pelanggan', 'route' => 'admin.customers.index', 'pattern' => 'admin.customers.*', 'icon' => 'groups'],
            ['label' => 'Pembayaran', 'route' => 'admin.payments', 'pattern' => 'admin.payments*', 'icon' => 'payments'],
            ['label' => 'Riwayat servis', 'route' => 'admin.history.index', 'pattern' => 'admin.history.*', 'icon' => 'history'],
            ['label' => 'Inventaris', 'route' => 'admin.inventory.index', 'pattern' => 'admin.inventory.*', 'icon' => 'inventory_2'],
            ['label' => 'Pengingat', 'route' => 'admin.reminders.index', 'pattern' => 'admin.reminders.*', 'icon' => 'notifications_active'],
            ['label' => 'Notifikasi', 'route' => 'admin.notifications.index', 'pattern' => 'admin.notifications.*', 'icon' => 'notifications'],
        ],
        'pelanggan' => [
            ['label' => 'Ringkasan', 'route' => 'dashboard.customer', 'pattern' => 'dashboard.customer', 'icon' => 'grid_view'],
            ['label' => 'Booking servis', 'route' => 'customer.bookings', 'pattern' => 'customer.bookings*', 'icon' => 'event_available'],
            ['label' => 'Pembayaran', 'route' => 'customer.payments', 'pattern' => 'customer.payments*', 'icon' => 'payments'],
            ['label' => 'Chat dengan bengkel', 'route' => 'customer.chats', 'pattern' => 'customer.chats', 'icon' => 'chat'],
            ['label' => 'Persetujuan sparepart', 'route' => 'customer.approvals', 'pattern' => 'customer.approvals', 'icon' => 'fact_check'],
            ['label' => 'Kendaraan saya', 'route' => 'customer.vehicles', 'pattern' => 'customer.vehicles*', 'icon' => 'two_wheeler'],
            ['label' => 'Sparepart', 'route' => 'customer.inventory', 'pattern' => 'customer.inventory', 'icon' => 'inventory_2'],
            ['label' => 'Riwayat servis', 'route' => 'customer.history', 'pattern' => 'customer.history', 'icon' => 'history'],
            ['label' => 'Notifikasi', 'route' => 'customer.notifications', 'pattern' => 'customer.notifications*', 'icon' => 'notifications'],
        ],
        'mekanik', 'teknisi' => [
            ['label' => 'Ringkasan mekanik', 'route' => 'dashboard.mechanic', 'pattern' => 'dashboard.mechanic', 'icon' => 'grid_view', 'status' => 'active'],
            ['label' => 'Antrean menunggu', 'route' => 'dashboard.mechanic', 'pattern' => 'dashboard.mechanic', 'icon' => 'schedule', 'status' => 'waiting'],
            ['label' => 'Sedang dikerjakan', 'route' => 'dashboard.mechanic', 'pattern' => 'dashboard.mechanic', 'icon' => 'build_circle', 'status' => 'in_progress'],
            ['label' => 'Pengajuan menunggu', 'route' => 'dashboard.mechanic', 'pattern' => 'dashboard.mechanic', 'icon' => 'fact_check', 'status' => 'pending_approval'],
            ['label' => 'Riwayat selesai', 'route' => 'dashboard.mechanic', 'pattern' => 'dashboard.mechanic', 'icon' => 'history', 'status' => 'completed'],
        ],
        default => [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'pattern' => 'dashboard', 'icon' => 'grid_view'],
        ],
    };

    $unreadNotifications = $unreadCount ?? 0;
    $pendingApprovalCount = $role === 'admin'
        ? \App\Models\ServiceApprovalItem::query()->where('status', 'pending')->count()
        : ($role === 'pelanggan'
            ? \App\Models\ServiceApprovalItem::query()
                ->where('status', 'pending')
                ->whereHas('serviceQueue', fn ($query) => $query->where('user_id', $currentUser->id))
                ->count()
            : 0);
    $unpaidPaymentCount = $role === 'admin'
        ? \App\Models\ServiceHistory::query()->where('payment_status', '!=', 'paid')->where('total_cost', '>', 0)->count()
        : ($role === 'pelanggan'
            ? $currentUser->serviceHistories()
                ->where('payment_status', '!=', 'paid')
                ->where('total_cost', '>', 0)
                ->where('total_cost', '>', 0)
                ->count()
            : 0);
@endphp

<nav x-data="{ sidebarOpen: false }" aria-label="Navigasi utama">
    <div
        x-cloak
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-slate-950/40 backdrop-blur-[2px] md:hidden"
        aria-hidden="true"
    ></div>

    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
        class="fixed inset-y-0 left-0 z-50 flex w-[272px] flex-col border-r border-slate-200 bg-white transition-transform duration-200 md:translate-x-0"
        aria-label="Sidebar Bengkel AR"
    >
        <div class="flex h-[76px] items-center justify-between border-b border-slate-100 px-5">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" aria-label="Bengkel AR, dashboard">
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-indigo-700 text-white shadow-sm shadow-indigo-900/20">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M14.7 6.3a5 5 0 0 0-6.4 6.4L3.5 17.5a2.1 2.1 0 0 0 3 3l4.8-4.8a5 5 0 0 0 6.4-6.4l-3.1 3.1-3.1-3.1 3.2-3Z"/>
                    </svg>
                </span>
                <span class="text-lg font-extrabold tracking-tight text-slate-900">Bengkel <span class="text-indigo-700">AR</span></span>
            </a>
            <button type="button" @click="sidebarOpen = false" class="grid h-9 w-9 place-items-center rounded-xl text-slate-500 hover:bg-slate-100 md:hidden" aria-label="Tutup menu">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
            </button>
        </div>

        <div class="px-5 pb-2 pt-6">
            <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Menu {{ $roleLabel }}</p>
        </div>

        <div class="flex-1 space-y-1 overflow-y-auto px-3 pb-5">
            @foreach ($menuItems as $item)
                @php($active = request()->routeIs($item['pattern']) && (! isset($item['status']) || request()->query('status', 'active') === $item['status']))
                <a
                    href="{{ route($item['route'], isset($item['status']) ? ['status' => $item['status'] === 'active' ? null : $item['status']] : []) }}"
                    @click="sidebarOpen = false"
                    @class([
                        'group flex min-h-11 items-center gap-3 rounded-xl px-3 text-sm font-semibold transition',
                        'bg-indigo-50 text-indigo-800 ring-1 ring-inset ring-indigo-100' => $active,
                        'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! $active,
                    ])
                    @if ($active) aria-current="page" @endif
                >
                    <span @class([
                        'material-symbols-outlined text-[20px]',
                        'text-indigo-700' => $active,
                        'text-slate-400 group-hover:text-slate-600' => ! $active,
                    ]) aria-hidden="true">{{ $item['icon'] }}</span>
                    <span class="flex-1">{{ $item['label'] }}</span>
                    @if (in_array($item['route'], ['customer.notifications', 'admin.notifications.index'], true) && $unreadNotifications > 0)
                        <span class="min-w-5 rounded-full bg-rose-100 px-1.5 py-0.5 text-center text-[10px] font-bold text-rose-700">{{ $unreadNotifications > 9 ? '9+' : $unreadNotifications }}</span>
                    @endif
                    @if (in_array($item['route'], ['customer.approvals', 'admin.approvals'], true) && $pendingApprovalCount > 0)
                        <span class="min-w-5 rounded-full bg-amber-100 px-1.5 py-0.5 text-center text-[10px] font-bold text-amber-800">{{ $pendingApprovalCount > 9 ? '9+' : $pendingApprovalCount }}</span>
                    @endif
                    @if (in_array($item['route'], ['customer.payments', 'admin.payments'], true) && $unpaidPaymentCount > 0)
                        <span class="min-w-5 rounded-full bg-rose-100 px-1.5 py-0.5 text-center text-[10px] font-bold text-rose-700">{{ $unpaidPaymentCount > 9 ? '9+' : $unpaidPaymentCount }}</span>
                    @endif
                    @if ($active)
                        <span class="h-1.5 w-1.5 rounded-full bg-indigo-600" aria-hidden="true"></span>
                    @endif
                </a>
            @endforeach

            <div class="mx-3 my-4 border-t border-slate-100"></div>
            <a
                href="{{ route('profile.edit') }}"
                @click="sidebarOpen = false"
                @class([
                    'group flex min-h-11 items-center gap-3 rounded-xl px-3 text-sm font-semibold transition',
                    'bg-indigo-50 text-indigo-800 ring-1 ring-inset ring-indigo-100' => request()->routeIs('profile.*'),
                    'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! request()->routeIs('profile.*'),
                ])
                @if (request()->routeIs('profile.*')) aria-current="page" @endif
            >
                <span class="material-symbols-outlined text-[20px] text-slate-400" aria-hidden="true">manage_accounts</span>
                <span>Profil akun</span>
            </a>
        </div>

        <div class="border-t border-slate-100 p-3">
            <div class="mb-2 flex items-center gap-3 rounded-xl bg-slate-50 p-3">
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-800">{{ strtoupper(substr($currentUser?->name ?? 'U', 0, 1)) }}</span>
                <span class="min-w-0 flex-1">
                    <span class="block truncate text-sm font-bold text-slate-800">{{ $currentUser?->name }}</span>
                    <span class="mt-0.5 block truncate text-xs text-slate-500">{{ $roleLabel }}</span>
                </span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex min-h-10 w-full items-center gap-3 rounded-xl px-3 text-sm font-semibold text-slate-500 transition hover:bg-rose-50 hover:text-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500">
                    <span class="material-symbols-outlined text-[19px]" aria-hidden="true">logout</span>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <div class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-slate-200 bg-white/95 px-4 backdrop-blur md:hidden">
        <button type="button" @click="sidebarOpen = true" class="inline-flex h-10 items-center gap-2 rounded-xl px-2 text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500" aria-label="Buka menu navigasi">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            <span class="font-bold">Bengkel AR</span>
        </button>
        <span class="max-w-[45%] truncate text-xs font-semibold text-slate-500">{{ $roleLabel }}</span>
    </div>
</nav>
