<?php
$allowedRoles = ['admin'];
require_once __DIR__ . '/../middleware/require_roles.php';
?>
<!DOCTYPE html>

<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Sistem Pengingat Servis - Bengkel AR</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
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
                      "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                      "label-md": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}],
                      "headline-lg": ["30px", {"lineHeight": "38px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                      "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                      "headline-md": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                      "headline-lg-mobile": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                      "tabular-nums": ["14px", {"lineHeight": "20px", "fontWeight": "500"}]
              }
            }
          }
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .fill-icon {
            font-variation-settings: 'FILL' 1;
        }
    </style>
</head>
<body class="bg-background text-on-background font-body-md text-body-md antialiased flex">
<!-- Shared Component: SideNavBar -->
<aside class="fixed left-0 top-0 h-screen w-[240px] bg-surface-container-lowest border-r border-outline-variant flex flex-col py-md z-20">
<div class="px-lg mb-xl">
<h1 class="font-headline-md text-headline-md font-bold text-primary">Bengkel AR</h1>
<p class="font-label-md text-label-md text-on-surface-variant">Admin Terminal</p>
</div>
<nav class="flex-1 flex flex-col gap-xs px-sm">
<!-- Inactive Tab: Dashboard -->
<a class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out" href="#">
<span class="material-symbols-outlined">dashboard</span>
<span class="font-label-md text-label-md">Dashboard</span>
</a>
<!-- Inactive Tab: Service Queue -->
<a class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out" href="#">
<span class="material-symbols-outlined">queue</span>
<span class="font-label-md text-label-md">Service Queue</span>
</a>
<!-- Inactive Tab: Customers -->
<a class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out" href="#">
<span class="material-symbols-outlined">group</span>
<span class="font-label-md text-label-md">Customers</span>
</a>
<!-- Inactive Tab: Service History -->
<a class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out" href="#">
<span class="material-symbols-outlined">history</span>
<span class="font-label-md text-label-md">Service History</span>
</a>
<!-- Active Tab: Reminders -->
<a class="flex items-center gap-md px-md py-sm rounded-lg text-primary font-bold border-r-4 border-primary bg-surface-container transition-colors duration-200 ease-in-out" href="#">
<span class="material-symbols-outlined fill-icon">notifications_active</span>
<span class="font-label-md text-label-md">Reminders</span>
</a>
<!-- Inactive Tab: Inventory -->
<a class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out" href="#">
<span class="material-symbols-outlined">inventory_2</span>
<span class="font-label-md text-label-md">Inventory</span>
</a>
</nav>
</aside>
<!-- Main Content Wrapper -->
<div class="flex-1 ml-[240px] flex flex-col min-h-screen">
<!-- Shared Component: TopNavBar -->
<header class="fixed top-0 right-0 left-[240px] h-16 bg-surface/80 backdrop-blur-md border-b border-outline-variant flex justify-between items-center px-lg z-10 w-[calc(100%-240px)]">
<div class="flex items-center gap-md flex-1">
<div class="relative w-64 focus-within:ring-2 focus-within:ring-primary rounded-lg">
<span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">search</span>
<input class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg pl-xl pr-sm py-sm font-body-md text-body-md text-on-surface focus:outline-none" placeholder="Search vehicle or customer..." type="text"/>
</div>
</div>
<div class="font-label-md text-label-md text-primary absolute left-1/2 -translate-x-1/2 pointer-events-none">
                Bengkel AR
            </div>
