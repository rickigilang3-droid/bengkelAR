<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Admin Bengkel AR')</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
        .pt-safe { padding-top: env(safe-area-inset-top); }
        @media (max-width: 767px) {
            nav[aria-label="Sidebar admin"] {
                position: relative;
                width: 100%;
                height: auto;
                min-height: 0;
                padding-top: 0;
                padding-bottom: 0;
                border-right: 0;
                border-bottom: 1px solid #c4c5d5;
            }
            nav[aria-label="Sidebar admin"] > div:first-child {
                margin-bottom: 0;
                padding: 12px 16px;
            }
            nav[aria-label="Sidebar admin"] > div:nth-child(2) {
                padding: 0 12px 12px;
                overflow: visible;
            }
            nav[aria-label="Sidebar admin"] > div:nth-child(2) > p {
                display: none;
            }
            nav[aria-label="Sidebar admin"] > div:nth-child(2) > div {
                flex-direction: row;
                overflow-x: auto;
                padding-bottom: 2px;
            }
            nav[aria-label="Sidebar admin"] > div:nth-child(2) a {
                flex: 0 0 auto;
                padding: 9px 12px;
                white-space: nowrap;
            }
            nav[aria-label="Sidebar admin"] > div:last-child {
                display: none;
            }
            body > header {
                position: relative;
                top: auto;
                right: auto;
                left: auto;
                height: auto;
                min-height: 72px;
                padding: 12px 16px;
            }
            body > header > div:first-child > div:first-child {
                display: none;
            }
            body > header > div:first-child h1 {
                font-size: 16px;
            }
            body > header > div:first-child p {
                display: none;
            }
            body > main {
                margin-left: 0;
                padding: 24px 16px;
            }
        }
    </style>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        background: '#f7f9fb',
                        surface: '#f7f9fb',
                        'surface-container-lowest': '#ffffff',
                        'surface-container': '#eceef0',
                        'surface-container-low': '#f2f4f6',
                        'on-surface': '#191c1e',
                        primary: '#00288e',
                        'primary-container': '#a8b8ff',
                        secondary: '#505f76',
                        'tertiary-container': '#00563a',
                        outline: '#757684',
                        'outline-variant': '#c4c5d5',
                        error: '#ba1a1a',
                        'on-primary': '#ffffff',
                        'on-surface-variant': '#444653'
                    },
                    borderRadius: {
                        lg: '0.5rem',
                        xl: '0.75rem',
                    },
                    spacing: {
                        'margin-desktop': '32px',
                        'margin-mobile': '16px',
                        md: '16px',
                        lg: '24px',
                        xl: '32px',
                    },
                },
            },
        }
    </script>
