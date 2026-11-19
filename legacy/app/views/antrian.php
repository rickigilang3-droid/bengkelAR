<?php
$allowedRoles = ['admin', 'mekanik', 'teknisi'];
require_once __DIR__ . '/../middleware/require_roles.php';
?>
<!DOCTYPE html>

<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Bengkel AR - Service Queue</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-background text-on-background font-body-md text-body-md antialiased flex h-screen overflow-hidden">
<!-- SideNavBar -->
<nav class="hidden md:flex bg-surface-container-lowest border-r border-outline-variant fixed left-0 top-0 h-screen w-[240px] flex-col py-md z-20">
<div class="px-lg mb-xl">
<h1 class="font-headline-md text-headline-md font-bold text-primary">Bengkel AR</h1>
<p class="font-label-md text-label-md text-secondary mt-1 uppercase tracking-wider">Admin Terminal</p>
</div>
<ul class="flex flex-col gap-sm px-sm w-full">
<li>
<a class="flex items-center gap-md px-4 py-3 rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out w-full" href="#">
<span class="material-symbols-outlined">dashboard</span>
<span class="font-body-md text-body-md font-medium">Dashboard</span>
</a>
</li>
<li>
<a class="flex items-center gap-md px-4 py-3 rounded-lg text-primary font-bold border-r-4 border-primary bg-surface-container w-full opacity-80" href="#">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">queue</span>
<span class="font-body-md text-body-md font-medium">Service Queue</span>
</a>
</li>
<li>
<a class="flex items-center gap-md px-4 py-3 rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out w-full" href="#">
<span class="material-symbols-outlined">group</span>
<span class="font-body-md text-body-md font-medium">Customers</span>
</a>
</li>
<li>
<a class="flex items-center gap-md px-4 py-3 rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out w-full" href="#">
<span class="material-symbols-outlined">history</span>
<span class="font-body-md text-body-md font-medium">Service History</span>
</a>
</li>
<li>
<a class="flex items-center gap-md px-4 py-3 rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out w-full" href="#">
<span class="material-symbols-outlined">notifications_active</span>
<span class="font-body-md text-body-md font-medium">Reminders</span>
</a>
</li>
<li>
<a class="flex items-center gap-md px-4 py-3 rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out w-full" href="#">
<span class="material-symbols-outlined">inventory_2</span>
<span class="font-body-md text-body-md font-medium">Inventory</span>
</a>
</li>
</ul>
</nav>
<!-- Main Content Wrapper -->
<div class="flex-1 flex flex-col md:ml-[240px] w-full h-full relative">
<!-- TopNavBar -->
<header class="bg-surface/80 backdrop-blur-md border-b border-outline-variant fixed top-0 right-0 md:left-[240px] left-0 h-16 flex justify-between items-center px-lg w-full max-w-max-width mx-auto z-10">
<div class="flex items-center gap-md w-full max-w-md">
<div class="hidden md:block font-label-md text-label-md text-secondary mr-md uppercase tracking-wider whitespace-nowrap">
                    Bengkel AR
                </div>
<div class="flex-1 flex items-center bg-surface-container rounded-full px-4 py-2 border border-outline-variant focus-within:ring-2 focus-within:ring-primary transition-all duration-200">
<span class="material-symbols-outlined text-outline text-[20px]">search</span>
<input class="bg-transparent border-none outline-none focus:ring-0 text-body-md ml-2 w-full text-on-surface" placeholder="Search plates, owners, or mechanics..." type="text"/>
</div>
</div>
<div class="flex items-center gap-sm">
<button class="p-2 rounded-full text-on-surface-variant hover:text-primary hover:bg-surface-container-low transition-colors duration-200 ease-in-out">
<span class="material-symbols-outlined">settings</span>
</button>
<button class="p-2 rounded-full text-on-surface-variant hover:text-primary hover:bg-surface-container-low transition-colors duration-200 ease-in-out">
<span class="material-symbols-outlined">help_outline</span>
</button>
<div class="h-8 w-px bg-outline-variant mx-2"></div>
<button class="p-2 rounded-full text-primary hover:bg-surface-container-low transition-colors duration-200 ease-in-out flex items-center gap-2">
<span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1;">account_circle</span>
</button>
</div>
</header>
<!-- Page Canvas -->
<main class="flex-1 overflow-y-auto mt-16 p-margin-mobile md:p-margin-desktop bg-background">
<div class="max-w-max-width mx-auto">
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-xl">
<div>
<h2 class="font-headline-lg text-headline-lg text-on-background">Antrean Servis Aktif</h2>
<p class="font-body-md text-body-md text-secondary mt-1">Kelola kendaraan yang sedang berada di area bengkel hari ini.</p>
</div>
<button class="flex items-center justify-center gap-2 bg-primary text-on-primary px-6 py-3 rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm whitespace-nowrap w-full md:w-auto">
<span class="material-symbols-outlined text-[20px]">add</span>
                        Tambah Antrean Baru
                    </button>
