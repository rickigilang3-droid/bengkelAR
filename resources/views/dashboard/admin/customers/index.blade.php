@extends('dashboard.admin.layouts.app')

@section('title', 'Bengkel AR - Customers')
@section('title', $title ?? 'Manajemen Pelanggan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-on-surface">Pelanggan Terdaftar</h2>
            <p class="text-sm text-secondary mt-1">Kelola data pelanggan dan pantau jumlah kendaraan mereka.</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-surface-container-lowest rounded-2xl p-5 border border-outline-variant/40 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined">group</span>
            </div>
            <div>
                <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-1">Total Pelanggan</p>
                <p class="text-2xl font-black text-on-surface">{{ $totalCustomers }}</p>
            </div>
        </div>
        <div class="bg-surface-container-lowest rounded-2xl p-5 border border-outline-variant/40 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined">how_to_reg</span>
            </div>
            <div>
                <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-1">Pelanggan Aktif</p>
                <p class="text-2xl font-black text-on-surface">{{ $activeCustomers }}</p>
            </div>
        </div>
        <div class="bg-surface-container-lowest rounded-2xl p-5 border border-outline-variant/40 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined">two_wheeler</span>
            </div>
            <div>
                <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-1">Total Kendaraan</p>
                <p class="text-2xl font-black text-on-surface">{{ $totalVehicles }}</p>
            </div>
        </div>
    </div>

    <!-- Search Bar & Filters -->
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/40 shadow-sm p-4">
        <form action="{{ route('admin.customers.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-4">
            <div class="relative flex-1 w-full">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau no. telepon..." class="w-full pl-10 pr-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <select name="status" class="bg-surface-container-lowest border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                <button type="submit" class="px-5 py-2.5 bg-surface-container-low hover:bg-surface-container text-on-surface font-semibold text-sm rounded-xl border border-outline-variant/50 transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.customers.index') }}" class="px-3 py-2.5 text-error hover:bg-error/10 rounded-xl text-sm font-semibold transition-colors" title="Reset Filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/40 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low/50 border-b border-outline-variant/40">
                        <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider">Nama Pelanggan</th>
                        <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider">Kontak</th>
                        <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider">Kendaraan</th>
                        <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider">Bergabung Pada</th>
                        <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @forelse ($customers as $customer)
                        <tr class="hover:bg-surface-container-low/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm shrink-0">
                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-on-surface">{{ $customer->name }}</div>
                                        <div class="text-xs text-secondary mt-0.5">{{ $customer->address ?? 'Belum ada alamat' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-on-surface">{{ $customer->phone ?? '-' }}</div>
                                <div class="text-xs text-secondary mt-0.5">{{ $customer->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-md bg-surface-container-low border border-outline-variant/50 text-xs font-bold text-on-surface">
                                    {{ $customer->vehicles->count() }} Motor
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">
                                {{ $customer->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('admin.customers.show', $customer->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-secondary hover:text-primary hover:bg-primary/10 transition-colors" title="Lihat Detail">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-secondary">
                                    <span class="material-symbols-outlined text-4xl mb-3 text-outline-variant">person_off</span>
                                    <p class="text-sm font-medium text-on-surface">Tidak ada pelanggan ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($customers->hasPages())
            <div class="px-6 py-4 border-t border-outline-variant/30 bg-surface-container-low/30">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
