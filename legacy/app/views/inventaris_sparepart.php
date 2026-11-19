<?php
$allowedRoles = ['admin'];
require_once __DIR__ . '/../middleware/require_roles.php';
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Manajemen Inventaris Sparepart Bengkel AR</title>
<!-- Google Fonts & Material Symbols -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<!-- Theme Configuration -->
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
                      "body-md": [
                              "Inter"
                      ],
                      "label-md": [
                              "Inter"
                      ],
                      "headline-lg": [
                              "Inter"
                      ],
                      "body-lg": [
                              "Inter"
                      ],
                      "headline-md": [
                              "Inter"
                      ],
                      "headline-lg-mobile": [
                              "Inter"
                      ],
                      "tabular-nums": [
                              "Inter"
                      ]
              },
              "fontSize": {
                      "body-md": [
                              "14px",
                              {
                                      "lineHeight": "20px",
                                      "fontWeight": "400"
                              }
                      ],
                      "label-md": [
                              "12px",
                              {
                                      "lineHeight": "16px",
                                      "letterSpacing": "0.05em",
                                      "fontWeight": "600"
                              }
                      ],
                      "headline-lg": [
                              "30px",
                              {
                                      "lineHeight": "38px",
                                      "letterSpacing": "-0.02em",
                                      "fontWeight": "700"
                              }
                      ],
                      "body-lg": [
                              "16px",
                              {
                                      "lineHeight": "24px",
                                      "fontWeight": "400"
                              }
                      ],
                      "headline-md": [
                              "20px",
                              {
                                      "lineHeight": "28px",
                                      "fontWeight": "600"
                              }
                      ],
                      "headline-lg-mobile": [
                              "24px",
                              {
                                      "lineHeight": "32px",
                                      "letterSpacing": "-0.02em",
                                      "fontWeight": "700"
                              }
                      ],
                      "tabular-nums": [
                              "14px",
                              {
                                      "lineHeight": "20px",
                                      "fontWeight": "500"
                              }
                      ]
              }
            },
          },
        }
    </script>
</head>
<body class="bg-background text-on-background font-body-md min-h-screen antialiased flex">
<!-- SideNavBar -->
<nav class="bg-surface-container-lowest text-primary font-body-md text-body-md fixed left-0 top-0 h-screen w-[240px] border-r border-outline-variant flex flex-col py-md z-20">
<div class="px-lg mb-xl">
<h1 class="font-headline-md text-headline-md font-bold text-primary">Bengkel AR</h1>
<p class="font-label-md text-label-md text-secondary mt-xs">Admin Terminal</p>
</div>
<div class="flex-1 overflow-y-auto">
<ul class="space-y-sm">
<!-- Dashboard -->
<li>
<a class="flex items-center px-lg py-sm text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out group" href="#">
<span class="material-symbols-outlined mr-md group-hover:text-primary">dashboard</span>
<span>Dashboard</span>
</a>
</li>
<!-- Service Queue -->
<li>
<a class="flex items-center px-lg py-sm text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out group" href="#">
<span class="material-symbols-outlined mr-md group-hover:text-primary">queue</span>
<span>Service Queue</span>
</a>
</li>
<!-- Customers -->
<li>
<a class="flex items-center px-lg py-sm text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out group" href="#">
<span class="material-symbols-outlined mr-md group-hover:text-primary">group</span>
<span>Customers</span>
</a>
</li>
<!-- Service History -->
<li>
<a class="flex items-center px-lg py-sm text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out group" href="#">
<span class="material-symbols-outlined mr-md group-hover:text-primary">history</span>
<span>Service History</span>
</a>
</li>
<!-- Reminders -->
<li>
<a class="flex items-center px-lg py-sm text-secondary hover:bg-surface-container-low transition-colors duration-200 ease-in-out group" href="#">
<span class="material-symbols-outlined mr-md group-hover:text-primary">notifications_active</span>
<span>Reminders</span>
</a>
</li>
<!-- Inventory (Active) -->
<li>
<a class="flex items-center px-lg py-sm text-primary font-bold border-r-4 border-primary bg-surface-container opacity-80 transition-colors duration-200 ease-in-out" href="#">
<span class="material-symbols-outlined mr-md">inventory_2</span>
<span>Inventory</span>
</a>
</li>
</ul>
</div>
</nav>
<!-- TopNavBar -->
<header class="bg-surface text-primary font-label-md text-label-md fixed top-0 right-0 left-[240px] h-16 bg-surface/80 backdrop-blur-md border-b border-outline-variant flex justify-between items-center px-lg w-full max-w-max-width mx-auto z-10">
<!-- Search Bar (Left) -->
<div class="relative w-96 group focus-within:ring-2 focus-within:ring-primary rounded-DEFAULT">
<div class="absolute inset-y-0 left-0 flex items-center pl-sm pointer-events-none">
<span class="material-symbols-outlined text-outline">search</span>
</div>
<input class="w-full pl-xl pr-sm py-sm bg-surface-container-highest border-none rounded-DEFAULT text-on-surface-variant focus:outline-none focus:ring-0 font-body-md placeholder:text-outline transition-colors duration-200 ease-in-out" placeholder="Cari sparepart..." type="text"/>
</div>
<div class="flex items-center space-x-md">
<span class="font-headline-md text-headline-md font-bold text-primary mr-lg hidden lg:block">Bengkel AR</span>
<!-- Trailing Icons -->
<button class="text-on-surface-variant hover:text-primary transition-colors duration-200 ease-in-out p-xs rounded-full hover:bg-surface-container">
<span class="material-symbols-outlined">settings</span>
</button>
<button class="text-on-surface-variant hover:text-primary transition-colors duration-200 ease-in-out p-xs rounded-full hover:bg-surface-container">
<span class="material-symbols-outlined">help_outline</span>
</button>
<button class="text-on-surface-variant hover:text-primary transition-colors duration-200 ease-in-out p-xs rounded-full hover:bg-surface-container">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">account_circle</span>
</button>
</div>
</header>
<!-- Main Content Area -->
<main class="ml-[240px] mt-16 p-margin-desktop w-full max-w-max-width mx-auto">
<!-- Page Header -->
<div class="flex justify-between items-end mb-xl">
<div>
<h2 class="font-headline-lg text-headline-lg text-on-background">Manajemen Inventaris Sparepart</h2>
<p class="font-body-md text-body-md text-secondary mt-xs">Kelola stok, harga, dan ketersediaan komponen bengkel.</p>
</div>
<button class="bg-primary text-on-primary font-label-md text-label-md px-lg py-sm rounded-DEFAULT hover:opacity-90 transition-opacity flex items-center">
<span class="material-symbols-outlined mr-xs text-[18px]">add</span>
                Tambah Barang
            </button>
