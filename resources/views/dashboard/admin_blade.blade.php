<!DOCTYPE html>

<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Dashboard Admin - Bengkel AR</title>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "secondary": "#505f76",
                        "surface-bright": "#f7f9fb",
                        "tertiary-container": "#00563a",
                        "inverse-surface": "#2d3133",
                        "on-error-container": "#93000a",
                        "on-primary-fixed-variant": "#173bab",
                        "on-primary": "#ffffff",
                        "on-secondary-container": "#54647a",
                        "primary-container": "#1e40af",
                        "error": "#ba1a1a",
                        "inverse-primary": "#b8c4ff",
                        "outline": "#757684",
                        "on-surface-variant": "#444653",
                        "surface-tint": "#3755c3",
                        "primary-fixed": "#dde1ff",
                        "surface-container-highest": "#e0e3e5",
                        "on-background": "#191c1e",
                        "on-surface": "#191c1e",
                        "outline-variant": "#c4c5d5",
                        "surface-variant": "#e0e3e5",
                        "secondary-fixed": "#d3e4fe",
                        "background": "#f7f9fb",
                        "tertiary-fixed": "#6ffbbe",
                        "on-error": "#ffffff",
                        "inverse-on-surface": "#eff1f3",
                        "on-secondary-fixed-variant": "#38485d",
                        "on-tertiary-fixed-variant": "#005236",
                        "on-primary-container": "#a8b8ff",
                        "surface-container-high": "#e6e8ea",
                        "surface-container-lowest": "#ffffff",
                        "tertiary": "#003d27",
                        "on-tertiary-container": "#3fd298",
                        "error-container": "#ffdad6",
                        "surface-container-low": "#f2f4f6",
                        "on-secondary": "#ffffff",
                        "primary": "#00288e",
                        "surface-container": "#eceef0",
                        "secondary-fixed-dim": "#b7c8e1",
                        "secondary-container": "#d0e1fb",
                        "primary-fixed-dim": "#b8c4ff",
                        "tertiary-fixed-dim": "#4edea3",
                        "on-tertiary": "#ffffff",
                        "on-secondary-fixed": "#0b1c30",
                        "surface": "#f7f9fb",
                        "on-tertiary-fixed": "#002113",
                        "surface-dim": "#d8dadc",
                        "on-primary-fixed": "#001453"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "xs": "4px",
                        "md": "16px",
                        "lg": "24px",
                        "xl": "32px",
                        "margin-desktop": "32px",
                        "margin-mobile": "16px",
                        "sm": "8px",
                        "base": "4px",
                        "max-width": "1440px",
                        "gutter": "24px"
                    },
                    "fontFamily": {
                        "body-md": ["Inter"],
                        "label-md": ["Inter"],
                        "headline-lg": ["Inter"],
                        "body-lg": ["Inter"],
                        "headline-md": ["Inter"],
                        "headline-lg-mobile": ["Inter"],
                        "tabular-nums": ["Inter"]
                    },
                    "fontSize": {
                        "body-md": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                        "label-md": ["12px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600" }],
                        "headline-lg": ["30px", { "lineHeight": "38px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "body-lg": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "headline-md": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                        "headline-lg-mobile": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "tabular-nums": ["14px", { "lineHeight": "20px", "fontWeight": "500" }]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background text-on-surface font-body-md antialiased min-h-screen">
<!-- SideNavBar -->
<nav class="bg-surface-container-lowest fixed left-0 top-0 h-screen w-[240px] border-r border-outline-variant flex flex-col h-full py-md z-20">
<div class="px-lg mb-xl">
<h1 class="font-headline-md text-headline-md font-bold text-primary">Bengkel AR</h1>
<p class="font-label-md text-label-md text-secondary mt-xs">Admin Terminal</p>
</div>
<div class="flex flex-col gap-sm px-sm">
<!-- Active Tab -->
<a class="flex items-center gap-md px-md py-sm rounded-lg text-primary font-bold border-r-4 border-primary bg-surface-container transition-colors duration-200 ease-in-out opacity-80" href="{{ route('dashboard') }}">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">dashboard</span>
<span>Dashboard</span>
</a>
<!-- Inactive Tabs -->
<a class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out" href="{{ route('admin.queue.index') }}">
<span class="material-symbols-outlined">queue</span>
<span>Service Queue</span>
</a>
<a class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out" href="{{ route('admin.customers.index') }}">
<span class="material-symbols-outlined">group</span>
<span>Customers</span>
</a>
<a class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out" href="{{ route('admin.history.index') }}">
<span class="material-symbols-outlined">history</span>
<span>Service History</span>
</a>
<a class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out" href="{{ route('admin.reminders.index') }}">
<span class="material-symbols-outlined">notifications_active</span>
<span>Reminders</span>
</a>
<a class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out" href="{{ route('inventaris') }}">
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
<!-- TopNavBar -->
<header class="bg-surface/80 backdrop-blur-md fixed top-0 right-0 left-[240px] h-16 border-b border-outline-variant flex justify-between items-center px-lg w-full max-w-max-width mx-auto z-10">
<!-- Search Bar (on_left) -->
<div class="flex items-center bg-surface-container-lowest border border-outline-variant rounded-full px-md py-sm w-96 focus-within:ring-2 focus-within:ring-primary transition-shadow">
<span class="material-symbols-outlined text-secondary mr-sm">search</span>
<input class="bg-transparent border-none outline-none w-full font-body-md text-body-md text-on-surface placeholder-secondary focus:ring-0 p-0" placeholder="Cari pelanggan, kendaraan, atau ID servis..." type="text"/>
</div>
<div class="flex items-center gap-lg">
<span class="font-label-md text-label-md text-secondary hidden lg:block">Sistem Manajemen Bengkel</span>
<div class="flex items-center gap-sm">
<button class="p-sm text-on-surface-variant hover:text-primary transition-colors duration-200 ease-in-out rounded-full hover:bg-surface-container">
<span class="material-symbols-outlined">settings</span>
</button>
<button class="p-sm text-on-surface-variant hover:text-primary transition-colors duration-200 ease-in-out rounded-full hover:bg-surface-container">
<span class="material-symbols-outlined">help_outline</span>
</button>
<button class="p-sm text-on-surface-variant hover:text-primary transition-colors duration-200 ease-in-out rounded-full hover:bg-surface-container ml-sm">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">account_circle</span>
</button>
</div>
</div>
</header>
<!-- Main Content Area -->
<main class="ml-[240px] pt-[88px] px-margin-desktop pb-xl max-w-max-width">
<!-- Page Header -->
<div class="mb-lg flex justify-between items-end">
<div>
<h2 class="font-headline-lg text-headline-lg text-on-surface">Dashboard Overview</h2>
<p class="font-body-md text-body-md text-secondary mt-xs">Ringkasan operasional dan performa bengkel hari ini.</p>
</div>
<button class="bg-primary text-on-primary font-label-md text-label-md px-lg py-sm rounded-lg flex items-center gap-sm hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm">
<span class="material-symbols-outlined">add</span>
                Buat Work Order
            </button>
</div>
<!-- Summary Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter mb-lg">
<!-- Card 1 -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg border-t-4 border-t-primary shadow-sm flex flex-col justify-between">
<div class="flex justify-between items-start mb-md">
<span class="font-label-md text-label-md text-secondary uppercase tracking-wider">Total Pendapatan</span>
<span class="material-symbols-outlined text-primary bg-primary-fixed p-sm rounded-full">payments</span>
</div>
<div>
<div class="font-tabular-nums text-[32px] leading-tight font-semibold text-on-surface">Rp 12.450.000</div>
<div class="font-body-md text-body-md text-tertiary-container mt-xs flex items-center gap-xs">
<span class="material-symbols-outlined text-[16px]">trending_up</span>
<span>+8.5% dari kemarin</span>
</div>
</div>
</div>
<!-- Card 2 -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg border-t-4 border-t-secondary-fixed-dim shadow-sm flex flex-col justify-between">
<div class="flex justify-between items-start mb-md">
<span class="font-label-md text-label-md text-secondary uppercase tracking-wider">Servis Hari Ini</span>
<span class="material-symbols-outlined text-secondary bg-surface-container p-sm rounded-full">build_circle</span>
</div>
<div>
<div class="font-tabular-nums text-[32px] leading-tight font-semibold text-on-surface">24</div>
<div class="font-body-md text-body-md text-secondary mt-xs flex items-center gap-xs">
<span>18 Selesai, 6 Proses</span>
</div>
</div>
</div>
<!-- Card 3 -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg border-t-4 border-t-error shadow-sm flex flex-col justify-between">
<div class="flex justify-between items-start mb-md">
<span class="font-label-md text-label-md text-secondary uppercase tracking-wider">Antrean Aktif</span>
<span class="material-symbols-outlined text-error bg-error-container p-sm rounded-full">directions_car</span>
</div>
<div>
<div class="font-tabular-nums text-[32px] leading-tight font-semibold text-on-surface">12</div>
<div class="font-body-md text-body-md text-on-surface-variant mt-xs flex items-center gap-xs">
<span>3 Menunggu Sparepart</span>
</div>
</div>
</div>
<!-- Card 4 -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg border-t-4 border-t-tertiary-container shadow-sm flex flex-col justify-between">
<div class="flex justify-between items-start mb-md">
<span class="font-label-md text-label-md text-secondary uppercase tracking-wider">Pengingat Terkirim</span>
<span class="material-symbols-outlined text-tertiary-container bg-tertiary-fixed p-sm rounded-full">mark_email_read</span>
</div>
<div>
<div class="font-tabular-nums text-[32px] leading-tight font-semibold text-on-surface">45</div>
<div class="font-body-md text-body-md text-tertiary-container mt-xs flex items-center gap-xs">
<span>Untuk servis berkala minggu ini</span>
</div>
</div>
</div>
</div>
<!-- Main Content Layout -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
<!-- Monthly Trend Chart (Bento Cell) -->
<div class="lg:col-span-2 bg-surface-container-lowest border border-outline-variant rounded-xl p-lg shadow-sm flex flex-col">
<div class="flex justify-between items-center mb-lg">
<h3 class="font-headline-md text-headline-md text-on-surface">Tren Servis Bulanan</h3>
<button class="text-secondary hover:text-primary p-xs rounded hover:bg-surface-container transition-colors">
<span class="material-symbols-outlined">more_vert</span>
</button>
</div>
<!-- CSS Mock Chart -->
<div class="flex-grow flex items-end gap-sm h-64 mt-md border-b border-outline-variant pb-xs relative">
<!-- Y Axis Labels -->
<div class="absolute left-0 top-0 bottom-0 flex flex-col justify-between text-secondary font-label-md text-[10px] w-8">
<span>100</span>
<span>75</span>
<span>50</span>
<span>25</span>
<span>0</span>
</div>
<!-- Chart Bars Container -->
<div class="flex-grow flex items-end justify-around pl-10 h-full w-full">
<div class="w-12 bg-primary-fixed hover:bg-primary-fixed-dim transition-colors rounded-t-sm h-[40%] relative group cursor-pointer">
<div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-inverse-surface text-inverse-on-surface text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">40</div>
</div>
<div class="w-12 bg-primary-fixed hover:bg-primary-fixed-dim transition-colors rounded-t-sm h-[55%] relative group cursor-pointer">
<div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-inverse-surface text-inverse-on-surface text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">55</div>
</div>
<div class="w-12 bg-primary-fixed hover:bg-primary-fixed-dim transition-colors rounded-t-sm h-[80%] relative group cursor-pointer">
<div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-inverse-surface text-inverse-on-surface text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">80</div>
</div>
<div class="w-12 bg-primary hover:bg-primary-container transition-colors rounded-t-sm h-[95%] relative group cursor-pointer">
<div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-inverse-surface text-inverse-on-surface text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity z-10">95</div>
</div>
<div class="w-12 bg-primary-fixed hover:bg-primary-fixed-dim transition-colors rounded-t-sm h-[60%] relative group cursor-pointer">
<div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-inverse-surface text-inverse-on-surface text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">60</div>
</div>
<div class="w-12 bg-primary-fixed hover:bg-primary-fixed-dim transition-colors rounded-t-sm h-[70%] relative group cursor-pointer">
<div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-inverse-surface text-inverse-on-surface text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">70</div>
</div>
</div>
</div>
<!-- X Axis Labels -->
<div class="flex justify-around pl-10 mt-sm font-label-md text-label-md text-secondary">
<span>Jan</span>
<span>Feb</span>
<span>Mar</span>
<span class="text-primary font-bold">Apr</span>
<span>Mei</span>
<span>Jun</span>
</div>
</div>
<!-- Upcoming Services Table (Bento Cell) -->
<div class="lg:col-span-1 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm flex flex-col overflow-hidden">
<div class="p-lg border-b border-outline-variant flex justify-between items-center bg-surface-bright">
<h3 class="font-headline-md text-headline-md text-on-surface">Servis Mendatang</h3>
<button class="text-primary font-label-md text-label-md hover:underline">Lihat Semua</button>
</div>
<div class="flex-grow overflow-y-auto max-h-[360px]">
<!-- List Item 1 -->
<div class="p-md border-b border-outline-variant hover:bg-surface-container-low transition-colors flex justify-between items-center group cursor-pointer">
<div>
<p class="font-body-md text-body-md font-semibold text-on-surface">Budi Santoso</p>
<p class="font-label-md text-label-md text-secondary mt-xs">Toyota Avanza - B 1234 CD</p>
<p class="font-label-md text-label-md text-error mt-xs flex items-center gap-xs">
<span class="material-symbols-outlined text-[14px]">warning</span>
                                Jatuh Tempo: Hari Ini
                            </p>
</div>
<button class="text-primary bg-primary-fixed p-sm rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-primary hover:text-on-primary">
<span class="material-symbols-outlined">call</span>
</button>
</div>
<!-- List Item 2 -->
<div class="p-md border-b border-outline-variant hover:bg-surface-container-low transition-colors flex justify-between items-center group cursor-pointer">
<div>
<p class="font-body-md text-body-md font-semibold text-on-surface">Siti Rahma</p>
<p class="font-label-md text-label-md text-secondary mt-xs">Honda Jazz - D 5678 EF</p>
<p class="font-label-md text-label-md text-on-surface-variant mt-xs">
                                Besok - Servis Berkala 20k
                            </p>
</div>
<button class="text-primary bg-primary-fixed p-sm rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-primary hover:text-on-primary">
<span class="material-symbols-outlined">call</span>
</button>
</div>
<!-- List Item 3 -->
<div class="p-md border-b border-outline-variant hover:bg-surface-container-low transition-colors flex justify-between items-center group cursor-pointer">
<div>
<p class="font-body-md text-body-md font-semibold text-on-surface">PT Maju Mundur (Fleet)</p>
<p class="font-label-md text-label-md text-secondary mt-xs">3 Unit Hilux</p>
<p class="font-label-md text-label-md text-on-surface-variant mt-xs">
                                Lusa - Ganti Oli Massal
                            </p>
</div>
<button class="text-primary bg-primary-fixed p-sm rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-primary hover:text-on-primary">
<span class="material-symbols-outlined">call</span>
</button>
</div>
<!-- List Item 4 -->
<div class="p-md border-b border-outline-variant hover:bg-surface-container-low transition-colors flex justify-between items-center group cursor-pointer">
<div>
<p class="font-body-md text-body-md font-semibold text-on-surface">Ahmad Faisal</p>
<p class="font-label-md text-label-md text-secondary mt-xs">Mitsubishi Pajero - B 9012 GH</p>
<p class="font-label-md text-label-md text-on-surface-variant mt-xs">
                                2 Hari Lagi - Cek Rem
                            </p>
</div>
<button class="text-primary bg-primary-fixed p-sm rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-primary hover:text-on-primary">
<span class="material-symbols-outlined">call</span>
</button>
</div>
</div>
</div>
</div>
</main>
</body></html>