</div>
<!-- Stats Overview Cards (Bento-style snippet) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-lg mb-lg">
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg relative overflow-hidden">
<div class="absolute top-0 left-0 w-full h-1 bg-secondary-container"></div>
<p class="font-label-md text-label-md text-secondary uppercase tracking-wider mb-2">Menunggu</p>
<div class="flex items-end gap-2">
<span class="font-headline-lg text-headline-lg text-on-background">12</span>
<span class="font-body-md text-body-md text-secondary mb-1">Unit</span>
</div>
</div>
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg relative overflow-hidden">
<div class="absolute top-0 left-0 w-full h-1 bg-primary-container"></div>
<p class="font-label-md text-label-md text-secondary uppercase tracking-wider mb-2">Dikerjakan</p>
<div class="flex items-end gap-2">
<span class="font-headline-lg text-headline-lg text-on-background">8</span>
<span class="font-body-md text-body-md text-secondary mb-1">Unit</span>
</div>
</div>
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg relative overflow-hidden">
<div class="absolute top-0 left-0 w-full h-1 bg-tertiary-fixed-dim"></div>
<p class="font-label-md text-label-md text-secondary uppercase tracking-wider mb-2">Selesai (Hari Ini)</p>
<div class="flex items-end gap-2">
<span class="font-headline-lg text-headline-lg text-on-background">24</span>
<span class="font-body-md text-body-md text-secondary mb-1">Unit</span>
</div>
</div>
</div>
<!-- Data Table Card -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container border-b border-outline-variant">
<th class="py-4 px-6 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider whitespace-nowrap">No. Polisi</th>
<th class="py-4 px-6 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider whitespace-nowrap">Pemilik</th>
<th class="py-4 px-6 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider whitespace-nowrap">Tipe Motor</th>
<th class="py-4 px-6 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider whitespace-nowrap">Mekanik</th>
<th class="py-4 px-6 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider whitespace-nowrap">Status</th>
<th class="py-4 px-6 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider whitespace-nowrap text-right">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant">
<!-- Row 1 -->
<tr class="hover:bg-surface-container-low transition-colors duration-150">
<td class="py-4 px-6 font-tabular-nums text-tabular-nums text-on-background font-medium">B 3452 KJD</td>
<td class="py-4 px-6 font-body-md text-body-md text-on-surface">Agus Pratama</td>
<td class="py-4 px-6 font-body-md text-body-md text-secondary">Honda Vario 150 eSP</td>
<td class="py-4 px-6">
<div class="flex items-center gap-2">
<div class="w-6 h-6 rounded-full bg-surface-variant text-on-surface-variant flex items-center justify-center font-label-md text-[10px] font-bold">R</div>
<span class="font-body-md text-body-md text-on-surface">Rudi</span>
</div>
</td>
<td class="py-4 px-6">
<span class="inline-flex items-center px-3 py-1 rounded-full bg-primary-container text-on-primary-container font-label-md text-[11px] font-medium tracking-wide">
<span class="w-1.5 h-1.5 rounded-full bg-on-primary-container mr-2"></span>
                                            Dikerjakan
                                        </span>
</td>
<td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
<button class="inline-flex items-center justify-center p-2 rounded-lg text-primary hover:bg-primary-fixed transition-colors" title="Update Status">
<span class="material-symbols-outlined text-[20px]">swap_horiz</span>
</button>
<button class="inline-flex items-center justify-center p-2 rounded-lg text-secondary hover:bg-surface-container-highest transition-colors" title="Detail Kendaraan">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</button>
</td>
</tr>
<!-- Row 2 -->
<tr class="hover:bg-surface-container-low transition-colors duration-150">
<td class="py-4 px-6 font-tabular-nums text-tabular-nums text-on-background font-medium">D 8810 ZBC</td>
<td class="py-4 px-6 font-body-md text-body-md text-on-surface">Siti Aminah</td>
<td class="py-4 px-6 font-body-md text-body-md text-secondary">Yamaha NMAX 155</td>
<td class="py-4 px-6">
<span class="font-body-md text-body-md text-secondary italic text-[13px]">Belum dialokasikan</span>
</td>
<td class="py-4 px-6">
<span class="inline-flex items-center px-3 py-1 rounded-full bg-secondary-container text-on-secondary-container font-label-md text-[11px] font-medium tracking-wide">
<span class="w-1.5 h-1.5 rounded-full bg-on-secondary-container mr-2"></span>
                                            Menunggu
                                        </span>
