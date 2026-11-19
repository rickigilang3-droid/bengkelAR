@extends('dashboard.admin.layouts.app')

@section('title', $title ?? 'Antrean Servis')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-on-surface">Antrean Servis</h2>
            <p class="text-sm text-secondary mt-1">Kelola dan pantau antrean kendaraan yang sedang menunggu servis.</p>
        </div>
        <a href="{{ route('admin.queue.create') }}" class="flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-primary hover:bg-primary/90 shadow-md shadow-primary/20 transition-all active:scale-[0.98]">
            <span class="material-symbols-outlined text-[20px]">add_circle</span>
            Tambah Antrean
        </a>
    </div>

    <!-- Queue Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-surface-container-lowest rounded-2xl p-5 border border-outline-variant/40 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined">pending_actions</span>
            </div>
            <div>
                <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-1">Menunggu</p>
                <p class="text-2xl font-black text-on-surface">{{ $waiting }}</p>
            </div>
        </div>
        <div class="bg-surface-container-lowest rounded-2xl p-5 border border-outline-variant/40 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined">build_circle</span>
            </div>
            <div>
                <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-1">Dikerjakan</p>
                <p class="text-2xl font-black text-on-surface">{{ $inProgress }}</p>
            </div>
        </div>
        <div class="bg-surface-container-lowest rounded-2xl p-5 border border-outline-variant/40 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined">check_circle</span>
            </div>
            <div>
                <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-1">Selesai (Hari Ini)</p>
                <p class="text-2xl font-black text-on-surface">{{ $completed }}</p>
            </div>
        </div>
        <div class="bg-surface-container-lowest rounded-2xl p-5 border border-outline-variant/40 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined">event</span>
            </div>
            <div>
                <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-1">Booking (Tgl {{ \Carbon\Carbon::parse($selectedDate)->format('d M') }})</p>
                <p class="text-2xl font-black text-on-surface">{{ count($bookingRequestsDate) }}</p>
            </div>
        </div>
    </div>

    <!-- PANEL CEK JADWAL BOOKING SESUAI TANGGAL -->
    <div class="bg-surface-container-lowest rounded-2xl p-6 border-2 border-primary/20 shadow-md">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6 pb-4 border-b border-outline-variant/40">
            <div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-2xl">calendar_month</span>
                    <h3 class="text-lg font-extrabold text-on-surface">Cek Jadwal Booking Pelanggan</h3>
                </div>
                <p class="text-xs text-secondary mt-1">Pilih tanggal untuk melihat siapa saja pelanggan yang sudah melakukan booking servis pada tanggal tersebut.</p>
            </div>

            <!-- Date Filter Form -->
            <form method="GET" action="{{ route('admin.queue.index') }}" class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                <input type="date" name="date" value="{{ $selectedDate }}" onchange="this.form.submit()" class="px-4 py-2.5 rounded-xl border border-outline-variant text-xs font-bold bg-slate-50 focus:bg-white focus:border-primary focus:ring-primary outline-none">
                
                <a href="{{ route('admin.queue.index', ['date' => date('Y-m-d')]) }}" class="px-3 py-2 rounded-lg text-xs font-bold {{ $selectedDate === date('Y-m-d') ? 'bg-primary text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Hari Ini</a>
                <a href="{{ route('admin.queue.index', ['date' => date('Y-m-d', strtotime('+1 day'))]) }}" class="px-3 py-2 rounded-lg text-xs font-bold {{ $selectedDate === date('Y-m-d', strtotime('+1 day')) ? 'bg-primary text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Besok</a>
                <a href="{{ route('admin.queue.index', ['date' => date('Y-m-d', strtotime('+2 days'))]) }}" class="px-3 py-2 rounded-lg text-xs font-bold {{ $selectedDate === date('Y-m-d', strtotime('+2 days')) ? 'bg-primary text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Lusa</a>
                <a href="{{ route('admin.queue.index') }}" class="px-3 py-2 rounded-lg text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200">Semua</a>
            </form>
        </div>

        <!-- Booking List for Selected Date -->
        <h4 class="text-xs font-bold uppercase tracking-wider text-secondary mb-3">
            Daftar Booking pada <span class="text-primary font-black">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}</span> ({{ count($bookingRequestsDate) }} Pelanggan)
        </h4>

        @if(count($bookingRequestsDate) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($bookingRequestsDate as $b)
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-white hover:border-primary/50 transition-all flex flex-col justify-between shadow-sm">
                    <div>
                        <div class="flex justify-between items-start gap-2 mb-2">
                            <span class="px-2.5 py-1 rounded-full bg-blue-100 text-blue-800 text-[11px] font-extrabold flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">schedule</span> {{ $b->preferred_time }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider
                                @if($b->status === 'accepted') bg-emerald-100 text-emerald-800 border border-emerald-300
                                @elseif($b->status === 'cancelled') bg-rose-100 text-rose-800 border border-rose-300
                                @else bg-amber-100 text-amber-800 border border-amber-300 animate-pulse @endif">
                                {{ $b->status }}
                            </span>
                        </div>

                        <h5 class="font-bold text-sm text-slate-900">{{ $b->user->name ?? 'Pelanggan' }}</h5>
                        <p class="text-xs text-slate-500 font-medium">{{ $b->user->phone ?? 'No HP -' }}</p>
                        
                        <div class="mt-2 pt-2 border-t border-slate-200/60">
                            <p class="text-xs font-bold text-primary">{{ $b->service_type }} — {{ $b->vehicle->brand ?? 'Motor' }} {{ $b->vehicle->model ?? '' }} ({{ $b->vehicle->plate_number ?? '-' }})</p>
                            <p class="text-xs text-slate-600 italic mt-1 bg-white p-2 rounded border border-slate-100">"{{ $b->complaint }}"</p>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-200 flex items-center gap-2">
                        @if($b->status === 'pending')
                            <form method="POST" action="{{ route('admin.booking.accept', $b->id) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full py-2 px-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-colors shadow flex items-center justify-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">check</span> Terima & Antrekan
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.booking.reject', $b->id) }}">
                                @csrf
                                <button type="submit" class="py-2 px-3 bg-rose-100 hover:bg-rose-200 text-rose-700 rounded-lg text-xs font-bold transition-colors">Tolak</button>
                            </form>
                        @else
                            <span class="text-[11px] text-slate-400 font-semibold italic">Status: {{ strtoupper($b->status) }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="p-6 text-center bg-white rounded-xl border border-dashed border-slate-300">
                <span class="material-symbols-outlined text-slate-400 text-3xl mb-1">event_busy</span>
                <p class="text-xs font-bold text-slate-600">Tidak ada pengajuan booking pada tanggal ini ({{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}).</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Pilih tanggal lain di atas untuk mengecek jadwal booking pelanggan.</p>
            </div>
        @endif
    </div>

    <!-- Queue Table -->
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/40 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low/50 border-b border-outline-variant/40">
                        <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider">No. Antrean</th>
                        <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider">Waktu Masuk</th>
                        <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider">Kendaraan</th>
                        <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider">Prioritas</th>
                        <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @forelse ($queues as $queue)
                        <tr class="hover:bg-surface-container-low/50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-primary">#{{ str_pad($queue->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-on-surface">{{ $queue->entered_at->format('H:i') }} WIB</div>
                                <div class="text-xs text-secondary mt-0.5">{{ $queue->entered_at->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-on-surface">{{ $queue->user->name }}</div>
                                <div class="text-xs text-secondary mt-0.5">{{ $queue->user->phone ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-on-surface">{{ $queue->vehicle->plate_number }}</div>
                                <div class="text-xs text-secondary mt-0.5">{{ $queue->vehicle->brand }} {{ $queue->vehicle->model }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($queue->priority === 'high')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-red-50 text-red-700 text-xs font-bold ring-1 ring-inset ring-red-600/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Tinggi
                                    </span>
                                @elseif($queue->priority === 'medium')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-orange-50 text-orange-700 text-xs font-bold ring-1 ring-inset ring-orange-600/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-600"></span> Sedang
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-green-50 text-green-700 text-xs font-bold ring-1 ring-inset ring-green-600/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Rendah
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($queue->status === 'waiting')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200">Menunggu</span>
                                @elseif($queue->status === 'in_progress')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-orange-50 text-orange-700 text-xs font-bold border border-orange-200">Dikerjakan</span>
                                @elseif($queue->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold border border-green-200">Selesai</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold border border-red-200">Dibatalkan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('admin.queue.show', $queue->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-secondary hover:text-primary hover:bg-primary/10 transition-colors" title="Lihat Detail">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-secondary">
                                    <span class="material-symbols-outlined text-4xl mb-3 text-outline-variant">inbox</span>
                                    <p class="text-sm font-medium text-on-surface">Belum ada antrean</p>
                                    <p class="text-xs mt-1">Antrean yang ditambahkan akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($queues->hasPages())
            <div class="px-6 py-4 border-t border-outline-variant/30 bg-surface-container-low/30">
                {{ $queues->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
