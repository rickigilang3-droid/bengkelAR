<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ $title }} - Admin Bengkel AR</title>
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
                        on-surface: '#191c1e',
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
    @php $active = $active ?? ''; @endphp

    <nav class="bg-surface-container-lowest fixed left-0 top-0 h-screen w-[240px] border-r border-outline-variant flex flex-col py-md z-20">
        <div class="px-lg mb-xl">
            <h1 class="font-headline-md text-headline-md font-bold text-primary">Bengkel AR</h1>
            <p class="font-label-md text-label-md text-secondary mt-xs">Admin Terminal</p>
        </div>
        <div class="flex flex-col gap-sm px-sm">
            <a class="flex items-center gap-md px-md py-sm rounded-lg {{ $active === 'dashboard' ? 'text-primary font-bold border-r-4 border-primary bg-surface-container opacity-80' : 'text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out' }}" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">dashboard</span>
                <span>Dashboard</span>
            </a>
            <a class="flex items-center gap-md px-md py-sm rounded-lg {{ $active === 'queue' ? 'text-primary font-bold border-r-4 border-primary bg-surface-container opacity-80' : 'text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out' }}" href="{{ route('admin.queue.index') }}">
                <span class="material-symbols-outlined">queue</span>
                <span>Service Queue</span>
            </a>
            <a class="flex items-center gap-md px-md py-sm rounded-lg {{ $active === 'customers' ? 'text-primary font-bold border-r-4 border-primary bg-surface-container opacity-80' : 'text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out' }}" href="{{ route('admin.customers.index') }}">
                <span class="material-symbols-outlined">group</span>
                <span>Customers</span>
            </a>
            <a class="flex items-center gap-md px-md py-sm rounded-lg {{ $active === 'history' ? 'text-primary font-bold border-r-4 border-primary bg-surface-container opacity-80' : 'text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out' }}" href="{{ route('admin.history.index') }}">
                <span class="material-symbols-outlined">history</span>
                <span>Service History</span>
            </a>
            <a class="flex items-center gap-md px-md py-sm rounded-lg {{ $active === 'reminders' ? 'text-primary font-bold border-r-4 border-primary bg-surface-container opacity-80' : 'text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out' }}" href="{{ route('admin.reminders.index') }}">
                <span class="material-symbols-outlined">notifications_active</span>
                <span>Reminders</span>
            </a>
            <a class="flex items-center gap-md px-md py-sm rounded-lg {{ $active === 'inventory' ? 'text-primary font-bold border-r-4 border-primary bg-surface-container opacity-80' : 'text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out' }}" href="{{ route('inventaris') }}">
                <span class="material-symbols-outlined">inventory_2</span>
                <span>Inventory</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-auto px-sm">
                @csrf
                <button type="submit" class="flex w-full items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out">
                    <span class="material-symbols-outlined">logout</span>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <header class="bg-surface/80 backdrop-blur-md fixed top-0 right-0 left-[240px] h-16 border-b border-outline-variant flex justify-between items-center px-lg max-w-max-width mx-auto z-10">
        <div class="flex items-center gap-md">
            <span class="material-symbols-outlined text-primary text-[28px]" style="font-variation-settings: 'FILL' 0;">workspace_premium</span>
            <div>
                <h1 class="font-headline-md text-headline-md font-bold text-primary">{{ $title }}</h1>
                <p class="font-body-md text-body-md text-secondary">{{ $description }}</p>
            </div>
        </div>
        <a href="{{ route('dashboard') }}" class="rounded-2xl bg-primary px-4 py-3 text-sm font-semibold text-white hover:bg-primary-container">Kembali ke Dashboard</a>
    </header>

    <main class="ml-[240px] pt-[88px] px-margin-desktop pb-xl max-w-max-width mx-auto">
        <div class="grid gap-gutter">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg shadow-sm">
                <h2 class="font-headline-md text-headline-md text-on-surface mb-md">Ringkasan {{ $title }}</h2>
                <p class="font-body-md text-body-md text-on-surface-variant">Halaman ini menjelaskan ringkasan dan tindakan lanjutan untuk fitur admin {{ strtolower($title) }}.</p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
                <div class="bg-white rounded-3xl border border-outline-variant p-lg shadow-sm">
                    <p class="font-label-md text-label-md text-secondary uppercase tracking-wider mb-3">Detail</p>
                    <p class="font-body-md text-body-md text-on-surface-variant">Modul ini bisa dikembangkan menjadi fitur lengkap sesuai kebutuhan manajemen bengkel.</p>
                </div>
                <div class="bg-white rounded-3xl border border-outline-variant p-lg shadow-sm">
                    <p class="font-label-md text-label-md text-secondary uppercase tracking-wider mb-3">Aksi cepat</p>
                    <ul class="space-y-2 text-body-md text-on-surface-variant">
                        <li>• Tinjau antrean</li>
                        <li>• Lihat pelanggan</li>
                        <li>• Cek histori servis</li>
                    </ul>
                </div>
                <div class="bg-white rounded-3xl border border-outline-variant p-lg shadow-sm">
                    <p class="font-label-md text-label-md text-secondary uppercase tracking-wider mb-3">Status</p>
                    <p class="font-body-md text-body-md text-on-surface-variant">Fitur ini diaktifkan untuk admin. Semua pengguna terautentikasi akan diarahkan ke halaman ini setelah login jika berperan sebagai admin.</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