</td>
<td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
<button class="inline-flex items-center justify-center p-2 rounded-lg text-primary hover:bg-primary-fixed transition-colors" title="Update Status">
<span class="material-symbols-outlined text-[20px]">swap_horiz</span>
</button>
<button class="inline-flex items-center justify-center p-2 rounded-lg text-secondary hover:bg-surface-container-highest transition-colors" title="Detail Kendaraan">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</button>
</td>
</tr>
<!-- Row 3 -->
<tr class="hover:bg-surface-container-low transition-colors duration-150 bg-surface-bright">
<td class="py-4 px-6 font-tabular-nums text-tabular-nums text-on-background font-medium">L 1290 WQ</td>
<td class="py-4 px-6 font-body-md text-body-md text-on-surface">Budi Gunawan</td>
<td class="py-4 px-6 font-body-md text-body-md text-secondary">Honda Beat FI</td>
<td class="py-4 px-6">
<div class="flex items-center gap-2">
<div class="w-6 h-6 rounded-full bg-surface-variant text-on-surface-variant flex items-center justify-center font-label-md text-[10px] font-bold">A</div>
<span class="font-body-md text-body-md text-on-surface">Anton</span>
</div>
</td>
<td class="py-4 px-6">
<span class="inline-flex items-center px-3 py-1 rounded-full bg-tertiary-fixed text-on-tertiary-fixed font-label-md text-[11px] font-medium tracking-wide">
<span class="material-symbols-outlined text-[14px] mr-1" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                            Selesai
                                        </span>
</td>
<td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
<button class="opacity-50 cursor-not-allowed" disabled="" title="Detail Kendaraan">
<span class="material-symbols-outlined text-[20px]">receipt_long</span>
</button>
<button class="inline-flex items-center justify-center p-2 rounded-lg text-secondary hover:bg-surface-container-highest transition-colors" title="Detail Kendaraan">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</button>
</td>
</tr>
<!-- Row 4 -->
<tr class="hover:bg-surface-container-low transition-colors duration-150">
<td class="py-4 px-6 font-tabular-nums text-tabular-nums text-on-background font-medium">F 5561 TR</td>
<td class="py-4 px-6 font-body-md text-body-md text-on-surface">Dewi Lestari</td>
<td class="py-4 px-6 font-body-md text-body-md text-secondary">Vespa Sprint 150</td>
<td class="py-4 px-6">
<div class="flex items-center gap-2">
<div class="w-6 h-6 rounded-full bg-surface-variant text-on-surface-variant flex items-center justify-center font-label-md text-[10px] font-bold">T</div>
<span class="font-body-md text-body-md text-on-surface">Tono</span>
</div>
</td>
<td class="py-4 px-6">
<span class="inline-flex items-center px-3 py-1 rounded-full bg-primary-container text-on-primary-container font-label-md text-[11px] font-medium tracking-wide">
<span class="w-1.5 h-1.5 rounded-full bg-on-primary-container mr-2"></span>
                                            Dikerjakan
                                        </span>
</td>
<td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
<button class="inline-flex items-center justify-center p-2 rounded-lg text-primary hover:bg-primary-fixed transition-colors" title="Update Status">
<span class="material-symbols-outlined text-[20px]">swap_horiz</span>
</button>
<button class="inline-flex items-center justify-center p-2 rounded-lg text-secondary hover:bg-surface-container-highest transition-colors" title="Detail Kendaraan">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</button>
</td>
</tr>
</tbody>
</table>
</div>
<!-- Pagination Footer -->
<div class="border-t border-outline-variant bg-surface px-6 py-4 flex items-center justify-between">
<span class="font-body-md text-[13px] text-secondary">Menampilkan 1 hingga 4 dari 24 entri</span>
<div class="flex items-center gap-2">
<button class="p-1 rounded text-outline hover:text-primary transition-colors disabled:opacity-50" disabled="">
<span class="material-symbols-outlined text-[20px]">chevron_left</span>
</button>
<button class="w-8 h-8 rounded-lg bg-primary-container text-on-primary-container font-tabular-nums text-tabular-nums flex items-center justify-center">1</button>
<button class="w-8 h-8 rounded-lg text-secondary hover:bg-surface-container-highest font-tabular-nums text-tabular-nums flex items-center justify-center transition-colors">2</button>
<button class="w-8 h-8 rounded-lg text-secondary hover:bg-surface-container-highest font-tabular-nums text-tabular-nums flex items-center justify-center transition-colors">3</button>
<span class="text-secondary px-1">...</span>
<button class="p-1 rounded text-secondary hover:text-primary transition-colors">
<span class="material-symbols-outlined text-[20px]">chevron_right</span>
</button>
</div>
</div>
</div>
</div>
</main>
</div>
</body></html>