</head>
<body class="bg-background text-on-surface font-body-md antialiased min-h-screen">
    @php
        $active = $active ?? '';
        $currentUser = Auth::user();
        $pendingApprovalCount = \App\Models\ServiceApprovalItem::query()->where('status', 'pending')->count();
        $unpaidPaymentCount = \App\Models\ServiceHistory::query()->where('payment_status', '!=', 'paid')->where('total_cost', '>', 0)->count();
        $navItems = [
            ['label' => 'Ringkasan', 'route' => 'dashboard.admin', 'icon' => 'grid_view', 'pattern' => 'dashboard.admin'],
            ['label' => 'Antrean servis', 'route' => 'admin.queue.index', 'icon' => 'queue', 'pattern' => 'admin.queue.*'],
            ['label' => 'Chat pelanggan', 'route' => 'admin.chats', 'icon' => 'chat', 'pattern' => 'admin.chats'],
            ['label' => 'Persetujuan sparepart', 'route' => 'admin.approvals', 'icon' => 'fact_check', 'pattern' => 'admin.approvals'],
            ['label' => 'Pelanggan', 'route' => 'admin.customers.index', 'icon' => 'group', 'pattern' => 'admin.customers.*'],
            ['label' => 'Akun mekanik', 'route' => 'admin.mechanics.index', 'icon' => 'engineering', 'pattern' => 'admin.mechanics.*'],
            ['label' => 'Pembayaran', 'route' => 'admin.payments', 'icon' => 'payments', 'pattern' => 'admin.payments*'],
            ['label' => 'Riwayat servis', 'route' => 'admin.history.index', 'icon' => 'history', 'pattern' => 'admin.history.*'],
            ['label' => 'Pengingat', 'route' => 'admin.reminders.index', 'icon' => 'notifications_active', 'pattern' => 'admin.reminders.*'],
            ['label' => 'Inventaris & sparepart', 'route' => 'admin.inventory.index', 'icon' => 'inventory_2', 'pattern' => 'admin.inventory.*'],
            ['label' => 'Notifikasi', 'route' => 'admin.notifications.index', 'icon' => 'notifications', 'pattern' => 'admin.notifications.*'],
        ];
    @endphp

    <!-- Sidebar Navigation -->
    <nav role="navigation" aria-label="Sidebar admin" class="bg-surface-container-lowest fixed left-0 top-0 h-screen w-[260px] border-r border-outline-variant flex flex-col py-md shadow-sm z-20">
        <!-- Brand / Logo -->
        <div class="px-6 py-4 mb-4 flex items-center gap-3">
            <div class="bg-primary text-white p-2 rounded-xl shadow-md shadow-primary/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-[26px]" style="font-variation-settings: 'FILL' 1;">build_circle</span>
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-primary tracking-tight">Bengkel AR</h1>
                <p class="text-xs font-semibold text-secondary/80 uppercase tracking-widest mt-0.5">Panel Admin</p>
            </div>
        </div>

        <!-- Main Navigation -->
        <div class="flex-1 overflow-y-auto px-4 pb-4">
            <p class="text-xs font-bold uppercase tracking-[0.15em] text-secondary/60 mb-3 px-2">Menu Utama</p>
            <div class="flex flex-col gap-1.5">
                @foreach ($navItems as $item)
                    @php
                        $isActive = request()->routeIs($item['pattern']);
                    @endphp
                    <a
                        class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 ease-in-out text-sm font-medium {{ $isActive ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary' }}"
                        href="{{ route($item['route']) }}"
                        @if ($isActive) aria-current="page" @endif
                    >
                        <span class="material-symbols-outlined transition-transform duration-200 {{ $isActive ? '' : 'group-hover:scale-110' }}" style="font-variation-settings: 'FILL' {{ $isActive ? 1 : 0 }};">{{ $item['icon'] }}</span>
                        <span>{{ $item['label'] }}</span>
                        @if ($item['route'] === 'admin.approvals' && $pendingApprovalCount > 0)
                            <span class="min-w-5 rounded-full bg-amber-100 px-1.5 py-0.5 text-center text-[10px] font-bold text-amber-800">{{ $pendingApprovalCount > 9 ? '9+' : $pendingApprovalCount }}</span>
                        @endif
                        @if ($item['route'] === 'admin.payments' && $unpaidPaymentCount > 0)
                            <span class="min-w-5 rounded-full bg-rose-100 px-1.5 py-0.5 text-center text-[10px] font-bold text-rose-700">{{ $unpaidPaymentCount > 9 ? '9+' : $unpaidPaymentCount }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        <!-- User Profile & Logout -->
        <div class="px-4 py-4 border-t border-outline-variant/30 mt-auto bg-surface-container-lowest">
            <div class="flex items-center gap-3 px-3 py-2 mb-3 rounded-xl bg-surface-container-low border border-outline-variant/50">
                <div class="w-10 h-10 rounded-full bg-primary/20 text-primary flex items-center justify-center font-bold text-lg shrink-0">
                    {{ substr($currentUser?->name ?? 'A', 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-on-surface truncate">{{ $currentUser?->name ?? 'Admin' }}</p>
                    <p class="text-xs text-secondary truncate">{{ $currentUser?->email ?? 'Administrator' }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="group flex w-full items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-error hover:bg-error/10 transition-colors duration-200">
                    <span class="material-symbols-outlined group-hover:-translate-x-1 transition-transform duration-200">logout</span>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- Top Header -->
    <header class="bg-surface-container-lowest/80 backdrop-blur-md fixed top-0 right-0 left-[260px] h-20 border-b border-outline-variant/40 flex items-center justify-between px-8 z-10 shadow-sm transition-all duration-300">
        <div class="flex items-center gap-4">
            <div class="bg-primary/10 p-2.5 rounded-xl text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1;">dataset</span>
            </div>
            <div>
                <h1 class="text-xl font-bold text-on-surface tracking-tight">{{ $title ?? 'Admin Panel' }}</h1>
                <p class="text-sm font-medium text-secondary mt-0.5">{{ $description ?? 'Kelola sistem bengkel Anda dengan mudah.' }}</p>
            </div>
        </div>
        
        <div class="flex items-center gap-4 relative">
            <div class="text-right hidden md:block mr-2">
                <p class="text-sm font-bold text-on-surface">{{ now()->translatedFormat('l, d F Y') }}</p>
                <p class="text-xs text-secondary">Status Sistem: <span class="text-green-600 font-bold">Online</span></p>
            </div>
            
            <!-- Admin Notification Bell -->
            <button id="admin-notif-btn" onclick="toggleAdminNotif()" class="relative p-2.5 rounded-xl text-secondary hover:bg-surface-container-low hover:text-primary transition-colors duration-200" title="Notifikasi Admin">
                <span class="material-symbols-outlined">notifications</span>
                @if(($unreadCount ?? 0) > 0)
                    <span id="admin-notif-badge" class="absolute top-2 right-2 flex h-4 w-4 items-center justify-center rounded-full bg-error text-[10px] font-bold text-white shadow">{{ $unreadCount }}</span>
                @endif
            </button>

            <!-- Admin Notification Dropdown Modal -->
            <div id="admin-notif-dropdown" class="hidden absolute right-0 top-14 w-80 md:w-96 bg-white border border-outline-variant/60 rounded-2xl shadow-2xl z-50 overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 bg-slate-50 border-b border-outline-variant/40">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">notifications</span>
                        <h3 class="font-bold text-sm text-on-surface">Notifikasi Admin</h3>
                    </div>
                    <button onclick="markAdminNotificationsRead()" class="text-[11px] text-primary hover:underline font-bold">Tandai Dibaca</button>
                </div>
                <div class="max-h-80 overflow-y-auto divide-y divide-slate-100">
                    @if(isset($notifications) && count($notifications) > 0)
                        @foreach($notifications as $n)
                            <a href="{{ route('admin.notifications.open', $n) }}" class="block p-3.5 transition-colors hover:bg-slate-50 {{ $n->is_read ? 'opacity-65' : 'bg-blue-50/50' }}">
                                <div class="flex items-start justify-between gap-2">
                                    <h4 class="font-bold text-xs text-slate-900">{{ $n->title }}</h4>
                                    <span class="text-[10px] text-slate-400 font-medium">{{ $n->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $n->message }}</p>
                                <span class="mt-2 inline-flex items-center gap-1 text-[11px] font-bold text-primary">Buka detail <span aria-hidden="true">→</span></span>
                            </a>
                        @endforeach
                    @else
                        <div class="p-6 text-center text-slate-400 text-xs font-medium">Belum ada notifikasi admin</div>
                    @endif
                </div>
                <a href="{{ route('admin.notifications.index') }}" class="block border-t border-slate-100 bg-white px-4 py-3 text-center text-xs font-bold text-primary hover:bg-slate-50">Lihat semua notifikasi</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="ml-[260px] pt-[104px] px-8 pb-10">
        <!-- Alerts -->
        @if ($errors->any())
            <div class="mb-6 bg-error/10 border border-error rounded-lg p-4">
                <p class="font-semibold text-error mb-2">Terjadi kesalahan:</p>
                <ul class="list-disc list-inside text-error">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 rounded-lg p-4">
                <p class="font-semibold text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 rounded-lg p-4">
                <p class="font-semibold text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </main>

    <script>
        function toggleAdminNotif() {
            const dd = document.getElementById('admin-notif-dropdown');
            dd?.classList.toggle('hidden');
        }

        function markAdminNotificationsRead() {
            fetch('/api/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const badge = document.getElementById('admin-notif-badge');
                    if (badge) badge.style.display = 'none';
                    location.reload();
                }
            });
        }

        document.addEventListener('click', function(e) {
            const btn = document.getElementById('admin-notif-btn');
            const dd = document.getElementById('admin-notif-dropdown');
            if (btn && dd && !btn.contains(e.target) && !dd.contains(e.target)) {
                dd.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
