<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Bengkel AR - Customer Dashboard</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                "colors": {
                    "on-secondary": "#ffffff",
                    "inverse-primary": "#b8c4ff",
                    "surface-container-high": "#e6e8ea",
                    "inverse-on-surface": "#eff1f3",
                    "secondary-fixed-dim": "#b7c8e1",
                    "on-primary": "#ffffff",
                    "on-surface-variant": "#444653",
                    "primary-fixed-dim": "#b8c4ff",
                    "surface-container-low": "#f2f4f6",
                    "on-secondary-fixed-variant": "#38485d",
                    "secondary-container": "#d0e1fb",
                    "on-error": "#ffffff",
                    "background": "#f7f9fb",
                    "on-tertiary-fixed": "#002113",
                    "surface-container-lowest": "#ffffff",
                    "surface-bright": "#f7f9fb",
                    "on-tertiary": "#ffffff",
                    "surface-tint": "#3755c3",
                    "on-primary-container": "#a8b8ff",
                    "on-background": "#191c1e",
                    "inverse-surface": "#2d3133",
                    "tertiary": "#003d27",
                    "surface-container": "#eceef0",
                    "secondary": "#505f76",
                    "primary-container": "#1e40af",
                    "tertiary-fixed": "#6ffbbe",
                    "on-primary-fixed": "#001453",
                    "surface-dim": "#d8dadc",
                    "on-tertiary-fixed-variant": "#005236",
                    "error": "#ba1a1a",
                    "outline-variant": "#c4c5d5",
                    "error-container": "#ffdad6",
                    "on-surface": "#191c1e",
                    "primary": "#00288e",
                    "on-secondary-container": "#54647a",
                    "tertiary-fixed-dim": "#4edea3",
                    "surface-container-highest": "#e0e3e5",
                    "on-primary-fixed-variant": "#173bab",
                    "tertiary-container": "#00563a",
                    "surface": "#f7f9fb",
                    "outline": "#757684",
                    "primary-fixed": "#dde1ff",
                    "on-tertiary-container": "#3fd298",
                    "surface-variant": "#e0e3e5",
                    "on-secondary-fixed": "#0b1c30",
                    "secondary-fixed": "#d3e4fe",
                    "on-error-container": "#93000a"
                },
                "borderRadius": {
                    "DEFAULT": "0.125rem",
                    "lg": "0.25rem",
                    "xl": "0.5rem",
                    "full": "0.75rem"
                },
                "spacing": {
                    "margin-mobile": "16px",
                    "sm": "8px",
                    "base": "4px",
                    "md": "16px",
                    "gutter": "24px",
                    "xl": "32px",
                    "margin-desktop": "32px",
                    "max-width": "1440px",
                    "lg": "24px",
                    "xs": "4px"
                },
                "fontFamily": {
                    "body-lg": ["Inter"],
                    "label-md": ["Inter"],
                    "tabular-nums": ["Inter"],
                    "headline-lg": ["Inter"],
                    "headline-md": ["Inter"],
                    "headline-lg-mobile": ["Inter"],
                    "body-md": ["Inter"]
                },
                "fontSize": {
                    "body-lg": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                    "label-md": ["12px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600" }],
                    "tabular-nums": ["14px", { "lineHeight": "20px", "fontWeight": "500" }],
                    "headline-lg": ["30px", { "lineHeight": "38px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                    "headline-md": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                    "headline-lg-mobile": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                    "body-md": ["14px", { "lineHeight": "20px", "fontWeight": "400" }]
                }
            }
        }
    }
</script>
<style>
    .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
    .pt-safe { padding-top: env(safe-area-inset-top); }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    body { min-height: max(884px, 100dvh); }
</style>
</head>
<body class="bg-background text-on-background font-body-lg min-h-screen flex flex-col pt-safe pb-safe selection:bg-primary selection:text-on-primary">

<!-- ===== MOBILE TOP APP BAR ===== -->
<!-- Hanya hamburger + logo, fitur lain sudah di sidebar -->
<header class="fixed top-0 w-full z-50 bg-surface border-b border-outline-variant md:hidden">
    <div class="flex items-center h-16 px-margin-mobile gap-3">
        <button
            id="mobile-sidebar-toggle"
            aria-label="Menu"
            aria-controls="mobile-sidebar"
            aria-expanded="false"
            class="flex items-center justify-center p-sm rounded-full text-primary hover:bg-surface-variant transition-colors active:scale-95 duration-150"
        >
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">menu</span>
        </button>
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[22px]" style="font-variation-settings: 'FILL' 0;">motorcycle</span>
            <h1 class="font-headline-md text-headline-md font-bold text-primary">Bengkel AR</h1>
        </div>
    </div>
</header>

<!-- ===== MOBILE SIDEBAR OVERLAY ===== -->
<div id="sidebar-overlay" class="hidden fixed inset-0 bg-black/30 z-30 md:hidden"></div>

<!-- ===== MOBILE SIDEBAR ===== -->
<aside
    id="mobile-sidebar"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-surface-container-lowest border-r border-outline-variant flex flex-col transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden"
