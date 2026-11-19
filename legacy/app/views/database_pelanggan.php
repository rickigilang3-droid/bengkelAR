<?php
$allowedRoles = ['admin'];
require_once __DIR__ . '/../middleware/require_roles.php';
?>
<!DOCTYPE html>

<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Customer Database - Bengkel AR</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "inverse-primary": "#b8c4ff",
                        "outline": "#757684",
                        "on-primary": "#ffffff",
                        "on-secondary-container": "#54647a",
                        "primary-container": "#1e40af",
                        "error": "#ba1a1a",
                        "tertiary-container": "#00563a",
                        "surface-bright": "#f7f9fb",
                        "secondary": "#505f76",
                        "inverse-surface": "#2d3133",
                        "on-error-container": "#93000a",
                        "on-primary-fixed-variant": "#173bab",
                        "outline-variant": "#c4c5d5",
                        "on-surface": "#191c1e",
                        "on-background": "#191c1e",
                        "surface-variant": "#e0e3e5",
                        "secondary-fixed": "#d3e4fe",
                        "surface-tint": "#3755c3",
                        "on-surface-variant": "#444653",
                        "surface-container-highest": "#e0e3e5",
                        "primary-fixed": "#dde1ff",
                        "on-tertiary-container": "#3fd298",
                        "error-container": "#ffdad6",
                        "surface-container-low": "#f2f4f6",
                        "on-tertiary-fixed-variant": "#005236",
                        "on-secondary-fixed-variant": "#38485d",
                        "inverse-on-surface": "#eff1f3",
                        "on-primary-container": "#a8b8ff",
                        "surface-container-high": "#e6e8ea",
                        "tertiary": "#003d27",
                        "surface-container-lowest": "#ffffff",
                        "on-error": "#ffffff",
                        "tertiary-fixed": "#6ffbbe",
                        "background": "#f7f9fb",
                        "on-tertiary-fixed": "#002113",
                        "surface-dim": "#d8dadc",
                        "on-primary-fixed": "#001453",
                        "tertiary-fixed-dim": "#4edea3",
                        "on-tertiary": "#ffffff",
                        "on-secondary-fixed": "#0b1c30",
                        "surface": "#f7f9fb",
                        "secondary-fixed-dim": "#b7c8e1",
                        "secondary-container": "#d0e1fb",
                        "primary-fixed-dim": "#b8c4ff",
                        "primary": "#00288e",
                        "on-secondary": "#ffffff",
                        "surface-container": "#eceef0"
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
                        "max-width": "1440px",
                        "base": "4px",
                        "gutter": "24px"
                    },
                    "fontFamily": {
                        "body-md": ["Inter"],
                        "label-md": ["Inter"],
                        "headline-lg": ["Inter"],
                        "headline-md": ["Inter"],
                        "body-lg": ["Inter"],
                        "tabular-nums": ["Inter"],
                        "headline-lg-mobile": ["Inter"]
                    },
                    "fontSize": {
                        "body-md": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                        "label-md": ["12px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600" }],
                        "headline-lg": ["30px", { "lineHeight": "38px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "headline-md": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                        "body-lg": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "tabular-nums": ["14px", { "lineHeight": "20px", "fontWeight": "500" }],
                        "headline-lg-mobile": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.02em", "fontWeight": "700" }]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background text-on-background font-body-md h-screen flex overflow-hidden">
<!-- SideNavBar (Desktop) -->
<nav class="hidden md:flex flex-col h-full w-60 p-md gap-sm bg-surface-container-low dark:bg-inverse-surface border-r border-outline-variant dark:border-outline shrink-0">
<!-- Header -->
<div class="flex items-center gap-sm mb-lg px-sm">
<div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-on-primary-container">build</span>
</div>
<div>
<h1 class="font-headline-md text-headline-md font-black text-primary dark:text-inverse-primary truncate">Bengkel AR</h1>
<p class="font-label-md text-label-md text-on-surface-variant truncate">Main Branch Admin</p>
</div>
</div>
<!-- CTA -->
<button class="w-full flex items-center justify-center gap-xs py-sm px-md mb-md bg-primary text-on-primary rounded-full hover:bg-primary/90 transition-colors font-label-md text-label-md shadow-sm">
<span class="material-symbols-outlined text-[18px]">add</span>
            New Work Order
        </button>
<!-- Navigation Tabs -->
<div class="flex flex-col gap-xs flex-grow overflow-y-auto">
<a class="flex items-center gap-sm px-sm py-sm rounded-lg text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-high dark:hover:bg-on-surface-variant transition-colors group" href="#">
<span class="material-symbols-outlined group-hover:text-primary transition-colors">dashboard</span>
<span class="font-label-md text-label-md">Dashboard</span>
</a>
<a class="flex items-center gap-sm px-sm py-sm rounded-lg text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-high dark:hover:bg-on-surface-variant transition-colors group" href="#">
<span class="material-symbols-outlined group-hover:text-primary transition-colors">handyman</span>
<span class="font-label-md text-label-md">Work Orders</span>
</a>
<a class="flex items-center gap-sm px-sm py-sm rounded-lg text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-high dark:hover:bg-on-surface-variant transition-colors group" href="#">
<span class="material-symbols-outlined group-hover:text-primary transition-colors">inventory_2</span>
<span class="font-label-md text-label-md">Inventory</span>
</a>
<a class="flex items-center gap-sm px-sm py-sm rounded-lg text-primary dark:text-inverse-primary font-bold bg-secondary-fixed dark:bg-secondary-fixed-dim translate-x-1 transition-transform group" href="#">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">group</span>
<span class="font-label-md text-label-md">Customers</span>
</a>
<a class="flex items-center gap-sm px-sm py-sm rounded-lg text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-high dark:hover:bg-on-surface-variant transition-colors group" href="#">
<span class="material-symbols-outlined group-hover:text-primary transition-colors">directions_car</span>
<span class="font-label-md text-label-md">Vehicles</span>
</a>
<a class="flex items-center gap-sm px-sm py-sm rounded-lg text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-high dark:hover:bg-on-surface-variant transition-colors group" href="#">
<span class="material-symbols-outlined group-hover:text-primary transition-colors">analytics</span>
<span class="font-label-md text-label-md">Reports</span>
</a>
</div>
<!-- Footer Tabs -->
<div class="flex flex-col gap-xs mt-auto border-t border-outline-variant dark:border-outline pt-sm">
<a class="flex items-center gap-sm px-sm py-sm rounded-lg text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-high dark:hover:bg-on-surface-variant transition-colors group" href="#">
<span class="material-symbols-outlined group-hover:text-primary transition-colors">settings</span>
<span class="font-label-md text-label-md">Settings</span>
</a>
<a class="flex items-center gap-sm px-sm py-sm rounded-lg text-on-surface-variant dark:text-surface-variant hover:bg-surface-container-high dark:hover:bg-on-surface-variant transition-colors group" href="#">
<span class="material-symbols-outlined group-hover:text-primary transition-colors">logout</span>
<span class="font-label-md text-label-md">Logout</span>
</a>
</div>
</nav>
<!-- Main Content Area -->
<div class="flex-1 flex flex-col h-full overflow-hidden bg-background">
<!-- TopNavBar (Mobile Focus / Secondary Desktop Actions) -->
<header class="flex justify-between items-center w-full px-lg h-16 sticky top-0 z-50 bg-surface dark:bg-inverse-surface border-b border-outline-variant dark:border-outline shrink-0 md:hidden">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-primary cursor-pointer">menu</span>
<span class="font-headline-md text-headline-md font-bold text-primary dark:text-inverse-primary">Bengkel AR</span>
</div>
<div class="flex items-center gap-sm">
<button class="p-xs text-on-surface-variant hover:bg-surface-container-low rounded-full transition-colors">
<span class="material-symbols-outlined">notifications</span>
</button>
<img alt="Administrator Profile" class="w-8 h-8 rounded-full border border-outline-variant object-cover" data-alt="A close up portrait of a professional workshop manager. The lighting is bright and clean, reflecting a modern office environment. The color palette features neutral tones with subtle hints of corporate blue." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDcVPscDLll7K4jgjBMsl-Jw_-y6yV3Qed6se19P0VYgzMosV5hmRrNifyHV7B1Z0zCQ-pE_libwzj3TS-lINYxwUVztcgUFRO92XMCNq0jAU_GTizu7fAwsew0ZOsq92kLuzuJZtE78D9jxxOEH3F5Hmh_oO1ltt_04AdqZDnlBc5gHsS5kbIafSPxb7V60xsmZhDPd7I7GfVWlD6IUcXpGjAwZ66nABImeXoFXnUIiICumeSopdFR1w0hgQtgbaJo1AE-OAnBraWS"/>
</div>
</header>
<!-- Desktop Top App Bar Alternative (Search & Profile) -->
<header class="hidden md:flex justify-between items-center w-full px-margin-desktop h-16 shrink-0 bg-surface border-b border-outline-variant">
<div class="flex-1 max-w-md">
<!-- Global Search placeholder if needed -->
</div>
<div class="flex items-center gap-md">
<button class="p-sm text-on-surface-variant hover:bg-surface-container-low rounded-full transition-colors flex items-center justify-center">
<span class="material-symbols-outlined">notifications</span>
</button>
<button class="p-sm text-on-surface-variant hover:bg-surface-container-low rounded-full transition-colors flex items-center justify-center">
<span class="material-symbols-outlined">help</span>
</button>
<img alt="Administrator Profile" class="w-8 h-8 rounded-full border border-outline-variant object-cover ml-sm cursor-pointer" data-alt="A close up portrait of a professional workshop manager. The lighting is bright and clean, reflecting a modern office environment. The color palette features neutral tones with subtle hints of corporate blue." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCXGsZ8A1ZOhBn4J-yl25q2HGaIxJDAW_8MtXBx_rbuKdyf-9fJ_mhn_tokN2xma_GV4UqLIi0dpoZ4XgsxUaa0sBk3qN_hC_66XRiANh_RGQLYXIKXxXKcX-F6_6Z52O1JsJwLoudCkKlWlR2VPo-o5-_rs7zAJcFJOBcQnteYk5GtKDQ4A26Oy6zNYUFGne6gC8iLYMQj0J1bg79CXJFsIgQSs3xmum67qOIobfkyb0yzgYJdOm0tHL_P-_57VbAjJscDuXSuKW7y"/>
</div>
</header>
<!-- Page Content -->
<main class="flex-1 overflow-y-auto p-margin-mobile md:p-margin-desktop">
<div class="max-w-max-width mx-auto flex flex-col gap-lg">
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-start justify-between gap-md">
<div>
<h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-background mb-xs">Database Pelanggan &amp; Kendaraan</h2>
<p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">Kelola data profil pelanggan dan detail kendaraan yang terdaftar.</p>
</div>
<button class="flex items-center justify-center gap-xs py-sm px-lg bg-primary text-on-primary rounded-full hover:bg-primary/90 transition-colors font-label-md text-label-md shadow-sm shrink-0 h-10 w-full md:w-auto">
<span class="material-symbols-outlined text-[18px]">person_add</span>
                        Tambah Pelanggan Baru
                    </button>
</div>
<!-- Action Bar (Search & Filter) -->
<div class="bg-surface rounded-xl border border-outline-variant p-md flex flex-col md:flex-row gap-md items-center justify-between shadow-sm">
<div class="relative w-full md:w-96">
<span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">search</span>
<input class="w-full pl-[40px] pr-sm py-sm bg-surface-container-lowest border border-outline-variant rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary font-body-md text-body-md text-on-surface transition-colors" placeholder="Cari nama, no. telp, atau plat nomor..." type="text"/>
</div>
<div class="flex gap-sm w-full md:w-auto">
<button class="flex items-center justify-center gap-xs px-md py-sm border border-outline-variant text-on-surface-variant rounded-lg hover:bg-surface-container-low transition-colors font-label-md text-label-md flex-1 md:flex-none">
<span class="material-symbols-outlined text-[18px]">filter_list</span>
                            Filter
                        </button>
<button class="flex items-center justify-center gap-xs px-md py-sm border border-outline-variant text-on-surface-variant rounded-lg hover:bg-surface-container-low transition-colors font-label-md text-label-md flex-1 md:flex-none">
<span class="material-symbols-outlined text-[18px]">download</span>
                            Export
                        </button>
</div>
</div>
<!-- Data Table Card -->
<div class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden flex flex-col">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse min-w-[800px]">
<thead class="bg-surface-container-low border-b border-outline-variant">
<tr>
<th class="py-sm px-md font-label-md text-label-md text-on-surface-variant w-[25%]">Nama Pelanggan</th>
<th class="py-sm px-md font-label-md text-label-md text-on-surface-variant w-[30%]">Daftar Kendaraan</th>
<th class="py-sm px-md font-label-md text-label-md text-on-surface-variant w-[10%] text-center">Total Servis</th>
<th class="py-sm px-md font-label-md text-label-md text-on-surface-variant w-[15%]">Terakhir Datang</th>
<th class="py-sm px-md font-label-md text-label-md text-on-surface-variant w-[10%]">Status</th>
<th class="py-sm px-md font-label-md text-label-md text-on-surface-variant w-[10%] text-right">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant font-body-md text-body-md">
<!-- Row 1 -->
<tr class="hover:bg-surface-container-lowest transition-colors group">
<td class="py-sm px-md align-top">
<div class="font-medium text-on-surface">Budi Santoso</div>
<div class="text-on-surface-variant text-[13px] mt-xs">0812-3456-7890</div>
</td>
<td class="py-sm px-md align-top">
<div class="flex flex-col gap-xs">
<div class="flex items-center gap-xs bg-surface-container-low w-fit px-sm py-[2px] rounded border border-outline-variant">
<span class="font-tabular-nums text-tabular-nums text-on-surface">B 1234 ABC</span>
<span class="text-outline text-[12px]">- Honda Vario</span>
</div>
<div class="flex items-center gap-xs bg-surface-container-low w-fit px-sm py-[2px] rounded border border-outline-variant">
<span class="font-tabular-nums text-tabular-nums text-on-surface">D 5678 DEF</span>
<span class="text-outline text-[12px]">- Toyota Avanza</span>
</div>
</div>
</td>
<td class="py-sm px-md align-top text-center font-tabular-nums text-tabular-nums text-on-surface">12</td>
<td class="py-sm px-md align-top font-tabular-nums text-tabular-nums text-on-surface">15 Okt 2023</td>
<td class="py-sm px-md align-top">
<span class="inline-flex items-center px-sm py-[2px] rounded-full text-[12px] font-medium bg-secondary-container text-on-secondary-container">Loyal</span>
</td>
<td class="py-sm px-md align-top text-right">
<div class="flex items-center justify-end gap-xs opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-xs text-on-surface-variant hover:text-primary hover:bg-surface-container-low rounded transition-colors" title="Edit">
<span class="material-symbols-outlined text-[20px]">edit</span>
</button>
<button class="p-xs text-on-surface-variant hover:text-primary hover:bg-surface-container-low rounded transition-colors" title="View Details">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</button>
<button class="p-xs text-on-surface-variant hover:text-primary hover:bg-surface-container-low rounded transition-colors" title="New Order">
<span class="material-symbols-outlined text-[20px]">add_circle</span>
</button>
</div>
</td>
</tr>
<!-- Row 2 -->
<tr class="hover:bg-surface-container-lowest transition-colors group bg-surface">
<td class="py-sm px-md align-top">
<div class="font-medium text-on-surface">Siti Aminah</div>
<div class="text-on-surface-variant text-[13px] mt-xs">0856-7890-1234</div>
</td>
<td class="py-sm px-md align-top">
<div class="flex flex-col gap-xs">
<div class="flex items-center gap-xs bg-surface-container-low w-fit px-sm py-[2px] rounded border border-outline-variant">
<span class="font-tabular-nums text-tabular-nums text-on-surface">N 9012 GHI</span>
<span class="text-outline text-[12px]">- Yamaha NMAX</span>
</div>
</div>
</td>
<td class="py-sm px-md align-top text-center font-tabular-nums text-tabular-nums text-on-surface">3</td>
<td class="py-sm px-md align-top font-tabular-nums text-tabular-nums text-on-surface">02 Nov 2023</td>
<td class="py-sm px-md align-top">
<span class="inline-flex items-center px-sm py-[2px] rounded-full text-[12px] font-medium bg-tertiary-container/20 text-tertiary-container">Aktif</span>
</td>
<td class="py-sm px-md align-top text-right">
<div class="flex items-center justify-end gap-xs opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-xs text-on-surface-variant hover:text-primary hover:bg-surface-container-low rounded transition-colors" title="Edit">
<span class="material-symbols-outlined text-[20px]">edit</span>
</button>
<button class="p-xs text-on-surface-variant hover:text-primary hover:bg-surface-container-low rounded transition-colors" title="View Details">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</button>
<button class="p-xs text-on-surface-variant hover:text-primary hover:bg-surface-container-low rounded transition-colors" title="New Order">
<span class="material-symbols-outlined text-[20px]">add_circle</span>
</button>
</div>
</td>
</tr>
<!-- Row 3 -->
<tr class="hover:bg-surface-container-lowest transition-colors group">
<td class="py-sm px-md align-top">
<div class="font-medium text-on-surface">Andi Setiawan</div>
<div class="text-on-surface-variant text-[13px] mt-xs">0811-2233-4455</div>
</td>
<td class="py-sm px-md align-top">
<div class="flex flex-col gap-xs">
<div class="flex items-center gap-xs bg-surface-container-low w-fit px-sm py-[2px] rounded border border-outline-variant">
<span class="font-tabular-nums text-tabular-nums text-on-surface">L 3456 JKL</span>
<span class="text-outline text-[12px]">- Suzuki Ertiga</span>
</div>
</div>
</td>
<td class="py-sm px-md align-top text-center font-tabular-nums text-tabular-nums text-on-surface">1</td>
<td class="py-sm px-md align-top font-tabular-nums text-tabular-nums text-on-surface">20 Jan 2023</td>
<td class="py-sm px-md align-top">
<span class="inline-flex items-center px-sm py-[2px] rounded-full text-[12px] font-medium bg-surface-variant text-on-surface-variant">Pasif</span>
</td>
<td class="py-sm px-md align-top text-right">
<div class="flex items-center justify-end gap-xs opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-xs text-on-surface-variant hover:text-primary hover:bg-surface-container-low rounded transition-colors" title="Edit">
<span class="material-symbols-outlined text-[20px]">edit</span>
</button>
<button class="p-xs text-on-surface-variant hover:text-primary hover:bg-surface-container-low rounded transition-colors" title="View Details">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</button>
<button class="p-xs text-on-surface-variant hover:text-primary hover:bg-surface-container-low rounded transition-colors" title="New Order">
<span class="material-symbols-outlined text-[20px]">add_circle</span>
</button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
<!-- Pagination -->
<div class="bg-surface border-t border-outline-variant px-md py-sm flex items-center justify-between">
<span class="font-body-md text-body-md text-on-surface-variant text-[13px]">Menampilkan 1-3 dari 124 pelanggan</span>
<div class="flex items-center gap-xs">
<button class="p-xs text-on-surface-variant hover:bg-surface-container-low rounded border border-transparent hover:border-outline-variant transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled="">
<span class="material-symbols-outlined text-[20px]">chevron_left</span>
</button>
<button class="w-8 h-8 flex items-center justify-center font-tabular-nums text-tabular-nums bg-primary text-on-primary rounded text-[13px]">1</button>
<button class="w-8 h-8 flex items-center justify-center font-tabular-nums text-tabular-nums text-on-surface-variant hover:bg-surface-container-low rounded transition-colors text-[13px]">2</button>
<button class="w-8 h-8 flex items-center justify-center font-tabular-nums text-tabular-nums text-on-surface-variant hover:bg-surface-container-low rounded transition-colors text-[13px]">3</button>
<span class="text-on-surface-variant">...</span>
<button class="w-8 h-8 flex items-center justify-center font-tabular-nums text-tabular-nums text-on-surface-variant hover:bg-surface-container-low rounded transition-colors text-[13px]">12</button>
<button class="p-xs text-on-surface-variant hover:bg-surface-container-low rounded border border-transparent hover:border-outline-variant transition-colors">
<span class="material-symbols-outlined text-[20px]">chevron_right</span>
</button>
</div>
</div>
</div>
</div>
</main>
</div>
</body></html>