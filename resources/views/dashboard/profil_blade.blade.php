<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Profil Pengguna - Bengkel AR</title>
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
        <a class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out" href="{{ route('riwayat') }}">
            <span class="material-symbols-outlined">history</span>
            <span>Riwayat Servis</span>
        </a>
        <a class="flex items-center gap-md px-md py-sm rounded-lg bg-surface-container text-primary font-bold transition-colors duration-200 ease-in-out" href="{{ route('profil') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">person</span>
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
        <a class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out" href="{{ route('riwayat') }}">
            <span class="material-symbols-outlined">history</span>
            <span>Riwayat Servis</span>
        </a>
        <a class="flex items-center gap-md px-md py-sm rounded-lg text-primary font-bold border-r-4 border-primary bg-surface-container transition-colors duration-200 ease-in-out" href="{{ route('profil') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">person</span>
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
        <a class="flex items-center h-full text-on-surface-variant border-b-2 border-transparent font-label-md text-label-md transition-colors hover:text-primary" href="{{ route('riwayat') }}">Riwayat</a>
        <a class="flex items-center h-full text-primary border-b-2 border-primary font-bold font-label-md text-label-md" href="{{ route('profil') }}">Profil</a>
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
        <h1 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">Profil Pengguna</h1>
        <p class="font-body-md text-body-md text-on-surface-variant mt-1">Kelola informasi data diri dan daftar kendaraan motor Anda.</p>
    </header>

    @if(session('success'))
    <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 font-bold rounded-xl text-xs">
        ✓ {{ session('success') }}
    </div>
    @endif

    <section class="grid gap-4">
        <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-6 shadow-sm flex flex-col md:flex-row items-center gap-6">
            @if(auth()->user()->avatar)
                <img src="{{ asset(auth()->user()->avatar) }}" alt="Avatar" class="w-24 h-24 rounded-full object-cover border-2 border-primary shadow-md">
            @else
                <div class="w-24 h-24 rounded-full bg-primary-container text-on-primary flex items-center justify-center text-3xl font-bold uppercase shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
            @endif
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-2xl font-bold text-on-surface">{{ auth()->user()->name ?? 'Pelanggan' }}</h2>
                <p class="text-secondary mt-1">{{ auth()->user()->email }}</p>
                <div class="mt-4 flex flex-wrap justify-center md:justify-start gap-2">
                    <span class="bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">loyalty</span> 250 Poin Loyalitas
                    </span>
                    <span class="bg-surface-variant text-on-surface-variant px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">verified_user</span> Status: {{ ucfirst(auth()->user()->role ?? 'Pelanggan') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Form Edit Informasi Pribadi & Foto Profil -->
        <div class="rounded-xl border border-outline-variant bg-white p-6 shadow-sm mt-2">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="font-headline-md text-headline-md text-on-surface">Informasi Pribadi & Foto Profil</h3>
                    <p class="text-xs text-secondary mt-0.5">Perbarui data diri, foto avatar, dan alamat kontak Anda.</p>
                </div>
                <span class="material-symbols-outlined text-primary text-2xl">manage_accounts</span>
            </div>

            <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Foto Profil Upload -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Unggah Foto Profil Baru</label>
                    <div class="flex items-center gap-4 p-3 border border-slate-300 rounded-xl bg-slate-50">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset(auth()->user()->avatar) }}" class="w-12 h-12 rounded-full object-cover border border-slate-300 shadow-sm shrink-0">
                        @else
                            <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-sm">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                        @endif
                        <input type="file" name="avatar" accept="image/*" class="text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:bg-primary/90 cursor-pointer">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ auth()->user()->name }}" required class="w-full text-xs p-3 border border-slate-300 rounded-xl focus:border-primary focus:ring-primary outline-none font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Telepon</label>
                        <input type="text" name="phone" value="{{ auth()->user()->phone }}" placeholder="Contoh: 081234567890" class="w-full text-xs p-3 border border-slate-300 rounded-xl focus:border-primary focus:ring-primary outline-none font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Email</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}" required class="w-full text-xs p-3 border border-slate-300 rounded-xl focus:border-primary focus:ring-primary outline-none font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Password Baru <span class="text-secondary font-normal">(Kosongkan jika tidak diubah)</span></label>
                        <input type="password" name="password" placeholder="••••••••" class="w-full text-xs p-3 border border-slate-300 rounded-xl focus:border-primary focus:ring-primary outline-none font-medium">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Domisili</label>
                        <textarea name="address" rows="2" placeholder="Masukkan alamat lengkap domisili Anda..." class="w-full text-xs p-3 border border-slate-300 rounded-xl focus:border-primary focus:ring-primary outline-none font-medium resize-none">{{ auth()->user()->address }}</textarea>
                    </div>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-5 py-2.5 bg-primary hover:bg-primary/90 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">save</span> Simpan Perubahan Profil & Foto
                    </button>
                </div>
            </form>
        </div>

        <!-- Daftar & Form Tambah Kendaraan -->
        <div class="rounded-xl border border-outline-variant bg-white p-6 shadow-sm mt-2">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-headline-md text-headline-md text-on-surface">Daftar Kendaraan Saya</h3>
                <button onclick="document.getElementById('add-vehicle-modal').classList.toggle('hidden')" class="px-4 py-2 bg-primary text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-1 hover:bg-primary/90">
                    <span class="material-symbols-outlined text-[16px]">add</span> Tambah Kendaraan
                </button>
            </div>

            <div id="add-vehicle-modal" class="hidden mb-6 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                <h4 class="font-bold text-sm text-slate-800 mb-3">Form Tambah Motor Baru</h4>
                <form action="{{ route('vehicles.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Merek Motor</label>
                            <input type="text" name="brand" required placeholder="Contoh: Honda / Yamaha / Suzuki" class="w-full text-xs p-2.5 border border-slate-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Model / Tipe</label>
                            <input type="text" name="model" required placeholder="Contoh: Vario 160 / NMAX 155" class="w-full text-xs p-2.5 border border-slate-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Plat Nomor Kendaraan</label>
                            <input type="text" name="plate_number" required placeholder="Contoh: B 4821 KLO" class="w-full text-xs p-2.5 border border-slate-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Tahun Kendaraan</label>
                            <input type="number" name="year" value="2022" class="w-full text-xs p-2.5 border border-slate-300 rounded-lg">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-2">
                        <button type="button" onclick="document.getElementById('add-vehicle-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-200 text-slate-700 text-xs font-bold rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-md">Simpan Motor</button>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($vehicles ?? [] as $v)
                <div class="p-4 border border-outline-variant rounded-2xl bg-surface-container-lowest flex flex-col justify-between shadow-sm hover:border-primary/50 transition-all">
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary-container/30 text-primary flex items-center justify-center font-bold">
                                    <span class="material-symbols-outlined">two_wheeler</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-on-surface">{{ $v->brand }} {{ $v->model }}</h4>
                                    <p class="text-xs text-secondary font-mono font-bold">{{ $v->plate_number }} • {{ $v->year ?? '-' }}</p>
                                </div>
                            </div>
                            <span class="text-[10px] bg-emerald-100 text-emerald-800 font-extrabold px-2.5 py-1 rounded-full uppercase tracking-wider">Terdaftar</span>
                        </div>
                        <p class="text-xs text-slate-500 italic mt-1 bg-slate-50 p-2 rounded-lg border border-slate-100">"{{ $v->description ?? 'Motor Utama Harian' }}"</p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-outline-variant/40 flex items-center justify-between gap-2">
                        <button onclick="document.getElementById('edit-vehicle-modal-{{ $v->id }}').classList.toggle('hidden')" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold rounded-lg transition-colors flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">edit</span> Edit Motor
                        </button>

                        <form action="{{ route('vehicles.destroy', $v->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus motor {{ $v->brand }} {{ $v->model }} ({{ $v->plate_number }}) ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold rounded-lg transition-colors flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">delete</span> Hapus
                            </button>
                        </form>
                    </div>

                    <!-- Modal Edit Motor -->
                    <div id="edit-vehicle-modal-{{ $v->id }}" class="hidden mt-3 p-4 bg-blue-50/60 border border-blue-200 rounded-xl text-left">
                        <h5 class="font-bold text-xs text-blue-900 mb-2">Edit Data Motor: {{ $v->brand }} {{ $v->model }}</h5>
                        <form action="{{ route('vehicles.update', $v->id) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600">Merek</label>
                                    <input type="text" name="brand" value="{{ $v->brand }}" required class="w-full text-xs p-2 border border-slate-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600">Model</label>
                                    <input type="text" name="model" value="{{ $v->model }}" required class="w-full text-xs p-2 border border-slate-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600">Plat Polisi</label>
                                    <input type="text" name="plate_number" value="{{ $v->plate_number }}" required class="w-full text-xs p-2 border border-slate-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600">Tahun</label>
                                    <input type="number" name="year" value="{{ $v->year }}" class="w-full text-xs p-2 border border-slate-300 rounded-lg">
                                </div>
                            </div>
                            <div class="flex justify-end gap-2 pt-1">
                                <button type="button" onclick="document.getElementById('edit-vehicle-modal-{{ $v->id }}').classList.add('hidden')" class="px-3 py-1.5 bg-slate-200 text-slate-700 text-xs font-bold rounded-lg">Batal</button>
                                <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                <p class="col-span-2 text-center text-xs text-slate-400 py-6 italic">Belum ada kendaraan terdaftar. Silakan klik tombol Tambah Kendaraan di atas.</p>
                @endforelse
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
    <a aria-label="Riwayat" class="flex flex-col items-center justify-center text-on-surface-variant px-4 py-1 hover:bg-surface-container-high transition-all active:scale-90 duration-200 group" href="{{ route('riwayat') }}">
        <span class="material-symbols-outlined mb-1 group-hover:text-primary" style="font-variation-settings: 'FILL' 0;">history</span>
        <span class="font-label-md text-label-md text-[10px] group-hover:text-primary">Riwayat</span>
    </a>
    <a aria-label="Profil" class="flex flex-col items-center justify-center bg-secondary-container text-on-secondary-container rounded-full px-4 py-1 transition-all active:scale-90 duration-200" href="{{ route('profil') }}">
        <span class="material-symbols-outlined mb-1" style="font-variation-settings: 'FILL' 1;">person</span>
        <span class="font-label-md text-label-md text-[10px]">Profil</span>
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