>
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between px-md pt-md pb-sm border-b border-outline-variant">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[22px]" style="font-variation-settings: 'FILL' 0;">motorcycle</span>
            <div>
                <h1 class="font-headline-md text-headline-md font-bold text-primary leading-tight">Bengkel AR</h1>
                <p class="font-label-md text-label-md text-secondary">Customer Menu</p>
            </div>
        </div>
        <button
            type="button"
            aria-label="Tutup menu"
            data-sidebar-close
            class="flex items-center justify-center p-sm rounded-full text-on-surface-variant hover:bg-surface-variant transition-colors"
        >
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">close</span>
        </button>
    </div>

    <!-- User Info -->
    <div class="flex items-center gap-sm px-md py-sm mx-md mt-md bg-surface-container rounded-xl border border-outline-variant">
        <div class="w-9 h-9 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-bold font-label-md uppercase text-sm">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
        <div class="flex-1 min-w-0">
            <p class="font-tabular-nums text-tabular-nums font-bold text-on-surface truncate">{{ auth()->user()->name ?? 'Pelanggan' }}</p>
            <p class="font-label-md text-label-md text-secondary text-[10px]">250 Poin Loyalitas</p>
        </div>
    </div>

    <!-- Nav Links -->
    <nav class="flex flex-col gap-xs px-sm mt-md flex-1">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-md px-md py-sm rounded-lg bg-surface-container text-primary font-bold transition-colors duration-200">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">dashboard</span>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('booking') }}" class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">build_circle</span>
            <span>Booking Servis</span>
        </a>
        <a href="{{ route('inventaris') }}" class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">inventory_2</span>
            <span>Cek Stok Sparepart</span>
        </a>
        <a href="{{ route('riwayat') }}" class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">history</span>
            <span>Riwayat Servis</span>
        </a>
        <a href="javascript:void(0)" onclick="toggleNotifDropdown()" class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">notifications</span>
            <span>Notifikasi</span>
            <!-- Badge -->
            <span class="ml-auto bg-error text-on-error text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $unreadCount ?? 0 }}</span>
        </a>
        <a href="{{ route('profil') }}" class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">person</span>
            <span>Profil</span>
        </a>
    </nav>

    <!-- Logout at Bottom -->
    <div class="px-sm pb-md mt-auto border-t border-outline-variant pt-sm">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex w-full items-center gap-md px-md py-sm rounded-lg text-error hover:bg-error-container transition-colors duration-200">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">logout</span>
                <span class="font-label-md text-label-md">Logout</span>
            </button>
        </form>
    </div>
</aside>

<!-- ===== DESKTOP TOP BAR ===== -->
<header class="hidden md:flex fixed top-0 w-full z-50 bg-surface border-b border-outline-variant h-16 items-center justify-between px-margin-desktop shadow-sm">
    <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-primary text-[26px]" style="font-variation-settings: 'FILL' 0;">motorcycle</span>
        <h1 class="font-headline-md text-headline-md font-bold text-primary">Bengkel AR</h1>
    </div>
    <!-- Top Nav Links -->
    <nav class="flex gap-lg h-full items-center">
        <a href="{{ route('dashboard') }}" class="text-primary font-bold border-b-2 border-primary py-4 text-xs">Beranda</a>
        <a href="{{ route('booking') }}" class="text-on-surface-variant hover:text-primary transition-colors text-xs font-semibold">Booking Servis</a>
        <a href="{{ route('inventaris') }}" class="text-on-surface-variant hover:text-primary transition-colors text-xs font-semibold">Cek Stok Sparepart</a>
        <a href="{{ route('riwayat') }}" class="text-on-surface-variant hover:text-primary transition-colors text-xs font-semibold">Riwayat</a>
        <a href="{{ route('profil') }}" class="text-on-surface-variant hover:text-primary transition-colors text-xs font-semibold">Profil</a>
    </nav>
    <!-- User chip + Notif -->
    <div class="flex items-center gap-sm relative">
        <button id="notif-bell-btn" onclick="toggleNotifDropdown()" aria-label="Notifikasi" class="relative flex items-center justify-center p-sm rounded-full text-on-surface-variant hover:bg-surface-variant transition-colors">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">notifications</span>
            <span id="notif-badge" class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-error rounded-full border border-surface {{ ($unreadCount ?? 0) > 0 ? '' : 'hidden' }}"></span>
        </button>

        <!-- Notification Dropdown Modal -->
        <div id="notif-dropdown" class="hidden absolute right-12 top-12 w-80 md:w-96 bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-2xl z-50 overflow-hidden transition-all duration-200">
            <div class="flex items-center justify-between px-md py-sm bg-surface-container-low border-b border-outline-variant">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">notifications</span>
                    <h3 class="font-bold text-sm text-on-surface">Notifikasi Real-Time</h3>
                </div>
                <button onclick="markAllNotificationsRead()" class="text-[11px] text-primary hover:underline font-semibold">Tandai Dibaca</button>
            </div>
            <div id="notif-list-container" class="max-h-80 overflow-y-auto divide-y divide-outline-variant">
                @if(isset($notifications) && count($notifications) > 0)
                    @foreach($notifications as $n)
                        <div class="p-md hover:bg-surface-container-low transition-colors {{ $n->is_read ? 'opacity-70' : 'bg-primary-container/10' }}">
                            <div class="flex items-start justify-between gap-2">
                                <h4 class="font-bold text-xs text-on-surface">{{ $n->title }}</h4>
                                <span class="text-[10px] text-secondary">{{ $n->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-on-surface-variant mt-1">{{ $n->message }}</p>
                        </div>
                    @endforeach
                @else
                    <div class="p-lg text-center text-secondary text-xs">Belum ada notifikasi</div>
                @endif
            </div>
        </div>

        <a href="{{ route('profil') }}" class="flex items-center gap-sm px-sm py-xs bg-surface-container-low rounded-full border border-outline-variant cursor-pointer hover:bg-surface-variant transition-colors">
            <div class="w-7 h-7 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-bold font-label-md uppercase text-sm">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
            <span class="font-label-md text-label-md mr-xs text-on-surface">{{ auth()->user()->name ?? 'Pelanggan' }}</span>
        </a>
    </div>
</header>

<!-- ===== DESKTOP SIDEBAR ===== -->
<aside class="hidden md:flex fixed left-0 top-16 h-[calc(100vh-4rem)] w-[240px] flex-col bg-surface-container-lowest border-r border-outline-variant z-20">
    <!-- Brand -->
    <div class="px-lg py-md border-b border-outline-variant">
        <p class="font-label-md text-label-md text-secondary uppercase tracking-wider">Customer Portal</p>
    </div>

    <!-- Nav -->
    <nav class="flex flex-col gap-xs px-sm py-md flex-1">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-md px-md py-sm rounded-lg text-primary font-bold bg-surface-container border-l-4 border-primary transition-colors duration-200">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">dashboard</span>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('booking') }}" class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">build_circle</span>
            <span>Booking Servis</span>
        </a>
        <a href="{{ route('inventaris') }}" class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">inventory_2</span>
            <span>Cek Stok Sparepart</span>
        </a>
        <a href="{{ route('riwayat') }}" class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">history</span>
            <span>Riwayat Servis</span>
        </a>
        <a href="javascript:void(0)" onclick="toggleNotifDropdown()" class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">notifications</span>
            <span>Notifikasi</span>
            <span class="ml-auto bg-error text-on-error text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $unreadCount ?? 0 }}</span>
        </a>
        <a href="{{ route('profil') }}" class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">person</span>
            <span>Profil</span>
        </a>
    </nav>

    <!-- User Info + Logout -->
    <div class="px-sm pb-md border-t border-outline-variant pt-sm">
        <div class="flex items-center gap-sm px-md py-sm rounded-lg mb-sm">
            <div class="w-8 h-8 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-bold font-label-md uppercase">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
            <div class="flex-1 min-w-0">
                <p class="font-tabular-nums text-tabular-nums font-bold text-on-surface text-sm truncate">{{ auth()->user()->name ?? 'Pelanggan' }}</p>
                <p class="font-label-md text-label-md text-secondary text-[10px]">250 Poin</p>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex w-full items-center gap-md px-md py-sm rounded-lg text-error hover:bg-error-container transition-colors duration-200">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">logout</span>
                <span class="font-label-md text-label-md">Logout</span>
            </button>
        </form>
    </div>
