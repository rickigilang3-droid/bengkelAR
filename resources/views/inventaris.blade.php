<!DOCTYPE html>
<html class="light" lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Cek Stok Part - Bengkel AR</title>
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
                      "error-container": "#ffdad6",
                      "surface-tint": "#3755c3",
                      "surface-bright": "#f7f9fb",
                      "on-secondary-fixed-variant": "#38485d",
                      "on-secondary-fixed": "#0b1c30",
                      "primary-fixed": "#dde1ff",
                      "outline": "#757684",
                      "surface-dim": "#d8dadc",
                      "on-primary-fixed-variant": "#173bab",
                      "on-tertiary-fixed-variant": "#005236",
                      "inverse-on-surface": "#eff1f3",
                      "on-primary-fixed": "#001453",
                      "on-primary-container": "#a8b8ff",
                      "inverse-primary": "#b8c4ff",
                      "background": "#f7f9fb",
                      "tertiary": "#003d27",
                      "inverse-surface": "#2d3133",
                      "outline-variant": "#c4c5d5",
                      "surface-container-highest": "#e0e3e5",
                      "surface-container": "#eceef0",
                      "primary": "#00288e",
                      "on-background": "#191c1e",
                      "secondary-fixed": "#d3e4fe",
                      "secondary": "#505f76",
                      "on-secondary-container": "#54647a",
                      "on-secondary": "#ffffff",
                      "secondary-fixed-dim": "#b7c8e1",
                      "on-surface-variant": "#444653",
                      "surface-variant": "#e0e3e5",
                      "on-error": "#ffffff",
                      "surface-container-high": "#e6e8ea",
                      "on-tertiary-container": "#3fd298",
                      "tertiary-container": "#00563a",
                      "primary-fixed-dim": "#b8c4ff",
                      "on-primary": "#ffffff",
                      "primary-container": "#1e40af",
                      "surface-container-lowest": "#ffffff",
                      "tertiary-fixed": "#6ffbbe",
                      "tertiary-fixed-dim": "#4edea3",
                      "on-tertiary": "#ffffff",
                      "on-error-container": "#93000a",
                      "error": "#ba1a1a",
                      "on-surface": "#191c1e",
                      "on-tertiary-fixed": "#002113",
                      "surface-container-low": "#f2f4f6",
                      "surface": "#f7f9fb",
                      "secondary-container": "#d0e1fb"
              },
              "borderRadius": {
                      "DEFAULT": "0.125rem",
                      "lg": "0.25rem",
                      "xl": "0.5rem",
                      "full": "0.75rem"
              },
              "spacing": {
                      "base": "4px",
                      "margin-mobile": "16px",
                      "xs": "4px",
                      "xl": "32px",
                      "gutter": "24px",
                      "margin-desktop": "32px",
                      "sm": "8px",
                      "max-width": "1440px",
                      "lg": "24px",
                      "md": "16px"
              },
              "fontFamily": {
                      "label-md": ["Inter"],
                      "body-lg": ["Inter"],
                      "tabular-nums": ["Inter"],
                      "headline-lg-mobile": ["Inter"],
                      "headline-md": ["Inter"],
                      "headline-lg": ["Inter"],
                      "body-md": ["Inter"]
              },
              "fontSize": {
                      "label-md": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}],
                      "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                      "tabular-nums": ["14px", {"lineHeight": "20px", "fontWeight": "500"}],
                      "headline-lg-mobile": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                      "headline-md": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                      "headline-lg": ["30px", {"lineHeight": "38px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                      "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}]
              }
            }
          }
        }
    </script>
<style>
    body {
      min-height: max(884px, 100dvh);
    }
  </style>
  </head>