<div class="flex items-center gap-sm">
<button class="p-sm text-on-surface-variant hover:text-primary transition-colors duration-200 rounded-full hover:bg-surface-container-low">
<span class="material-symbols-outlined">settings</span>
</button>
<button class="p-sm text-on-surface-variant hover:text-primary transition-colors duration-200 rounded-full hover:bg-surface-container-low">
<span class="material-symbols-outlined">help_outline</span>
</button>
<button class="p-sm text-on-surface-variant hover:text-primary transition-colors duration-200 rounded-full hover:bg-surface-container-low">
<span class="material-symbols-outlined">account_circle</span>
</button>
</div>
</header>
<!-- Page Content -->
<main class="mt-16 p-lg flex-1 max-w-max-width w-full mx-auto">
<!-- Page Header -->
<div class="mb-lg flex justify-between items-end">
<div>
<h2 class="font-headline-lg text-headline-lg text-on-surface mb-xs">Pengingat Servis</h2>
<p class="font-body-md text-body-md text-on-surface-variant">Daftar kendaraan yang mendekati atau melewati jadwal servis.</p>
</div>
</div>
<!-- Bento Grid Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-lg mb-lg">
<!-- Stat Card 1 -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col relative overflow-hidden border-t-4 border-t-error">
<div class="flex justify-between items-center mb-md">
<span class="font-label-md text-label-md text-on-surface-variant uppercase">Jatuh Tempo Total</span>
<span class="material-symbols-outlined text-error">warning</span>
</div>
<div class="font-headline-lg text-headline-lg text-on-surface">124</div>
<p class="font-body-md text-body-md text-on-surface-variant mt-sm">Kendaraan butuh perhatian</p>
</div>
<!-- Stat Card 2 -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col relative overflow-hidden border-t-4 border-t-primary">
<div class="flex justify-between items-center mb-md">
<span class="font-label-md text-label-md text-on-surface-variant uppercase">Terkirim Hari Ini</span>
<span class="material-symbols-outlined text-primary">send</span>
</div>
<div class="font-headline-lg text-headline-lg text-on-surface">45</div>
<p class="font-body-md text-body-md text-on-surface-variant mt-sm">Pengingat WhatsApp terkirim</p>
</div>
<!-- Stat Card 3 -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col relative overflow-hidden border-t-4 border-t-tertiary-container">
<div class="flex justify-between items-center mb-md">
<span class="font-label-md text-label-md text-on-surface-variant uppercase">Jadwal Terkonfirmasi</span>
<span class="material-symbols-outlined text-tertiary-container">event_available</span>
</div>
<div class="font-headline-lg text-headline-lg text-on-surface">12</div>
<p class="font-body-md text-body-md text-on-surface-variant mt-sm">Booking masuk dari pengingat</p>
</div>
</div>
<!-- Controls (Filters & Actions) -->
<div class="flex justify-between items-center mb-md">
<div class="flex gap-sm">
<button class="px-md py-sm rounded-full bg-primary-container text-on-primary-container font-label-md text-label-md border border-transparent">
                        Semua Status
                    </button>
<button class="px-md py-sm rounded-full bg-surface-container-lowest text-on-surface-variant font-label-md text-label-md border border-outline-variant hover:bg-surface-container transition-colors">
                        Belum Dikirim
                    </button>
<button class="px-md py-sm rounded-full bg-surface-container-lowest text-on-surface-variant font-label-md text-label-md border border-outline-variant hover:bg-surface-container transition-colors">
                        Terkirim
                    </button>
<button class="px-md py-sm rounded-full bg-surface-container-lowest text-on-surface-variant font-label-md text-label-md border border-outline-variant hover:bg-surface-container transition-colors">
                        Dibatalkan
                    </button>
</div>
<div class="flex gap-sm">
<button class="flex items-center gap-xs px-md py-sm rounded-lg bg-surface-container-lowest text-secondary font-label-md text-label-md border border-outline-variant hover:bg-surface-container transition-colors">
<span class="material-symbols-outlined text-[18px]">filter_list</span>
                        Filter Lanjutan
                    </button>
</div>
</div>
<!-- Data Table -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
<table class="w-full text-left border-collapse">
<thead class="bg-surface-container-low border-b border-outline-variant">
<tr>
<th class="p-md font-label-md text-label-md text-on-surface-variant font-semibold">Pelanggan / Kendaraan</th>
<th class="p-md font-label-md text-label-md text-on-surface-variant font-semibold">Servis Terakhir</th>
<th class="p-md font-label-md text-label-md text-on-surface-variant font-semibold">Pemicu Reminder</th>
<th class="p-md font-label-md text-label-md text-on-surface-variant font-semibold">Status Pengingat</th>
<th class="p-md font-label-md text-label-md text-on-surface-variant font-semibold text-right">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant">
<!-- Row 1 -->
<tr class="hover:bg-surface-bright transition-colors">
<td class="p-md">
<div class="font-body-md text-body-md text-on-surface font-medium">Budi Santoso</div>
<div class="font-label-md text-label-md text-on-surface-variant mt-xs">B 1234 XYZ • Toyota Avanza</div>
</td>
<td class="p-md">
<div class="font-body-md text-body-md text-on-surface">15 Jan 2024</div>
<div class="font-label-md text-label-md text-on-surface-variant mt-xs tabular-nums">Odo: 45,200 km</div>
</td>
<td class="p-md">
<div class="flex items-center gap-xs text-error">
<span class="material-symbols-outlined text-[16px]">schedule</span>
<span class="font-label-md text-label-md">Jatuh Tempo Waktu (6 Bulan)</span>
</div>
</td>
<td class="p-md">
<span class="inline-flex items-center px-sm py-xs rounded-full bg-surface-container-highest text-on-surface-variant font-label-md text-label-md">
                                    Belum Dikirim
                                </span>
