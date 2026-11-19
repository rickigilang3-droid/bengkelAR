@extends('dashboard.admin.layouts.app')

@section('title', 'WorkshopPro - Service History')

@section('content')
<div class="flex flex-col gap-lg">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-md">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Riwayat Servis</h2>
            <p class="font-body-md text-body-md text-on-surface-variant mt-xs">Kelola dan pantau seluruh data transaksi servis pelanggan.</p>
        </div>
        <div class="flex flex-wrap gap-sm">
            <a href="{{ route('admin.history.export', array_merge(request()->query(), ['format' => 'csv'])) }}" class="inline-flex items-center gap-xs rounded-xl bg-surface-container-highest px-md py-sm text-label-md font-label-md text-on-surface hover:bg-surface-container-low transition-colors">
                <span class="material-symbols-outlined">file_download</span>
                Export CSV
            </a>
            <a href="{{ route('admin.queue.index') }}" class="inline-flex items-center gap-xs rounded-xl bg-primary px-md py-sm text-label-md font-label-md text-on-primary hover:bg-primary-container transition-colors">
                <span class="material-symbols-outlined">add</span>
                New Service
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
        <div class="bg-surface-container-lowest border border-outline-variant p-lg rounded-xl shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-lg text-primary/10 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined !text-6xl">calendar_month</span>
            </div>
            <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-widest mb-base">Servis Bulan Ini</p>
            <p class="font-headline-lg text-headline-lg text-on-surface">{{ number_format($totalServices) }}</p>
            <div class="mt-md h-1 w-24 bg-primary rounded-full"></div>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant p-lg rounded-xl shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-lg text-primary/10 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined !text-6xl">payments</span>
            </div>
            <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-widest mb-base">Total Pendapatan</p>
            <p class="font-headline-lg text-headline-lg text-on-surface">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
            <div class="mt-md h-1 w-24 bg-primary rounded-full"></div>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant p-lg rounded-xl shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-lg text-primary/10 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined !text-6xl">military_tech</span>
            </div>
            <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-widest mb-base">Tingkat Penyelesaian</p>
            <p class="font-headline-lg text-headline-lg text-on-surface">{{ $completionRate }}%</p>
            <p class="font-body-md text-body-md text-on-surface-variant mt-sm">Rata-rata durasi {{ $avgDuration }} jam</p>
        </div>
    </div>

    <form action="{{ route('admin.history.index') }}" method="GET" class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md mb-lg flex flex-wrap items-end gap-md">
        <div class="flex-1 min-w-[200px]">
            <label class="block font-label-md text-[10px] text-on-surface-variant uppercase mb-xs ml-base">Rentang Tanggal</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">calendar_today</span>
                <input name="start_date" class="w-full bg-surface-container-low border border-outline-variant rounded-lg pl-10 pr-4 py-2 font-body-md text-body-md" type="date" value="{{ request('start_date') }}"/>
            </div>
        </div>
        <div class="flex-1 min-w-[200px]">
            <label class="block font-label-md text-[10px] text-on-surface-variant uppercase mb-xs ml-base">Sampai Tanggal</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">calendar_today</span>
                <input name="end_date" class="w-full bg-surface-container-low border border-outline-variant rounded-lg pl-10 pr-4 py-2 font-body-md text-body-md" type="date" value="{{ request('end_date') }}"/>
            </div>
        </div>
        <div class="flex-1 min-w-[180px]">
            <label class="block font-label-md text-[10px] text-on-surface-variant uppercase mb-xs ml-base">Tipe Layanan</label>
            <select name="service_type" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 font-body-md text-body-md">
                <option value="">Semua Layanan</option>
                <option value="Ganti Oli" @if(request('service_type') === 'Ganti Oli') selected @endif>Ganti Oli</option>
                <option value="Servis Berkala" @if(request('service_type') === 'Servis Berkala') selected @endif>Servis Berkala</option>
                <option value="Perbaikan Berat" @if(request('service_type') === 'Perbaikan Berat') selected @endif>Perbaikan Berat</option>
            </select>
        </div>
        <div class="flex-1 min-w-[240px]">
            <label class="block font-label-md text-[10px] text-on-surface-variant uppercase mb-xs ml-base">Cari Pelanggan / Plat</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">person_search</span>
                <input name="search" class="w-full bg-surface-container-low border border-outline-variant rounded-lg pl-10 pr-4 py-2 font-body-md text-body-md" placeholder="Nama atau B 1234 ABC..." type="text" value="{{ request('search') }}"/>
            </div>
        </div>
        <div class="pt-4">
            <button type="submit" class="bg-primary text-on-primary py-2 px-lg rounded-lg font-label-md text-label-md hover:shadow-lg transition-all flex items-center gap-sm">
                <span class="material-symbols-outlined">filter_list</span>
                Terapkan Filter
            </button>
        </div>
    </form>

    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-sticky-header">
                <thead>
                    <tr class="border-b border-outline-variant">
                        <th class="px-lg py-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Tanggal</th>
                        <th class="px-lg py-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Plat Nomor</th>
                        <th class="px-lg py-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Pelanggan</th>
                        <th class="px-lg py-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Motor</th>
                        <th class="px-lg py-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Layanan</th>
                        <th class="px-lg py-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Mekanik</th>
                        <th class="px-lg py-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider text-right">Total Biaya</th>
                        <th class="px-lg py-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Status</th>
                        <th class="px-lg py-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($histories as $history)
                        <tr class="hover:bg-surface-container-low transition-colors group">
                            <td class="px-lg py-md font-tabular-nums text-tabular-nums">{{ $history->completed_at ? $history->completed_at->format('d M Y') : $history->created_at->format('d M Y') }}</td>
                            <td class="px-lg py-md"><span class="bg-inverse-surface text-inverse-on-surface px-2 py-1 rounded-lg font-bold text-xs">{{ $history->vehicle->plate_number }}</span></td>
                            <td class="px-lg py-md font-body-md text-body-md">{{ $history->user->name }}</td>
                            <td class="px-lg py-md font-body-md text-body-md">{{ $history->vehicle->brand }} {{ $history->vehicle->model }}</td>
                            <td class="px-lg py-md">
                                <span class="bg-secondary-container text-on-secondary-fixed-variant px-sm py-xs rounded-full text-[11px] font-bold">{{ $history->service_type }}</span>
                            </td>
                            <td class="px-lg py-md font-body-md text-body-md">Tim Servis</td>
                            <td class="px-lg py-md font-tabular-nums text-tabular-nums text-right font-bold">Rp {{ number_format($history->total_cost, 0, ',', '.') }}</td>
                            <td class="px-lg py-md">
                                <span class="flex items-center gap-1 text-on-tertiary-fixed-variant text-[11px] font-bold">
                                    <span class="w-1.5 h-1.5 bg-tertiary-fixed-dim rounded-full"></span>
                                    {{ ucfirst($history->payment_status) }}
                                </span>
                            </td>
                            <td class="px-lg py-md">
                                <div class="flex items-center gap-sm">
                                    <a href="{{ route('admin.history.show', $history->id) }}" class="p-1.5 text-on-surface-variant hover:text-primary transition-colors" title="Detail">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.history.export', ['format' => 'csv', 'history_id' => $history->id]) }}" class="p-1.5 text-on-surface-variant hover:text-primary transition-colors" title="Cetak Invoice">
                                        <span class="material-symbols-outlined">print</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-lg py-md text-center text-on-surface-variant">Tidak ada riwayat servis yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-lg py-md flex items-center justify-between bg-surface-container-low border-t border-outline-variant">
            <p class="font-body-md text-body-md text-on-surface-variant">Menampilkan {{ $histories->firstItem() ?? 0 }} - {{ $histories->lastItem() ?? 0 }} dari {{ $histories->total() }} data</p>
            <div class="flex items-center gap-xs">
                {{ $histories->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
