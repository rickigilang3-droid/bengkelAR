@extends('dashboard.admin.layouts.app')

@section('title', 'Customer Profile - Bengkel AR')

@section('content')
@php $active = 'customers'; @endphp

<div class="flex flex-col gap-lg">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-md mb-lg">
        <div>
            <div class="flex items-center gap-xs text-on-surface-variant text-label-md font-label-md mb-xs">
                <a class="hover:text-primary transition-colors" href="{{ route('admin.customers.index') }}">Customers</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-on-background">Customer Profile</span>
            </div>
            <h2 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg-mobile md:font-headline-lg text-on-background">Profil Pelanggan</h2>
        </div>
        <div class="flex flex-wrap gap-sm">
            <a href="{{ route('admin.customers.index') }}" class="bg-surface-container-lowest border border-outline-variant text-secondary hover:bg-surface-container-low transition-colors py-sm px-md rounded-xl flex items-center justify-center gap-sm text-label-md font-label-md">
                <span class="material-symbols-outlined text-[18px]">edit</span>
                Edit Profile
            </a>
            <a href="{{ route('admin.queue.index') }}" class="bg-primary text-on-primary hover:bg-surface-tint transition-colors py-sm px-md rounded-xl flex items-center justify-center gap-sm text-label-md font-label-md shadow-sm">
                <span class="material-symbols-outlined text-[18px]">post_add</span>
                Create Work Order
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
        <!-- Left Column -->
        <div class="flex flex-col gap-lg lg:col-span-1">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col items-center text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-primary"></div>
                <div class="w-24 h-24 rounded-full border-4 border-surface-container-lowest bg-surface-container-high overflow-hidden shadow-sm mb-md mt-sm">
                    <img alt="{{ $customer->name }}" class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=200&q=80" />
                </div>
                <h3 class="text-headline-md font-headline-md text-on-background mb-xs">{{ $customer->name }}</h3>
                <div class="inline-flex items-center gap-xs px-sm py-xs rounded-full bg-secondary-container text-on-secondary-container text-label-md font-label-md mb-lg">
                    <span class="material-symbols-outlined text-[14px]">star</span>
                    Loyal Customer
                </div>
                <div class="w-full flex flex-col gap-md text-left">
                    <div class="flex items-start gap-sm">
                        <span class="material-symbols-outlined text-on-surface-variant text-[20px]">phone_iphone</span>
                        <div>
                            <p class="text-label-md font-label-md text-on-surface-variant">Phone Number</p>
                            <p class="text-body-md font-body-md text-on-background">{{ $customer->phone }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-sm">
                        <span class="material-symbols-outlined text-on-surface-variant text-[20px]">mail</span>
                        <div>
                            <p class="text-label-md font-label-md text-on-surface-variant">Email</p>
                            <p class="text-body-md font-body-md text-on-background">{{ $customer->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-sm">
                        <span class="material-symbols-outlined text-on-surface-variant text-[20px]">location_on</span>
                        <div>
                            <p class="text-label-md font-label-md text-on-surface-variant">Address</p>
                            <p class="text-body-md font-body-md text-on-background">{{ $customer->address ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg grid grid-cols-2 gap-md">
                <div class="flex flex-col gap-xs p-md bg-surface-container-low rounded-lg">
                    <span class="material-symbols-outlined text-primary mb-xs">build</span>
                    <p class="text-label-md font-label-md text-on-surface-variant">Total Servis</p>
                    <p class="text-headline-md font-headline-md text-on-background">{{ $customer->serviceHistories->count() }}</p>
                </div>
                <div class="flex flex-col gap-xs p-md bg-surface-container-low rounded-lg">
                    <span class="material-symbols-outlined text-primary mb-xs">payments</span>
                    <p class="text-label-md font-label-md text-on-surface-variant">Total Biaya</p>
                    <p class="text-headline-md font-headline-md text-on-background tabular-nums">Rp {{ number_format($customer->serviceHistories->sum('total_cost'), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="flex flex-col gap-lg lg:col-span-2">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden flex flex-col">
                <div class="px-lg py-md border-b border-outline-variant flex justify-between items-center bg-surface-bright">
                    <div class="flex items-center gap-sm">
                        <span class="material-symbols-outlined text-primary">two_wheeler</span>
                        <h3 class="text-body-lg font-body-lg font-semibold text-on-background">Kendaraan Terdaftar Pelanggan</h3>
                    </div>
                    <button onclick="document.getElementById('admin-add-vehicle-modal').classList.toggle('hidden')" class="px-3 py-1.5 bg-primary text-white text-xs font-bold rounded-lg flex items-center gap-1 shadow hover:bg-primary/90">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        Tambah Kendaraan
                    </button>
                </div>

                <!-- Admin Modal Form Tambah Motor -->
                <div id="admin-add-vehicle-modal" class="hidden p-4 bg-slate-50 border-b border-outline-variant">
                    <h4 class="font-bold text-xs text-slate-800 mb-2">Form Tambah Motor untuk {{ $customer->name }}</h4>
                    <form action="{{ route('admin.vehicles.store', $customer->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 mb-1">Merek</label>
                                <input type="text" name="brand" required placeholder="Honda / Yamaha" class="w-full text-xs p-2 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 mb-1">Model / Tipe</label>
                                <input type="text" name="model" required placeholder="Vario 160 / NMAX" class="w-full text-xs p-2 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 mb-1">Plat Polisi</label>
                                <input type="text" name="plate_number" required placeholder="B 1234 XYZ" class="w-full text-xs p-2 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-600 mb-1">Tahun</label>
                                <input type="number" name="year" value="2023" class="w-full text-xs p-2 border border-slate-300 rounded-lg">
                            </div>
                        </div>
                        <div class="flex justify-end gap-2 pt-1">
                            <button type="button" onclick="document.getElementById('admin-add-vehicle-modal').classList.add('hidden')" class="px-3 py-1.5 bg-slate-200 text-slate-700 text-xs font-bold rounded-lg">Batal</button>
                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow">Simpan Motor</button>
                        </div>
                    </form>
                </div>

                <div class="p-lg grid grid-cols-1 sm:grid-cols-2 gap-md">
                    @forelse($customer->vehicles as $vehicle)
                        <div class="border border-outline-variant rounded-xl p-md hover:border-primary transition-colors bg-surface flex flex-col justify-between shadow-sm">
                            <div class="flex items-start gap-md">
                                <div class="w-12 h-12 rounded-full bg-primary-container/10 flex items-center justify-center text-primary shrink-0">
                                    <span class="material-symbols-outlined">two_wheeler</span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start mb-xs">
                                        <h4 class="text-body-md font-body-md font-semibold text-on-background">{{ $vehicle->brand }} {{ $vehicle->model }}</h4>
                                        <span class="px-2 py-1 bg-surface-container text-on-surface-variant rounded-full text-[10px] font-label-md font-bold">{{ $vehicle->year }}</span>
                                    </div>
                                    <p class="text-label-md font-label-md font-mono font-bold text-on-surface-variant mb-xs">{{ $vehicle->plate_number }}</p>
                                </div>
                            </div>

                            <div class="mt-3 pt-2 border-t border-outline-variant/40 flex items-center justify-between gap-2">
                                <button onclick="document.getElementById('admin-edit-vehicle-{{ $vehicle->id }}').classList.toggle('hidden')" class="px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-[11px] rounded-lg transition-colors flex items-center gap-0.5">
                                    <span class="material-symbols-outlined text-[14px]">edit</span> Edit
                                </button>
                                <form action="{{ route('admin.vehicles.destroy', $vehicle->id) }}" method="POST" onsubmit="return confirm('Hapus motor {{ $vehicle->brand }} {{ $vehicle->model }} ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-[11px] rounded-lg transition-colors flex items-center gap-0.5">
                                        <span class="material-symbols-outlined text-[14px]">delete</span> Hapus
                                    </button>
                                </form>
                            </div>

                            <!-- Modal Admin Edit Motor -->
                            <div id="admin-edit-vehicle-{{ $vehicle->id }}" class="hidden mt-2 p-3 bg-blue-50/90 border border-blue-200 rounded-xl text-left">
                                <h6 class="font-bold text-[11px] text-blue-900 mb-2">Edit Motor: {{ $vehicle->brand }} {{ $vehicle->model }}</h6>
                                <form action="{{ route('admin.vehicles.update', $vehicle->id) }}" method="POST" class="space-y-2">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid grid-cols-2 gap-1.5">
                                        <input type="text" name="brand" value="{{ $vehicle->brand }}" required class="text-[11px] p-1.5 border border-slate-300 rounded">
                                        <input type="text" name="model" value="{{ $vehicle->model }}" required class="text-[11px] p-1.5 border border-slate-300 rounded">
                                        <input type="text" name="plate_number" value="{{ $vehicle->plate_number }}" required class="text-[11px] p-1.5 border border-slate-300 rounded">
                                        <input type="number" name="year" value="{{ $vehicle->year }}" class="text-[11px] p-1.5 border border-slate-300 rounded">
                                    </div>
                                    <div class="flex justify-end gap-1.5 pt-1">
                                        <button type="button" onclick="document.getElementById('admin-edit-vehicle-{{ $vehicle->id }}').classList.add('hidden')" class="px-2 py-1 bg-slate-200 text-slate-700 text-[10px] font-bold rounded">Batal</button>
                                        <button type="submit" class="px-2 py-1 bg-blue-600 text-white text-[10px] font-bold rounded">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-on-surface-variant">Pelanggan belum memiliki kendaraan terdaftar.</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden flex flex-col flex-1">
                <div class="px-lg py-md border-b border-outline-variant flex justify-between items-center bg-surface-bright">
                    <div class="flex items-center gap-sm">
                        <span class="material-symbols-outlined text-primary">history</span>
                        <h3 class="text-body-lg font-body-lg font-semibold text-on-background">Service History</h3>
                    </div>
                    <a href="{{ route('admin.history.index') }}" class="text-on-surface-variant hover:text-primary text-label-md font-label-md transition-colors flex items-center gap-xs">
                        View All
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-surface-container-low text-label-md font-label-md text-on-surface-variant border-b border-outline-variant sticky top-0">
                            <tr>
                                <th class="px-lg py-sm font-semibold">Date</th>
                                <th class="px-lg py-sm font-semibold">Vehicle</th>
                                <th class="px-lg py-sm font-semibold">Service Type</th>
                                <th class="px-lg py-sm font-semibold">Mechanic</th>
                                <th class="px-lg py-sm font-semibold text-right">Cost</th>
                            </tr>
                        </thead>
                        <tbody class="text-body-md font-body-md text-on-background divide-y divide-outline-variant/50">
                            @forelse($customer->serviceHistories as $history)
                                <tr class="hover:bg-surface-container-lowest transition-colors group">
                                    <td class="px-lg py-md whitespace-nowrap">{{ $history->completed_at ? $history->completed_at->format('d M Y') : $history->created_at->format('d M Y') }}</td>
                                    <td class="px-lg py-md">{{ $history->vehicle->brand }} {{ $history->vehicle->model }}</td>
                                    <td class="px-lg py-md">
                                        <span class="inline-flex items-center gap-xs px-2 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed text-[11px] font-label-md font-semibold">
                                            {{ $history->service_type }}
                                        </span>
                                    </td>
                                    <td class="px-lg py-md text-on-surface-variant">{{ $history->mechanic ?? 'Tim Servis' }}</td>
                                    <td class="px-lg py-md text-right font-tabular-nums text-tabular-nums">Rp {{ number_format($history->total_cost, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-lg py-md text-center text-on-surface-variant">Belum ada riwayat servis untuk pelanggan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