</div>
<!-- Inventory Table Container -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden border-t-4 border-t-primary">
<!-- Table Toolbar -->
<div class="p-md border-b border-outline-variant flex justify-between items-center bg-surface-bright">
<div class="flex gap-md">
<select class="bg-surface border border-outline-variant text-on-surface-variant font-body-md rounded-DEFAULT px-md py-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
<option>Semua Kategori</option>
<option>Oli &amp; Cairan</option>
<option>Suku Cadang Mesin</option>
<option>Sistem Pengereman</option>
</select>
<select class="bg-surface border border-outline-variant text-on-surface-variant font-body-md rounded-DEFAULT px-md py-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
<option>Semua Status</option>
<option>Aman</option>
<option>Menipis</option>
</select>
</div>
<button class="text-secondary hover:text-primary font-label-md text-label-md flex items-center border border-outline-variant px-md py-sm rounded-DEFAULT bg-surface">
<span class="material-symbols-outlined mr-xs text-[18px]">download</span>
                    Export CSV
                </button>
</div>
<!-- Data Table -->
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead class="bg-surface-container-low border-b border-outline-variant">
<tr>
<th class="font-label-md text-label-md text-secondary px-lg py-md whitespace-nowrap">Kode</th>
<th class="font-label-md text-label-md text-secondary px-lg py-md w-1/3">Nama Barang</th>
<th class="font-label-md text-label-md text-secondary px-lg py-md">Kategori</th>
<th class="font-label-md text-label-md text-secondary px-lg py-md text-right">Stok Tersedia</th>
<th class="font-label-md text-label-md text-secondary px-lg py-md text-right">Harga Jual</th>
<th class="font-label-md text-label-md text-secondary px-lg py-md text-center">Status Stok</th>
<th class="font-label-md text-label-md text-secondary px-lg py-md text-center">Aksi</th>
</tr>
</thead>
<tbody class="font-tabular-nums text-tabular-nums text-on-surface divide-y divide-outline-variant">
<!-- Row 1: Safe Stock -->
<tr class="hover:bg-surface-container-low transition-colors duration-150">
<td class="px-lg py-md text-secondary">OL-001</td>
<td class="px-lg py-md font-body-md text-body-md font-medium">Oli Mesin TMO 10W-40 4L</td>
<td class="px-lg py-md text-secondary font-body-md text-body-md">Oli &amp; Cairan</td>
<td class="px-lg py-md text-right">45</td>
<td class="px-lg py-md text-right">Rp 350.000</td>
<td class="px-lg py-md text-center">
<span class="inline-flex items-center px-sm py-base rounded-full bg-tertiary-fixed text-on-tertiary-fixed-variant font-label-md text-label-md">
                                    Aman
                                </span>
