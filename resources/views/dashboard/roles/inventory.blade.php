<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-indigo-600">Portal pelanggan <span class="mx-1 text-slate-300">/</span> Sparepart</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Sparepart tersedia</h1>
            <p class="mt-1 text-sm text-slate-500">Lihat komponen yang tersedia di Bengkel AR. Harga dapat dikonfirmasi saat konsultasi servis.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-7 sm:px-6 lg:px-8">
        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <form method="GET" action="{{ route('customer.inventory') }}" class="grid gap-3 md:grid-cols-[minmax(0,1fr)_240px_auto_auto]">
                <label class="sr-only" for="search">Cari sparepart</label>
                <input id="search" name="search" type="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari nama, kategori, atau kode sparepart..." class="min-h-11 rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <label class="sr-only" for="category">Kategori sparepart</label>
                <select id="category" name="category" class="min-h-11 rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
                <button type="submit" class="min-h-11 rounded-xl bg-indigo-700 px-5 py-2 text-sm font-bold text-white hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Cari</button>
                @if (($filters['search'] ?? '') !== '' || ($filters['category'] ?? '') !== '')
                    <a href="{{ route('customer.inventory') }}" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">Reset</a>
                @endif
            </form>
        </section>

        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Katalog Bengkel AR</p>
                <h2 class="mt-1 text-xl font-bold text-slate-900">{{ $inventories->total() }} sparepart tersedia</h2>
            </div>
            <a href="{{ route('customer.bookings') }}" class="hidden min-h-10 items-center rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-bold text-indigo-700 hover:bg-indigo-100 sm:inline-flex">Tanya saat booking</a>
        </div>

        @if ($inventories->isEmpty())
            <section class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center">
                <span class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 text-slate-500"><span class="material-symbols-outlined" aria-hidden="true">inventory_2</span></span>
                <h3 class="mt-4 font-bold text-slate-900">Sparepart tidak ditemukan</h3>
                <p class="mt-1 text-sm text-slate-500">Coba kata kunci atau kategori lain. Ketersediaan dapat berubah sewaktu-waktu.</p>
            </section>
        @else
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($inventories as $inventory)
                    <article class="flex flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-md">
                        <div class="flex items-start justify-between gap-3">
                            <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-700">{{ $inventory->category }}</span>
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Tersedia</span>
                        </div>
                        <h3 class="mt-4 text-lg font-bold text-slate-900">{{ $inventory->name }}</h3>
                        <p class="mt-1 text-xs font-medium text-slate-400">Kode {{ $inventory->part_code }}</p>
                        @if ($inventory->description)
                            <p class="mt-3 flex-1 text-sm leading-6 text-slate-600">{{ $inventory->description }}</p>
                        @else
                            <div class="flex-1"></div>
                        @endif
                        <div class="mt-5 flex items-end justify-between gap-3 border-t border-slate-100 pt-4">
                            <div>
                                <p class="text-xs font-medium text-slate-400">Harga jual</p>
                                <p class="mt-1 text-lg font-extrabold tabular-nums text-slate-900">Rp{{ number_format((float) $inventory->selling_price, 0, ',', '.') }}<span class="ml-1 text-xs font-medium text-slate-500">/ {{ $inventory->unit }}</span></p>
                            </div>
                            <p class="text-right text-xs font-semibold text-slate-500">Stok<br><span class="text-sm text-slate-800">{{ $inventory->stock }}</span></p>
                        </div>
                    </article>
                @endforeach
            </div>
            <div>{{ $inventories->links() }}</div>
        @endif
    </div>
</x-app-layout>
