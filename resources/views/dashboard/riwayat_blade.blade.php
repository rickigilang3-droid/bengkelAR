<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Riwayat Servis - Bengkel AR</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
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
                    borderRadius: {
                        DEFAULT: "0.125rem",
                        lg: "0.25rem",
                        xl: "0.5rem",
                        full: "0.75rem"
                    },
                    spacing: {
                        "margin-mobile": "16px",
                        sm: "8px",
                        base: "4px",
                        md: "16px",
                        gutter: "24px",
                        xl: "32px",
                        "margin-desktop": "32px",
                        "max-width": "1440px",
                        lg: "24px",
                        xs: "4px"
                    },
                    fontFamily: {
                        "body-lg": ["Inter"],
                        "label-md": ["Inter"],
                        "tabular-nums": ["Inter"],
                        "headline-lg": ["Inter"],
                        "headline-md": ["Inter"],
                        "headline-lg-mobile": ["Inter"],
                        "body-md": ["Inter"]
                    },
                    fontSize: {
                        "body-lg": ["16px", { lineHeight: "24px", fontWeight: "400" }],
                        "label-md": ["12px", { lineHeight: "16px", letterSpacing: "0.05em", fontWeight: "600" }],
                        "tabular-nums": ["14px", { lineHeight: "20px", fontWeight: "500" }],
                        "headline-lg": ["30px", { lineHeight: "38px", letterSpacing: "-0.02em", fontWeight: "700" }],
                        "headline-md": ["20px", { lineHeight: "28px", fontWeight: "600" }],
                        "headline-lg-mobile": ["24px", { lineHeight: "32px", letterSpacing: "-0.02em", fontWeight: "700" }],
                        "body-md": ["14px", { lineHeight: "20px", fontWeight: "400" }]
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
<header class="fixed top-0 w-full z-50 bg-surface border-b border-outline-variant hover:bg-surface-container-low transition-colors active:scale-95 duration-150 ease-in-out md:hidden">
    <div class="flex justify-between items-center h-16 px-margin-mobile w-full">
        <div class="flex items-center gap-2">
            <button id="mobile-sidebar-toggle" aria-label="Menu" aria-controls="mobile-sidebar" aria-expanded="false" class="flex items-center justify-center p-sm rounded-full text-primary hover:bg-surface-variant transition-colors">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">menu</span>
            </button>
            <h1 class="font-headline-md text-headline-md-mobile font-bold text-primary">Bengkel AR</h1>
        </div>
        <div class="flex items-center gap-2">
            <button aria-label="Notifications" class="flex items-center justify-center p-sm rounded-full text-primary hover:bg-surface-variant transition-colors relative">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">notifications</span>
                <span class="absolute top-1 right-1 w-2 h-2 bg-error rounded-full border border-surface"></span>
            </button>
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" aria-label="Logout" class="flex items-center justify-center p-sm rounded-full text-error hover:bg-error-container transition-colors">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">logout</span>
                </button>
            </form>
        </div>
    </div>
</header>

<div id="sidebar-overlay" class="hidden fixed inset-0 bg-black/30 z-30 md:hidden"></div>
<aside id="mobile-sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-surface-container-lowest border-r border-outline-variant py-md px-sm transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden">
    <div class="flex items-center justify-between mb-md">
        <div>
            <h1 class="font-headline-md text-headline-md font-bold text-primary">Bengkel AR</h1>
            <p class="font-label-md text-label-md text-secondary mt-xs">User Menu</p>
        </div>
        <button type="button" aria-label="Close menu" data-sidebar-close class="flex items-center justify-center p-sm rounded-full text-primary hover:bg-surface-variant transition-colors">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">close</span>
        </button>
    </div>
    <div class="flex flex-col gap-sm">
        <a class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out" href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">dashboard</span>
            <span>Dashboard</span>
        </a>
        <a class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out" href="{{ route('booking') }}">
            <span class="material-symbols-outlined">build_circle</span>
            <span>Booking Servis</span>
        </a>
        <a class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out" href="{{ route('inventaris') }}">
            <span class="material-symbols-outlined">inventory_2</span>
            <span>Cek Stok Sparepart</span>
        </a>
        <a class="flex items-center gap-md px-md py-sm rounded-lg bg-surface-container text-primary font-bold transition-colors duration-200 ease-in-out" href="{{ route('riwayat') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">history</span>
            <span>Riwayat Servis</span>
        </a>
        <a class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out" href="{{ route('profil') }}">
            <span class="material-symbols-outlined">person</span>
            <span>Profil</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" class="mt-auto">
            @csrf
            <button type="submit" class="flex w-full items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out">
                <span class="material-symbols-outlined">logout</span>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>

<aside class="hidden md:flex fixed left-0 top-16 h-[calc(100vh-4rem)] w-[240px] flex-col bg-surface-container-lowest border-r border-outline-variant py-md z-20">
    <div class="px-lg mb-xl">
        <h1 class="font-headline-md text-headline-md font-bold text-primary">Bengkel AR</h1>
        <p class="font-label-md text-label-md text-secondary mt-xs">User Menu</p>
    </div>
    <div class="flex flex-col gap-sm px-sm h-full">
        <a class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out" href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">dashboard</span>
            <span>Dashboard</span>
        </a>
        <a class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out" href="{{ route('booking') }}">
            <span class="material-symbols-outlined">build_circle</span>
            <span>Booking Servis</span>
        </a>
        <a class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out" href="{{ route('inventaris') }}">
            <span class="material-symbols-outlined">inventory_2</span>
            <span>Cek Stok Sparepart</span>
        </a>
        <a class="flex items-center gap-md px-md py-sm rounded-lg text-primary font-bold border-r-4 border-primary bg-surface-container transition-colors duration-200 ease-in-out" href="{{ route('riwayat') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">history</span>
            <span>Riwayat Servis</span>
        </a>
        <a class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out" href="{{ route('profil') }}">
            <span class="material-symbols-outlined">person</span>
            <span>Profil</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" class="mt-auto px-sm">
            @csrf
            <button type="submit" class="flex w-full items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out">
                <span class="material-symbols-outlined">logout</span>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>

<header class="hidden md:flex fixed top-0 w-full z-50 bg-surface border-b border-outline-variant h-16 px-margin-desktop items-center justify-between shadow-sm">
    <div class="flex items-center gap-md">
        <span class="material-symbols-outlined text-primary text-[28px]" style="font-variation-settings: 'FILL' 0;">motorcycle</span>
        <h1 class="font-headline-md text-headline-md font-bold text-primary">Bengkel AR</h1>
    </div>
    <nav class="flex gap-lg h-full">
        <a class="flex items-center h-full text-on-surface-variant border-b-2 border-transparent font-label-md text-label-md transition-colors hover:text-primary" href="{{ route('dashboard') }}">Beranda</a>
        <a class="flex items-center h-full text-on-surface-variant border-b-2 border-transparent font-label-md text-label-md transition-colors hover:text-primary" href="{{ route('booking') }}">Booking Servis</a>
        <a class="flex items-center h-full text-on-surface-variant border-b-2 border-transparent font-label-md text-label-md transition-colors hover:text-primary" href="{{ route('inventaris') }}">Cek Stok Sparepart</a>
        <a class="flex items-center h-full text-primary border-b-2 border-primary font-bold font-label-md text-label-md" href="{{ route('riwayat') }}">Riwayat</a>
        <a class="flex items-center h-full text-on-surface-variant border-b-2 border-transparent font-label-md text-label-md transition-colors hover:text-primary" href="{{ route('profil') }}">Profil</a>
    </nav>
    <div class="flex items-center gap-md">
        <button aria-label="Notifications" class="flex items-center justify-center p-sm rounded-full text-on-surface-variant hover:bg-surface-variant transition-colors relative">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">notifications</span>
        </button>
        <div class="flex items-center gap-sm px-sm py-xs bg-surface-container-low rounded-full border border-outline-variant cursor-pointer hover:bg-surface-variant transition-colors">
            <div class="w-8 h-8 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold font-label-md text-label-md">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
            <span class="font-label-md text-label-md mr-sm">{{ auth()->user()->name ?? 'Pelanggan' }}</span>
        </div>
    </div>
</header>

<main class="flex-grow pt-20 md:pt-24 pb-28 md:pb-8 px-margin-mobile md:px-margin-desktop max-w-max-width mx-auto w-full flex flex-col gap-6 md:ml-[240px] md:w-[calc(100%-240px)]">
    <header>
        <h1 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">Riwayat Servis</h1>
        <p class="font-body-md text-body-md text-on-surface-variant mt-1">Lihat riwayat servis selesai dan rangkuman biaya pemeliharaan motor Anda.</p>
    </header>

    <section class="grid gap-4">
        <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-6 shadow-sm">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <p class="font-label-md text-label-md text-secondary uppercase tracking-wider">Ringkasan Riwayat</p>
                    <h2 class="mt-2 font-headline-md text-headline-md text-on-surface">{{ isset($histories) ? count($histories) : 0 }} Riwayat Servis</h2>
                </div>
            </div>
            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-outline-variant text-left text-sm text-on-surface">
                    <thead class="bg-surface text-on-surface-variant">
                        <tr>
                            <th class="px-4 py-3">Tanggal Selesai</th>
                            <th class="px-4 py-3">Kendaraan</th>
                            <th class="px-4 py-3">Layanan / Deskripsi</th>
                            <th class="px-4 py-3">Biaya Servis</th>
                            <th class="px-4 py-3">Status Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($histories ?? [] as $h)
                        <tr class="bg-white hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-4 font-semibold text-xs">{{ $h->completed_at ? $h->completed_at->format('d M Y, H:i') : '-' }}</td>
                            <td class="px-4 py-4 font-bold text-xs">{{ $h->vehicle->brand ?? 'Motor' }} {{ $h->vehicle->model ?? '' }} ({{ $h->vehicle->plate_number ?? '-' }})</td>
                            <td class="px-4 py-4 text-xs">
                                <span class="font-bold block text-primary">{{ $h->service_type }}</span>
                                <span class="text-slate-600 italic text-[11px]">{{ $h->description }}</span>
                            </td>
                            <td class="px-4 py-4 font-extrabold text-xs text-emerald-700">Rp {{ number_format($h->total_cost, 0, ',', '.') }}</td>
                            <td class="px-4 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold {{ $h->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ strtoupper($h->payment_status ?? 'PENDING') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-400 italic text-xs">Belum ada riwayat servis yang selesai.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="rounded-xl border border-outline-variant bg-white p-6 shadow-sm">
            <h3 class="font-headline-md text-headline-md text-on-surface mb-4">Rangkuman Biaya Pemeliharaan</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-2xl bg-surface-container-lowest p-4">
                    <p class="font-label-md text-label-md text-on-surface-variant">Total Biaya Dikeluarkan</p>
                    <p class="mt-2 font-headline-md text-headline-md text-emerald-700 font-extrabold">Rp {{ number_format($totalSpent ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl bg-surface-container-lowest p-4">
                    <p class="font-label-md text-label-md text-on-surface-variant">Total Servis Selesai</p>
                    <p class="mt-2 font-headline-md text-headline-md text-on-surface font-bold">{{ $completedCount ?? 0 }} Servis</p>
                </div>
                <div class="rounded-2xl bg-surface-container-lowest p-4">
                    <p class="font-label-md text-label-md text-on-surface-variant">Status Garansi Servis</p>
                    <p class="mt-2 font-bold text-xs text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full w-fit">✓ Garansi Aktif (7 Hari)</p>
                </div>
            </div>
        </div>
    </section>
</main>

<nav class="fixed bottom-0 w-full z-50 pb-safe border-t border-outline-variant bg-surface shadow-lg flex justify-around items-center h-20 px-base md:hidden">
    <a aria-label="Beranda" class="flex flex-col items-center justify-center text-on-surface-variant px-4 py-1 hover:bg-surface-container-high transition-all active:scale-90 duration-200 group" href="{{ route('dashboard') }}">
        <span class="material-symbols-outlined mb-1 group-hover:text-primary" style="font-variation-settings: 'FILL' 0;">home</span>
        <span class="font-label-md text-label-md text-[10px] group-hover:text-primary">Beranda</span>
    </a>
    <a aria-label="Servis" class="flex flex-col items-center justify-center text-on-surface-variant px-4 py-1 hover:bg-surface-container-high transition-all active:scale-90 duration-200 group" href="{{ route('booking') }}">
        <span class="material-symbols-outlined mb-1 group-hover:text-primary" style="font-variation-settings: 'FILL' 0;">build_circle</span>
        <span class="font-label-md text-label-md text-[10px] group-hover:text-primary">Servis</span>
    </a>
    <a aria-label="Riwayat" class="flex flex-col items-center justify-center bg-secondary-container text-on-secondary-container rounded-full px-4 py-1 hover:bg-surface-container-high transition-all active:scale-90 duration-200" href="{{ route('riwayat') }}">
        <span class="material-symbols-outlined mb-1" style="font-variation-settings: 'FILL' 1;">history</span>
        <span class="font-label-md text-label-md text-[10px]">Riwayat</span>
    </a>
    <a aria-label="Profil" class="flex flex-col items-center justify-center text-on-surface-variant px-4 py-1 hover:bg-surface-container-high transition-all active:scale-90 duration-200 group" href="{{ route('profil') }}">
        <span class="material-symbols-outlined mb-1 group-hover:text-primary" style="font-variation-settings: 'FILL' 0;">person</span>
        <span class="font-label-md text-label-md text-[10px] group-hover:text-primary">Profil</span>
    </a>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButton = document.getElementById('mobile-sidebar-toggle');
        const sidebar = document.getElementById('mobile-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const closeButtons = document.querySelectorAll('[data-sidebar-close]');

        function openSidebar() {
            if (sidebar && overlay) {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                overlay.classList.remove('hidden');
                toggleButton?.setAttribute('aria-expanded', 'true');
            }
        }

        function closeSidebar() {
            if (sidebar && overlay) {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
                overlay.classList.add('hidden');
                toggleButton?.setAttribute('aria-expanded', 'false');
            }
        }

        toggleButton?.addEventListener('click', openSidebar);
        overlay?.addEventListener('click', closeSidebar);
        closeButtons.forEach((button) => button.addEventListener('click', closeSidebar));
    });
</script>
</body>
</html>