</td>
<td class="p-md text-right flex justify-end gap-sm">
<button class="flex items-center gap-xs px-md py-sm rounded-lg bg-primary text-on-primary font-label-md text-label-md hover:bg-primary-container transition-colors shadow-sm">
<span class="material-symbols-outlined text-[18px]">chat</span>
                                    Kirim WhatsApp
                                </button>
<button class="px-md py-sm rounded-lg border border-outline text-primary font-label-md text-label-md hover:bg-primary-fixed transition-colors">
                                    Jadwalkan
                                </button>
</td>
</tr>
<!-- Row 2 -->
<tr class="hover:bg-surface-bright transition-colors">
<td class="p-md">
<div class="font-body-md text-body-md text-on-surface font-medium">Siti Aminah</div>
<div class="font-label-md text-label-md text-on-surface-variant mt-xs">D 9876 ABC • Honda Brio</div>
</td>
<td class="p-md">
<div class="font-body-md text-body-md text-on-surface">02 Mar 2024</div>
<div class="font-label-md text-label-md text-on-surface-variant mt-xs tabular-nums">Odo: 21,500 km</div>
</td>
<td class="p-md">
<div class="flex items-center gap-xs text-on-surface">
<span class="material-symbols-outlined text-[16px]">speed</span>
<span class="font-label-md text-label-md">Est. Odo &gt; 30,000 km</span>
</div>
</td>
<td class="p-md">
<span class="inline-flex items-center px-sm py-xs rounded-full bg-secondary-container text-on-secondary-container font-label-md text-label-md">
                                    Terkirim (Kemarin)
                                </span>
</td>
<td class="p-md text-right flex justify-end gap-sm">
<button class="px-md py-sm rounded-lg border border-outline text-primary font-label-md text-label-md hover:bg-primary-fixed transition-colors">
                                    Jadwalkan
                                </button>
</td>
</tr>
<!-- Row 3 -->
<tr class="hover:bg-surface-bright transition-colors">
<td class="p-md">
<div class="font-body-md text-body-md text-on-surface font-medium">PT. Maju Mundur</div>
<div class="font-label-md text-label-md text-on-surface-variant mt-xs">L 5555 KL • Mitsubishi L300</div>
</td>
<td class="p-md">
<div class="font-body-md text-body-md text-on-surface">10 Dec 2023</div>
<div class="font-label-md text-label-md text-on-surface-variant mt-xs tabular-nums">Odo: 120,000 km</div>
</td>
<td class="p-md">
<div class="flex items-center gap-xs text-error">
<span class="material-symbols-outlined text-[16px]">warning</span>
<span class="font-label-md text-label-md">Terlewat &gt; 3 Bulan</span>
</div>
</td>
<td class="p-md">
<span class="inline-flex items-center px-sm py-xs rounded-full bg-surface-container-highest text-on-surface-variant font-label-md text-label-md">
                                    Dibatalkan
                                </span>
</td>
<td class="p-md text-right flex justify-end gap-sm">
<button class="flex items-center gap-xs px-md py-sm rounded-lg bg-primary text-on-primary font-label-md text-label-md hover:bg-primary-container transition-colors shadow-sm">
<span class="material-symbols-outlined text-[18px]">chat</span>
                                    Kirim Ulang WA
                                </button>
<button class="px-md py-sm rounded-lg border border-outline text-primary font-label-md text-label-md hover:bg-primary-fixed transition-colors">
                                    Jadwalkan
                                </button>
</td>
</tr>
</tbody>
</table>
<!-- Pagination Footer -->
<div class="bg-surface-container-lowest border-t border-outline-variant p-md flex justify-between items-center">
<span class="font-label-md text-label-md text-on-surface-variant">Menampilkan 1-3 dari 124 pelanggan</span>
<div class="flex gap-xs">
<button class="p-sm rounded border border-outline-variant text-on-surface-variant hover:bg-surface-container disabled:opacity-50" disabled="">
<span class="material-symbols-outlined text-[18px]">chevron_left</span>
</button>
<button class="p-sm rounded border border-outline-variant text-on-surface-variant hover:bg-surface-container">
<span class="material-symbols-outlined text-[18px]">chevron_right</span>
</button>
</div>
</div>
</div>
</main>
</div>
</body></html>