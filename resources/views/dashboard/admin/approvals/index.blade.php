@extends('dashboard.admin.layouts.app')

@section('title', 'Persetujuan Sparepart')
@section('content')
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-on-surface">Persetujuan Sparepart</h2>
            <p class="mt-1 text-sm text-secondary">Pantau permintaan penggantian barang dan buka servis terkait untuk tindak lanjut.</p>
        </div>

        <div class="overflow-hidden rounded-2xl border border-outline-variant/50 bg-surface-container-lowest shadow-sm">
            @forelse ($approvals as $approval)
                @php($queue = $approval->serviceQueue)
                <div class="flex flex-col gap-4 border-b border-outline-variant/30 p-5 last:border-b-0 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="font-bold text-on-surface">{{ $approval->item_name }}</h3>
                            <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $approval->status === 'pending' ? 'bg-amber-100 text-amber-800' : ($approval->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800') }}">
                                {{ match ($approval->status) { 'pending' => 'Menunggu pelanggan', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', default => ucfirst($approval->status) } }}
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-secondary">{{ $queue->user?->name ?? 'Pelanggan' }} · Servis #{{ str_pad($queue->id, 3, '0', STR_PAD_LEFT) }} · {{ $queue->vehicle?->brand }} {{ $queue->vehicle?->model }}</p>
                        <p class="mt-1 text-sm font-bold text-on-surface">Rp{{ number_format((float) $approval->estimated_cost, 0, ',', '.') }}</p>
                        @if ($approval->notes)
                            <p class="mt-1 text-sm text-secondary">{{ $approval->notes }}</p>
                        @endif
                    </div>
                    <a href="{{ route('admin.queue.show', $queue->id) }}#approvals" class="inline-flex min-h-10 shrink-0 items-center justify-center gap-2 rounded-xl bg-primary px-4 py-2 text-sm font-bold text-white hover:bg-primary/90">
                        Buka detail servis
                        <span class="material-symbols-outlined text-lg" aria-hidden="true">arrow_forward</span>
                    </a>
                </div>
            @empty
                <div class="px-6 py-14 text-center">
                    <span class="material-symbols-outlined text-4xl text-secondary/40" aria-hidden="true">fact_check</span>
                    <h3 class="mt-3 font-bold text-on-surface">Belum ada permintaan sparepart</h3>
                    <p class="mt-1 text-sm text-secondary">Permintaan yang dikirim dari detail antrean akan tercatat di sini.</p>
                </div>
            @endforelse
        </div>

        @if ($approvals->hasPages())
            <div>{{ $approvals->links() }}</div>
        @endif
    </div>
@endsection