</td>
<td class="px-lg py-md text-center">
<button class="text-secondary hover:text-primary transition-colors p-xs"><span class="material-symbols-outlined text-[20px]">edit</span></button>
</td>
</tr>
<!-- Row 2: Low Stock (Highlighted) -->
<tr class="bg-error-container/20 hover:bg-error-container/40 transition-colors duration-150 border-l-4 border-l-error">
<td class="px-lg py-md text-on-error-container font-medium">BR-102</td>
<td class="px-lg py-md font-body-md text-body-md font-medium text-on-error-container">Kampas Rem Depan Bendix</td>
<td class="px-lg py-md text-secondary font-body-md text-body-md">Sistem Pengereman</td>
<td class="px-lg py-md text-right font-bold text-error">2</td>
<td class="px-lg py-md text-right text-on-error-container">Rp 280.000</td>
<td class="px-lg py-md text-center">
<span class="inline-flex items-center px-sm py-base rounded-full bg-error text-on-error font-label-md text-label-md">
<span class="material-symbols-outlined mr-[2px] text-[14px]">warning</span> Menipis
                                </span>
</td>
<td class="px-lg py-md text-center">
<button class="text-error hover:text-on-error-container transition-colors p-xs"><span class="material-symbols-outlined text-[20px]">edit</span></button>
</td>
</tr>
<!-- Row 3: Safe Stock -->
<tr class="hover:bg-surface-container-low transition-colors duration-150">
<td class="px-lg py-md text-secondary">FL-044</td>
<td class="px-lg py-md font-body-md text-body-md font-medium">Filter Udara Denso</td>
<td class="px-lg py-md text-secondary font-body-md text-body-md">Suku Cadang Mesin</td>
<td class="px-lg py-md text-right">18</td>
<td class="px-lg py-md text-right">Rp 85.000</td>
<td class="px-lg py-md text-center">
<span class="inline-flex items-center px-sm py-base rounded-full bg-tertiary-fixed text-on-tertiary-fixed-variant font-label-md text-label-md">
                                    Aman
                                </span>
</td>
<td class="px-lg py-md text-center">
<button class="text-secondary hover:text-primary transition-colors p-xs"><span class="material-symbols-outlined text-[20px]">edit</span></button>
</td>
</tr>
<!-- Row 4: Low Stock (Highlighted) -->
<tr class="bg-error-container/20 hover:bg-error-container/40 transition-colors duration-150 border-l-4 border-l-error">
<td class="px-lg py-md text-on-error-container font-medium">SP-011</td>
<td class="px-lg py-md font-body-md text-body-md font-medium text-on-error-container">Busi NGK Iridium</td>
<td class="px-lg py-md text-secondary font-body-md text-body-md">Suku Cadang Mesin</td>
<td class="px-lg py-md text-right font-bold text-error">4</td>
<td class="px-lg py-md text-right text-on-error-container">Rp 110.000</td>
<td class="px-lg py-md text-center">
<span class="inline-flex items-center px-sm py-base rounded-full bg-error text-on-error font-label-md text-label-md">
<span class="material-symbols-outlined mr-[2px] text-[14px]">warning</span> Menipis
                                </span>
</td>
<td class="px-lg py-md text-center">
<button class="text-error hover:text-on-error-container transition-colors p-xs"><span class="material-symbols-outlined text-[20px]">edit</span></button>
</td>
</tr>
<!-- Row 5: Safe Stock -->
<tr class="hover:bg-surface-container-low transition-colors duration-150">
<td class="px-lg py-md text-secondary">BT-200</td>
<td class="px-lg py-md font-body-md text-body-md font-medium">Aki GS Astra MF NS40Z</td>
<td class="px-lg py-md text-secondary font-body-md text-body-md">Kelistrikan</td>
<td class="px-lg py-md text-right">12</td>
<td class="px-lg py-md text-right">Rp 850.000</td>
<td class="px-lg py-md text-center">
<span class="inline-flex items-center px-sm py-base rounded-full bg-tertiary-fixed text-on-tertiary-fixed-variant font-label-md text-label-md">
                                    Aman
                                </span>
</td>
<td class="px-lg py-md text-center">
<button class="text-secondary hover:text-primary transition-colors p-xs"><span class="material-symbols-outlined text-[20px]">edit</span></button>
</td>
</tr>
</tbody>
</table>
</div>
<!-- Pagination (Mock) -->
<div class="px-lg py-md border-t border-outline-variant bg-surface-bright flex items-center justify-between">
<span class="font-body-md text-body-md text-secondary">Menampilkan 1 - 5 dari 142 barang</span>
<div class="flex gap-xs">
<button class="p-sm rounded-DEFAULT border border-outline-variant text-secondary hover:bg-surface-container disabled:opacity-50" disabled="">
<span class="material-symbols-outlined text-[18px]">chevron_left</span>
</button>
<button class="p-sm rounded-DEFAULT border border-outline-variant bg-primary text-on-primary font-tabular-nums text-tabular-nums">1</button>
<button class="p-sm rounded-DEFAULT border border-outline-variant text-secondary hover:bg-surface-container font-tabular-nums text-tabular-nums">2</button>
<button class="p-sm rounded-DEFAULT border border-outline-variant text-secondary hover:bg-surface-container font-tabular-nums text-tabular-nums">3</button>
<button class="p-sm rounded-DEFAULT border border-outline-variant text-secondary hover:bg-surface-container">
<span class="material-symbols-outlined text-[18px]">chevron_right</span>
</button>
</div>
</div>
</div>
</main>
</body></html>