<body class="bg-background text-on-background min-h-screen font-body-md pb-24">
<!-- TopAppBar -->
<header class="bg-surface fixed top-0 w-full z-50 border-b border-outline-variant flat no shadows transition-colors duration-200">
<div class="flex items-center justify-between px-margin-mobile h-16 w-full max-w-max-width mx-auto">
<div class="flex items-center gap-3">
<a href="{{ route('dashboard') }}" class="text-primary hover:bg-surface-container rounded-full p-2 flex items-center justify-center transition-colors duration-200">
<span class="material-symbols-outlined">arrow_back</span>
</a>
<h1 class="font-headline-md text-headline-md font-bold text-primary">Bengkel AR</h1>
</div>
<a href="{{ route('profil') }}" class="hover:bg-surface-container rounded-full p-1 flex items-center justify-center transition-colors duration-200">
<div class="w-8 h-8 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-bold text-xs">
{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
</div>
</a>
</div>
</header>
<!-- Main Content -->
<main class="pt-20 px-margin-mobile max-w-max-width mx-auto">
<!-- Page Header -->
<div class="mb-6">
<h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface mb-2">Cek Stok Suku Cadang</h2>
<p class="font-body-md text-body-md text-on-surface-variant">Temukan ketersediaan dan harga komponen untuk kendaraan Anda.</p>
</div>
<!-- Search & Category Form -->
<form method="GET" action="{{ route('inventaris') }}" class="mb-6 space-y-4">
    <!-- Search Bar -->
    <div class="relative">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
        <input name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none font-body-md text-body-md text-on-surface placeholder:text-outline shadow-sm text-xs" placeholder="Cari busi, oli, v-belt, kampas rem, roller..." type="text"/>
    </div>
    <!-- Categories -->
    <div class="flex overflow-x-auto space-x-2 pb-2 scrollbar-none">
        @foreach($categories as $cat)
            <button name="category" value="{{ $cat }}" class="px-4 py-2 rounded-full font-label-md text-label-md whitespace-nowrap border text-xs transition-colors {{ request('category', 'Semua') === $cat ? 'bg-primary text-on-primary border-primary font-bold' : 'bg-surface border-outline-variant text-on-surface-variant hover:bg-surface-variant' }}" type="submit">
                {{ $cat }}
            </button>
        @endforeach
    </div>
</form>
<!-- Product List -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($inventories as $inv)
    <div class="p-4 bg-surface rounded-2xl border border-outline-variant shadow-sm hover:border-primary/50 transition-all flex flex-col justify-between group">
        <div>
            <!-- Image & Supplier Badge -->
            <div class="relative w-full h-44 mb-3 rounded-xl overflow-hidden bg-slate-900 flex items-center justify-center p-2">
                @if($inv->image_url)
                    <img src="{{ $inv->image_url }}" alt="{{ $inv->name }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                @else
                    <span class="material-symbols-outlined text-slate-500 text-5xl">build_circle</span>
                @endif
                <span class="absolute top-2 left-2 bg-slate-900/80 backdrop-blur text-amber-400 border border-amber-500/30 text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase tracking-wider shadow">
                    {{ $inv->supplier ?? 'Pabrikan Original' }}
                </span>
            </div>

            <!-- Title & Code -->
            <div class="flex items-start justify-between gap-2 mb-1">
                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-200 uppercase tracking-wider">{{ $inv->category }}</span>
                <span class="text-[10px] text-slate-400 font-mono font-bold">{{ $inv->part_code }}</span>
            </div>
            <h3 class="font-bold text-sm text-slate-900 group-hover:text-primary transition-colors leading-snug mb-1">{{ $inv->name }}</h3>
            <p class="text-xs text-slate-500 line-clamp-2 mb-3 leading-relaxed">{{ $inv->description ?? 'Komponen suku cadang berkualitas tinggi untuk performa motor optimal.' }}</p>
        </div>

        <div class="pt-3 border-t border-slate-100 flex items-center justify-between mt-auto">
            <div>
                <span class="text-[10px] text-slate-400 uppercase font-bold block">Harga Eceran</span>
                <span class="text-base font-extrabold text-slate-900">Rp {{ number_format($inv->selling_price, 0, ',', '.') }}</span>
            </div>

            <div class="text-right">
                @if($inv->stock <= 0)
                    <span class="px-3 py-1.5 rounded-full bg-rose-100 text-rose-800 font-extrabold text-[11px] uppercase tracking-wider block">Stok Habis</span>
                @elseif($inv->stock <= $inv->min_stock)
                    <span class="px-3 py-1.5 rounded-full bg-amber-100 text-amber-800 font-extrabold text-[11px] uppercase tracking-wider block">Sisa {{ $inv->stock }} {{ $inv->unit }}</span>
                @else
                    <span class="px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-800 font-extrabold text-[11px] uppercase tracking-wider block">Tersedia ({{ $inv->stock }} {{ $inv->unit }})</span>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="p-8 text-center bg-surface-container-lowest border border-outline-variant rounded-xl col-span-full">
        <span class="material-symbols-outlined text-slate-400 text-4xl mb-2">search_off</span>
        <h4 class="font-bold text-sm text-slate-700">Sparepart Tidak Ditemukan</h4>
        <p class="text-xs text-slate-500 mt-1">Coba kata kunci lain atau pilih kategori komponen yang berbeda.</p>
    </div>
    @endforelse
</div>
</main>
<!-- BottomNavBar -->
<nav class="fixed bottom-0 w-full z-50 pb-safe border-t border-outline-variant bg-surface shadow-lg flex justify-around items-center h-20 px-base">
    <a aria-label="Beranda" href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center text-on-surface-variant px-3 py-1 transition-all active:scale-90 duration-200 group">
        <span class="material-symbols-outlined mb-0.5 text-[20px] group-hover:text-primary" style="font-variation-settings: 'FILL' 0;">home</span>
        <span class="font-label-md text-label-md text-[10px] group-hover:text-primary">Beranda</span>
    </a>
    <a aria-label="Servis" href="{{ route('booking') }}" class="flex flex-col items-center justify-center text-on-surface-variant px-3 py-1 transition-all active:scale-90 duration-200 group">
        <span class="material-symbols-outlined mb-0.5 text-[20px] group-hover:text-primary" style="font-variation-settings: 'FILL' 0;">build_circle</span>
        <span class="font-label-md text-label-md text-[10px] group-hover:text-primary">Servis</span>
    </a>
    <a aria-label="Stok Part" href="{{ route('inventaris') }}" class="flex flex-col items-center justify-center bg-primary-container text-on-primary-container rounded-full px-3 py-1 transition-all active:scale-90 duration-200">
        <span class="material-symbols-outlined mb-0.5 text-[20px]" style="font-variation-settings: 'FILL' 1;">inventory_2</span>
        <span class="font-label-md text-label-md text-[10px]">Stok Part</span>
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
</body></html>