</aside>

<!-- ===== MAIN CONTENT ===== -->
<main class="flex-grow pt-20 md:pt-20 pb-28 md:pb-8 px-margin-mobile md:px-margin-desktop max-w-max-width mx-auto w-full flex flex-col gap-lg md:ml-[240px] md:w-[calc(100%-240px)]">

    <!-- Welcome & Points Banner -->
    <section class="flex flex-col md:flex-row justify-between items-start md:items-center gap-md bg-surface p-md rounded-xl border border-outline-variant shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-primary-container opacity-20 rounded-bl-full -mr-8 -mt-8 pointer-events-none"></div>
        <div class="z-10">
            <h2 class="font-body-md text-body-md text-on-surface-variant mb-xs">Halo, selamat datang kembali 👋</h2>
            <h3 class="font-headline-lg-mobile md:text-headline-lg text-on-surface">{{ auth()->user()->name ?? 'Budi Santoso' }}</h3>
        </div>
        <div class="flex items-center gap-sm bg-tertiary-container text-on-tertiary-container px-md py-sm rounded-full z-10 shadow-sm border border-tertiary-fixed-dim/30">
            <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">loyalty</span>
            <div class="flex flex-col">
                <span class="font-label-md text-label-md uppercase opacity-80 text-[10px]">Poin Loyalitas</span>
                <span class="font-tabular-nums text-tabular-nums font-bold">250 Poin</span>
            </div>
        </div>
    </section>

    <!-- Aksi Cepat -->
    <section>
        <h4 class="font-headline-md text-headline-md-mobile md:text-headline-md mb-md text-on-surface">Aksi Cepat</h4>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-sm md:gap-md">
            <a href="{{ route('booking') }}" class="flex flex-col items-center justify-center gap-sm p-sm md:p-md bg-surface rounded-xl border border-outline-variant hover:bg-surface-container-low hover:border-primary/50 transition-all group active:scale-95 duration-150 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-primary-container/20 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-on-primary transition-colors duration-150">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">build_circle</span>
                </div>
                <span class="font-label-md text-label-md text-center text-on-surface-variant group-hover:text-primary transition-colors">Booking Servis</span>
            </a>
            <a href="{{ route('inventaris') }}" class="flex flex-col items-center justify-center gap-sm p-sm md:p-md bg-surface rounded-xl border border-outline-variant hover:bg-surface-container-low hover:border-blue-500/50 transition-all group active:scale-95 duration-150 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-150">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">inventory_2</span>
                </div>
                <span class="font-label-md text-label-md text-center text-on-surface-variant group-hover:text-blue-600 transition-colors">Cek Stok Part</span>
            </a>
            <a href="{{ route('riwayat') }}" class="flex flex-col items-center justify-center gap-sm p-sm md:p-md bg-surface rounded-xl border border-outline-variant hover:bg-surface-container-low hover:border-primary/50 transition-all group active:scale-95 duration-150 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-secondary-container/30 flex items-center justify-center text-secondary group-hover:bg-secondary group-hover:text-on-secondary transition-colors duration-150">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">history</span>
                </div>
                <span class="font-label-md text-label-md text-center text-on-surface-variant group-hover:text-secondary transition-colors">Riwayat Servis</span>
            </a>
            <button onclick="@if(isset($activeQueue)) openChatModal({{ $activeQueue->id }}) @else alert('Belum ada antrean servis aktif untuk live chat!') @endif" class="flex flex-col items-center justify-center gap-sm p-sm md:p-md bg-surface rounded-xl border border-outline-variant hover:bg-surface-container-low hover:border-primary/50 transition-all group active:scale-95 duration-150 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-150">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">chat</span>
                </div>
                <span class="font-label-md text-label-md text-center text-on-surface-variant group-hover:text-emerald-700 transition-colors">Chat Mekanik</span>
            </button>
            <a href="{{ route('profil') }}" class="flex flex-col items-center justify-center gap-sm p-sm md:p-md bg-surface rounded-xl border border-outline-variant hover:bg-surface-container-low hover:border-primary/50 transition-all group active:scale-95 duration-150 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-surface-variant flex items-center justify-center text-on-surface-variant group-hover:bg-on-surface-variant group-hover:text-surface transition-colors duration-150">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">person</span>
                </div>
                <span class="font-label-md text-label-md text-center text-on-surface-variant group-hover:text-on-surface transition-colors">Profil Saya</span>
            </a>
        </div>
    </section>

    <!-- ALERT ITEM PERSETUJUAN SPAREPART TAMBAHAN -->
    @if(isset($approvalItems) && count($approvalItems) > 0)
    <section class="bg-amber-500/10 border-2 border-amber-500 rounded-2xl p-md md:p-lg shadow-lg">
        <div class="flex items-center gap-md mb-xs">
            <span class="material-symbols-outlined text-amber-600 text-3xl animate-bounce">warning</span>
            <div>
                <h4 class="font-bold text-amber-900 text-base md:text-lg">Persetujuan Perbaikan Tambahan Diperlukan</h4>
                <p class="text-amber-700 text-xs md:text-sm">Mekanik menemukan beberapa komponen yang memerlukan pergantian/perbaikan tambahan pada motor Anda.</p>
            </div>
        </div>

        <div class="mt-md divide-y divide-amber-200/60 bg-white/80 backdrop-blur rounded-xl p-sm border border-amber-200">
            @foreach($approvalItems as $item)
            <div id="approval-item-{{ $item->id }}" class="py-md flex flex-col md:flex-row justify-between items-start md:items-center gap-sm px-sm">
                <div>
                    <h5 class="font-bold text-slate-900 text-sm md:text-base">{{ $item->item_name }}</h5>
                    <p class="text-xs text-slate-600 mt-0.5">{{ $item->notes ?? 'Direkomendasikan oleh mekanik' }}</p>
                    <span class="inline-block mt-1 font-extrabold text-amber-700 text-sm">Rp {{ number_format($item->estimated_cost, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <button onclick="respondApproval({{ $item->id }}, 'rejected')" class="flex-1 md:flex-initial px-md py-sm bg-rose-100 hover:bg-rose-200 text-rose-700 rounded-xl text-xs font-bold transition-colors">Tolak</button>
                    <button onclick="respondApproval({{ $item->id }}, 'approved')" class="flex-1 md:flex-initial px-md py-sm bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-colors shadow-md">Setujui Perbaikan</button>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-12 gap-lg">
        <!-- Left Column -->
        <div class="md:col-span-8 flex flex-col gap-lg">

            <!-- Real-Time Live Service Tracking Card -->
            <section>
                <div class="flex justify-between items-center mb-md">
                    <h4 class="font-headline-md text-headline-md-mobile md:text-headline-md text-on-surface">Status Live Servis Aktif</h4>
                    <span class="inline-flex items-center gap-1.5 bg-emerald-500/10 text-emerald-600 text-xs font-bold px-3 py-1 rounded-full border border-emerald-500/20">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span> Live Tracking
                    </span>
                </div>

                @if(isset($activeQueue))
                <div class="bg-surface rounded-2xl border-t-4 border-primary border-x border-b border-outline-variant shadow-md p-md md:p-lg relative overflow-hidden">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-md mb-md pb-md border-b border-outline-variant">
                        <div>
                            <div class="inline-flex items-center gap-xs bg-primary-container text-on-primary-container px-md py-1 rounded-full mb-xs">
                                <span class="material-symbols-outlined text-[16px]">two_wheeler</span>
                                <span class="font-bold text-xs">{{ $activeQueue->vehicle->brand ?? 'Motor' }} {{ $activeQueue->vehicle->model ?? '' }} ({{ $activeQueue->vehicle->plate_number ?? $activeQueue->vehicle->license_plate ?? '-' }})</span>
                            </div>
                            <h5 class="font-bold text-base text-on-surface mt-1">Keluhan: "{{ $activeQueue->complaint }}"</h5>
                        </div>
                        <button onclick="openChatModal({{ $activeQueue->id }})" class="w-full md:w-auto bg-primary hover:bg-primary/90 text-on-primary font-bold text-xs px-md py-sm rounded-xl transition-all flex items-center justify-center gap-sm shadow-md">
                            <span class="material-symbols-outlined text-[18px]">chat</span> Chat Mekanik
                        </button>
                    </div>

                    <!-- Progress Stepper -->
                    <div class="py-sm">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-bold text-primary">Progress Servis</span>
                            <span class="text-xs text-secondary">Estimasi Selesai: <strong>{{ $activeQueue->estimated_completion_at ? $activeQueue->estimated_completion_at->format('H:i') . ' WIB' : 'Dalam Proses' }}</strong></span>
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full bg-surface-container-high rounded-full h-3 mb-6 relative overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-600 to-emerald-500 h-3 rounded-full transition-all duration-500" 
                                style="width: {{ $activeQueue->status === 'waiting' ? '25%' : ($activeQueue->status === 'in_progress' ? '65%' : ($activeQueue->status === 'completed' ? '100%' : '10%')) }}"></div>
                        </div>

                        <div class="grid grid-cols-4 gap-1 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs text-white bg-blue-600 shadow-md">1</div>
                                <span class="text-[11px] font-semibold text-on-surface mt-1">Masuk Antrean</span>
                                <span class="text-[9px] text-secondary">{{ $activeQueue->entered_at ? $activeQueue->entered_at->format('H:i') : '-' }}</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs text-white {{ in_array($activeQueue->status, ['in_progress', 'completed']) ? 'bg-blue-600 shadow-md' : 'bg-slate-300' }}">2</div>
                                <span class="text-[11px] font-semibold text-on-surface mt-1">Inspeksi Motor</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs text-white {{ in_array($activeQueue->status, ['in_progress', 'completed']) ? 'bg-amber-500 animate-pulse shadow-md' : 'bg-slate-300' }}">3</div>
                                <span class="text-[11px] font-semibold text-on-surface mt-1">Pengerjaan</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs text-white {{ $activeQueue->status === 'completed' ? 'bg-emerald-600 shadow-md' : 'bg-slate-300' }}">4</div>
                                <span class="text-[11px] font-semibold text-on-surface mt-1">Selesai & Test</span>
                            </div>
                        </div>
                    </div>

                    @if($activeQueue->notes)
                    <div class="mt-md p-md bg-surface-container-low rounded-xl border border-outline-variant">
                        <p class="text-xs font-semibold text-secondary mb-1">Catatan Mekanik / Diagnosa:</p>
                        <p class="text-xs text-on-surface italic">"{{ $activeQueue->notes }}"</p>
                    </div>
                    @endif
                </div>
                @else
                <div class="bg-surface rounded-xl border border-outline-variant shadow-sm p-lg text-center">
                    <span class="material-symbols-outlined text-secondary text-5xl mb-sm">build_circle</span>
                    <h5 class="font-bold text-on-surface text-base">Belum Ada Servis Aktif Saat Ini</h5>
                    <p class="text-secondary text-xs mt-1 mb-md">Motor Anda belum berada di antrean bengkel. Silakan buat booking jadwal servis untuk perawatan motor Anda.</p>
                    <a href="{{ route('booking') }}" class="inline-flex items-center gap-2 bg-primary text-white font-bold text-xs px-md py-sm rounded-xl hover:bg-primary/90 transition-all shadow-md">
                        <span class="material-symbols-outlined text-[16px]">add</span> Buat Booking Servis Baru
                    </a>
                </div>
                @endif
            </section>
                <div class="bg-surface rounded-xl border-t-4 border-primary border-x border-b border-outline-variant shadow-sm p-md md:p-lg relative overflow-hidden">
                    <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-[120px] text-surface-container-highest opacity-30 rotate-12 pointer-events-none" style="font-variation-settings: 'FILL' 1;">calendar_today</span>
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-md relative z-10">
                        <div class="flex items-start gap-md">
                            <div class="bg-surface-container-low p-sm rounded-lg border border-outline-variant flex flex-col items-center justify-center min-w-[60px]">
                                <span class="font-label-md text-label-md text-error font-bold uppercase">Okt</span>
                                <span class="font-headline-md text-headline-md-mobile font-bold text-on-surface">15</span>
                            </div>
                            <div>
                                <div class="inline-flex items-center gap-xs bg-secondary-container text-on-secondary-container px-sm py-xs rounded-full mb-sm">
                                    <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">two_wheeler</span>
                                    <span class="font-label-md text-label-md text-[10px]">Honda Vario 150</span>
                                </div>
                                <h5 class="font-tabular-nums text-tabular-nums font-bold text-on-surface mb-xs">Servis Rutin Berkala (10.000 KM)</h5>
                                <div class="flex items-center gap-sm text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[16px]">schedule</span>
                                    <span class="font-body-md text-body-md text-[13px]">09:00 - 10:30 WIB</span>
                                </div>
                            </div>
                        </div>
                        <button class="w-full md:w-auto bg-primary hover:opacity-90 text-on-primary font-label-md text-label-md px-md py-sm rounded-lg transition-colors flex items-center justify-center gap-sm shadow-sm active:scale-95 duration-150">
                            Lihat Detail <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </section>

            <!-- Kendaraan Saya -->
            <section>
                <div class="flex justify-between items-center mb-md">
                    <h4 class="font-headline-md text-headline-md-mobile md:text-headline-md text-on-surface">Kendaraan Saya</h4>
                    <button onclick="document.getElementById('dash-add-vehicle-modal').classList.toggle('hidden')" class="px-3 py-1.5 bg-primary text-white font-bold text-xs rounded-xl shadow flex items-center gap-1 hover:bg-primary/90 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">add</span> Tambah Motor
                    </button>
                </div>

                <!-- Modal Tambah Motor di Dashboard -->
                <div id="dash-add-vehicle-modal" class="hidden mb-4 p-4 bg-slate-50 border border-slate-200 rounded-2xl shadow-sm">
                    <h4 class="font-bold text-xs text-slate-800 mb-2">Form Tambah Motor Baru</h4>
                    <form action="{{ route('vehicles.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 mb-1">Merek</label>
                                <input type="text" name="brand" required placeholder="Honda / Yamaha" class="w-full text-xs p-2 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 mb-1">Model</label>
                                <input type="text" name="model" required placeholder="Vario 160 / Beat" class="w-full text-xs p-2 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 mb-1">Plat Polisi</label>
                                <input type="text" name="plate_number" required placeholder="B 1234 XYZ" class="w-full text-xs p-2 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 mb-1">Tahun</label>
                                <input type="number" name="year" value="2023" class="w-full text-xs p-2 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                        <div class="flex justify-end gap-2 pt-1">
                            <button type="button" onclick="document.getElementById('dash-add-vehicle-modal').classList.add('hidden')" class="px-3 py-1.5 bg-slate-200 text-slate-700 text-xs font-bold rounded-lg">Batal</button>
                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow">Simpan Motor</button>
                        </div>
                    </form>
                </div>

                <div class="flex overflow-x-auto gap-md pb-sm no-scrollbar snap-x">
                    @forelse($vehicles as $v)
                    <div class="snap-start min-w-[260px] md:min-w-[300px] bg-surface rounded-2xl border border-outline-variant p-md flex flex-col justify-between gap-sm hover:border-primary/50 transition-colors shadow-sm relative group">
                        <div>
                            <div class="flex justify-between items-start">
                                <div class="w-10 h-10 bg-primary-container text-primary rounded-full flex items-center justify-center">
                                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">motorcycle</span>
                                </div>
                                <span class="bg-surface-container-high text-on-surface-variant font-label-md text-label-md px-2 py-1 rounded text-[10px] border border-outline-variant font-bold font-mono">{{ $v->license_plate ?? $v->plate_number }}</span>
                            </div>
                            <div class="mt-sm">
                                <h5 class="font-tabular-nums text-tabular-nums font-bold text-on-surface text-sm">{{ $v->brand }} {{ $v->model }}</h5>
                                <p class="font-body-md text-body-md text-[12px] text-on-surface-variant">Tahun: {{ $v->year ?? '-' }} • {{ $v->color ?? 'Hitam' }}</p>
                            </div>
                        </div>

                        <div class="mt-xs pt-sm border-t border-outline-variant flex items-center justify-between gap-2">
                            <button onclick="document.getElementById('dash-edit-vehicle-{{ $v->id }}').classList.toggle('hidden')" class="px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-[11px] rounded-lg transition-colors flex items-center gap-0.5">
                                <span class="material-symbols-outlined text-[14px]">edit</span> Edit
                            </button>
                            <form action="{{ route('vehicles.destroy', $v->id) }}" method="POST" onsubmit="return confirm('Hapus motor {{ $v->brand }} {{ $v->model }} ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-[11px] rounded-lg transition-colors flex items-center gap-0.5">
                                    <span class="material-symbols-outlined text-[14px]">delete</span> Hapus
                                </button>
                            </form>
                        </div>

                        <!-- Modal Edit Motor di Dashboard Card -->
                        <div id="dash-edit-vehicle-{{ $v->id }}" class="hidden mt-2 p-3 bg-blue-50/90 border border-blue-200 rounded-xl text-left z-10">
                            <h6 class="font-bold text-[11px] text-blue-900 mb-2">Edit Motor: {{ $v->brand }} {{ $v->model }}</h6>
                            <form action="{{ route('vehicles.update', $v->id) }}" method="POST" class="space-y-2">
                                @csrf
                                @method('PUT')
                                <div class="grid grid-cols-2 gap-1.5">
                                    <input type="text" name="brand" value="{{ $v->brand }}" required class="text-[11px] p-1.5 border border-slate-300 rounded">
                                    <input type="text" name="model" value="{{ $v->model }}" required class="text-[11px] p-1.5 border border-slate-300 rounded">
                                    <input type="text" name="plate_number" value="{{ $v->plate_number }}" required class="text-[11px] p-1.5 border border-slate-300 rounded">
                                    <input type="number" name="year" value="{{ $v->year }}" class="text-[11px] p-1.5 border border-slate-300 rounded">
                                </div>
                                <div class="flex justify-end gap-1.5 pt-1">
                                    <button type="button" onclick="document.getElementById('dash-edit-vehicle-{{ $v->id }}').classList.add('hidden')" class="px-2 py-1 bg-slate-200 text-slate-700 text-[10px] font-bold rounded">Batal</button>
                                    <button type="submit" class="px-2 py-1 bg-blue-600 text-white text-[10px] font-bold rounded">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 bg-surface rounded-xl border border-outline-variant text-xs text-secondary">Belum ada kendaraan terdaftar. Klik '+ Tambah Motor' di atas.</div>
                    @endforelse

                    <!-- Add New Card -->
                    <button onclick="document.getElementById('dash-add-vehicle-modal').classList.remove('hidden')" class="snap-start min-w-[120px] bg-surface-container-low rounded-xl border border-dashed border-outline-variant p-md flex flex-col items-center justify-center gap-sm hover:bg-surface-variant hover:border-primary transition-colors cursor-pointer group">
                        <div class="w-10 h-10 bg-surface text-outline rounded-full flex items-center justify-center group-hover:text-primary border border-outline-variant group-hover:border-primary transition-colors duration-150">
                            <span class="material-symbols-outlined">add</span>
                        </div>
                        <span class="font-label-md text-label-md text-on-surface-variant group-hover:text-primary">Tambah</span>
                    </button>
                </div>
            </section>
        </div>

        <!-- Right Column: Promo -->
        <div class="md:col-span-4 flex flex-col gap-lg">
            <section>
                <div class="flex justify-between items-center mb-md">
                    <h4 class="font-headline-md text-headline-md-mobile md:text-headline-md text-on-surface">Promo Spesial</h4>
                    <button class="text-primary font-label-md text-label-md hover:underline">Lihat Semua</button>
                </div>
                <div class="flex flex-col gap-md">
                    <!-- Promo 1 -->
                    <div class="bg-gradient-to-br from-primary-container to-primary-fixed-variant rounded-xl p-md text-on-primary-container relative overflow-hidden shadow-sm group cursor-pointer">
                        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 16px 16px;"></div>
                        <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-[100px] opacity-10 rotate-[-15deg] group-hover:scale-110 transition-transform pointer-events-none" style="font-variation-settings: 'FILL' 1;">oil_barrel</span>
                        <div class="relative z-10 flex flex-col justify-between">
                            <div class="bg-error text-on-error font-label-md text-label-md px-2 py-1 rounded text-[10px] w-fit mb-sm uppercase font-bold tracking-wider">Terbatas</div>
                            <div>
                                <h5 class="font-headline-md text-headline-md-mobile font-bold text-on-primary mb-xs">Ganti Oli 20% Off</h5>
                                <p class="font-body-md text-body-md text-on-primary-container opacity-90 text-[13px] mb-md">Diskon 20% untuk penggantian oli sintetik khusus minggu ini.</p>
                            </div>
                            <div class="flex justify-between items-end mt-sm">
                                <span class="font-label-md text-label-md text-[10px] opacity-75">S/D 20 Okt 2024</span>
                                <a href="#" class="bg-on-primary text-primary font-label-md text-label-md px-sm py-xs rounded hover:bg-surface-container-low transition-colors text-[11px]">Klaim</a>
                            </div>
                        </div>
                    </div>
                    <!-- Promo 2 -->
                    <div class="bg-surface rounded-xl p-md border border-outline-variant shadow-sm group cursor-pointer hover:border-secondary transition-colors relative overflow-hidden">
                        <span class="material-symbols-outlined absolute -right-4 top-4 text-[80px] text-surface-container-highest opacity-30 rotate-12 group-hover:scale-110 transition-transform pointer-events-none" style="font-variation-settings: 'FILL' 1;">savings</span>
                        <div class="relative z-10">
                            <div class="flex items-center gap-xs mb-sm">
                                <span class="material-symbols-outlined text-secondary text-[16px]">local_offer</span>
                                <span class="font-label-md text-label-md text-secondary uppercase text-[10px] tracking-wider">Paket Hemat</span>
                            </div>
                            <h5 class="font-tabular-nums text-tabular-nums font-bold text-on-surface mb-xs">Servis Rutin Hemat</h5>
                            <p class="font-body-md text-body-md text-on-surface-variant text-[13px] mb-sm">Paket komplit servis ringan + cek kelistrikan harga spesial.</p>
                            <a href="#" class="text-primary font-label-md text-label-md text-[12px] group-hover:underline flex items-center gap-xs">
                                Lihat Promo <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>

<!-- ===== BOTTOM NAV (Mobile Only) ===== -->
<nav class="fixed bottom-0 w-full z-50 pb-safe border-t border-outline-variant bg-surface shadow-lg flex justify-around items-center h-20 px-base md:hidden">
    <a aria-label="Beranda" href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center bg-secondary-container text-on-secondary-container rounded-full px-3 py-1 transition-all active:scale-90 duration-200">
        <span class="material-symbols-outlined mb-0.5 text-[20px]" style="font-variation-settings: 'FILL' 1;">home</span>
        <span class="font-label-md text-label-md text-[10px]">Beranda</span>
    </a>
    <a aria-label="Servis" href="{{ route('booking') }}" class="flex flex-col items-center justify-center text-on-surface-variant px-3 py-1 transition-all active:scale-90 duration-200 group">
        <span class="material-symbols-outlined mb-0.5 text-[20px] group-hover:text-primary" style="font-variation-settings: 'FILL' 0;">build_circle</span>
        <span class="font-label-md text-label-md text-[10px] group-hover:text-primary">Servis</span>
    </a>
    <a aria-label="Stok Part" href="{{ route('inventaris') }}" class="flex flex-col items-center justify-center text-on-surface-variant px-3 py-1 transition-all active:scale-90 duration-200 group">
        <span class="material-symbols-outlined mb-0.5 text-[20px] group-hover:text-primary" style="font-variation-settings: 'FILL' 0;">inventory_2</span>
        <span class="font-label-md text-label-md text-[10px] group-hover:text-primary">Stok Part</span>
    </a>
    <a aria-label="Riwayat" href="{{ route('riwayat') }}" class="flex flex-col items-center justify-center text-on-surface-variant px-3 py-1 transition-all active:scale-90 duration-200 group">
        <span class="material-symbols-outlined mb-0.5 text-[20px] group-hover:text-primary" style="font-variation-settings: 'FILL' 0;">history</span>
        <span class="font-label-md text-label-md text-[10px] group-hover:text-primary">Riwayat</span>
    </a>
    <a aria-label="Profil" href="{{ route('profil') }}" class="flex flex-col items-center justify-center text-on-surface-variant px-3 py-1 transition-all active:scale-90 duration-200 group">
        <span class="material-symbols-outlined mb-0.5 text-[20px] group-hover:text-primary" style="font-variation-settings: 'FILL' 0;">person</span>
        <span class="font-label-md text-label-md text-[10px] group-hover:text-primary">Profil</span>
    </a>
</nav>

<!-- ===== LIVE CHAT MODAL DRAWER ===== -->
<div id="chat-modal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex justify-end">
    <div class="w-full max-w-md bg-surface h-full flex flex-col shadow-2xl animate-slide-in">
        <!-- Header -->
        <div class="px-md py-sm bg-primary text-white flex items-center justify-between shadow-md">
            <div class="flex items-center gap-sm">
                <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center font-bold">
                    <span class="material-symbols-outlined text-[20px]">engineering</span>
                </div>
                <div>
                    <h3 id="chat-modal-title" class="font-bold text-sm">Chat Mekanik & Bengkel</h3>
                    <p class="text-[10px] text-blue-100 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Terhubung Live
                    </p>
                </div>
            </div>
            <button onclick="closeChatModal()" class="p-1 rounded-full hover:bg-white/20 text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Chat Body (Messages) -->
        <div id="chat-messages-container" class="flex-1 p-md overflow-y-auto space-y-3 bg-surface-container-low">
            <div class="text-center text-xs text-secondary my-4">Memuat percakapan...</div>
        </div>

        <!-- Chat Input Form -->
        <form id="chat-form" onsubmit="sendChatMessage(event)" class="p-sm bg-surface border-t border-outline-variant flex items-center gap-2">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <input type="file" id="chat-attachment-input" class="hidden" accept="image/*" onchange="previewAttachment(this)">
            <button type="button" onclick="document.getElementById('chat-attachment-input').click()" class="p-2 text-secondary hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[22px]">attach_file</span>
            </button>
            <input type="text" id="chat-input" placeholder="Tulis pesan ke mekanik..." required class="flex-1 text-xs px-md py-2.5 rounded-full border border-outline-variant bg-surface-container-lowest text-on-surface focus:outline-none focus:border-primary">
            <button type="submit" class="p-2 bg-primary text-white rounded-full hover:bg-primary/90 transition-colors shadow-md flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]">send</span>
            </button>
        </form>
    </div>
</div>

<script>
    let activeChatQueueId = null;
    let chatPollTimer = null;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    function toggleNotifDropdown() {
        const dd = document.getElementById('notif-dropdown');
        dd?.classList.toggle('hidden');
    }

    function markAllNotificationsRead() {
        fetch('/api/notifications/read-all', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken }
        }).then(() => {
            document.getElementById('notif-badge')?.classList.add('hidden');
            const list = document.getElementById('notif-list-container');
            if (list) {
                list.querySelectorAll('.bg-primary-container\\/10').forEach(el => el.classList.remove('bg-primary-container/10'));
            }
        });
    }

    function respondApproval(itemId, status) {
        if (!confirm(`Apakah Anda yakin ingin ${status === 'approved' ? 'MENYETUJUI' : 'MENOLAK'} perbaikan ini?`)) return;

        fetch(`/api/approvals/${itemId}/respond`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ status: status })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const el = document.getElementById(`approval-item-${itemId}`);
                if (el) {
                    el.innerHTML = `<div class="w-full text-center py-2 font-bold text-xs ${status === 'approved' ? 'text-emerald-600' : 'text-rose-600'}">✓ Keputusan (${status.toUpperCase()}) Berhasil Dikirim ke Mekanik</div>`;
                }
            } else {
                alert(data.error || 'Gagal memproses persetujuan');
            }
        });
    }

    function openChatModal(queueId) {
        activeChatQueueId = queueId;
        document.getElementById('chat-modal')?.classList.remove('hidden');
        loadChatMessages();
        if (chatPollTimer) clearInterval(chatPollTimer);
        chatPollTimer = setInterval(loadChatMessages, 4000);
    }

    function closeChatModal() {
        document.getElementById('chat-modal')?.classList.add('hidden');
        activeChatQueueId = null;
        if (chatPollTimer) clearInterval(chatPollTimer);
    }

    function loadChatMessages() {
        if (!activeChatQueueId) return;

        fetch(`/api/chats/${activeChatQueueId}`)
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById('chat-messages-container');
                if (!container) return;

                if (!data.chats || data.chats.length === 0) {
                    container.innerHTML = `<div class="text-center text-xs text-secondary my-8">Belum ada pesan. Mulai obrolan dengan mekanik!</div>`;
                    return;
                }

                let html = '';
                data.chats.forEach(chat => {
                    const isMe = chat.sender_type === 'user';
                    html += `
                        <div class="flex flex-col ${isMe ? 'items-end' : 'items-start'}">
                            <span class="text-[10px] text-secondary mb-0.5">${chat.sender?.name || 'Mekanik'}</span>
                            <div class="max-w-[80%] rounded-2xl px-md py-sm text-xs shadow-sm ${isMe ? 'bg-primary text-white rounded-br-none' : 'bg-surface border border-outline-variant text-on-surface rounded-bl-none'}">
                                ${chat.message}
                                ${chat.attachment_path ? `<div class="mt-2"><img src="${chat.attachment_path}" class="rounded-lg max-w-full h-auto"></div>` : ''}
                            </div>
                            <span class="text-[9px] text-slate-400 mt-0.5">${new Date(chat.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                        </div>
                    `;
                });
                container.innerHTML = html;
                container.scrollTop = container.scrollHeight;
            });
    }

    function sendChatMessage(e) {
        e.preventDefault();
        if (!activeChatQueueId) return;

        const input = document.getElementById('chat-input');
        const fileInput = document.getElementById('chat-attachment-input');
        const message = input.value.trim();
        if (!message) return;

        const formData = new FormData();
        formData.append('message', message);
        if (fileInput.files[0]) {
            formData.append('attachment', fileInput.files[0]);
        }

        fetch(`/api/chats/${activeChatQueueId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                input.value = '';
                fileInput.value = '';
                loadChatMessages();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const toggleButton = document.getElementById('mobile-sidebar-toggle');
        const sidebar = document.getElementById('mobile-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const closeButtons = document.querySelectorAll('[data-sidebar-close]');

        function openSidebar() {
            sidebar?.classList.remove('-translate-x-full');
            sidebar?.classList.add('translate-x-0');
            overlay?.classList.remove('hidden');
            toggleButton?.setAttribute('aria-expanded', 'true');
        }

        function closeSidebar() {
            sidebar?.classList.add('-translate-x-full');
            sidebar?.classList.remove('translate-x-0');
            overlay?.classList.hidden = true;
            toggleButton?.setAttribute('aria-expanded', 'false');
        }

        toggleButton?.addEventListener('click', openSidebar);
        overlay?.addEventListener('click', closeSidebar);
        closeButtons.forEach(btn => btn.addEventListener('click', closeSidebar));
    });
</script>
</body>
</html>