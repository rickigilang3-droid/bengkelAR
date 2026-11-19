<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-indigo-600">Portal pelanggan</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Persetujuan sparepart</h1>
            <p class="mt-1 text-sm text-slate-500">Tinjau biaya barang tambahan dan berikan keputusan untuk servis Anda.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl space-y-4 px-4 py-7 sm:px-6 lg:px-8">
        @forelse ($approvals as $approval)
            @php($queue = $approval->serviceQueue)
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-lg font-bold text-slate-900">{{ $approval->item_name }}</h2>
                            <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $approval->status === 'pending' ? 'bg-amber-100 text-amber-800' : ($approval->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800') }}">
                                {{ match ($approval->status) { 'pending' => 'Menunggu keputusan', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', default => ucfirst($approval->status) } }}
                            </span>
                        </div>
                        <p class="mt-2 text-xl font-extrabold tabular-nums text-slate-900">Rp{{ number_format((float) $approval->estimated_cost, 0, ',', '.') }}</p>
                        <p class="mt-1 text-sm text-slate-500">Servis #{{ str_pad($queue->id, 3, '0', STR_PAD_LEFT) }} · {{ $queue->vehicle?->brand }} {{ $queue->vehicle?->model }} ({{ $queue->vehicle?->plate_number }})</p>
                        @if ($approval->notes)
                            <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $approval->notes }}</p>
                        @endif
                        <p class="mt-3 text-xs text-slate-400">Diajukan {{ $approval->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <a href="{{ route('customer.service.show', $queue) }}#approvals" class="inline-flex min-h-10 shrink-0 items-center justify-center gap-2 rounded-xl {{ $approval->status === 'pending' ? 'bg-indigo-700 text-white hover:bg-indigo-800' : 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50' }} px-4 py-2 text-sm font-bold">
                        {{ $approval->status === 'pending' ? 'Tinjau keputusan' : 'Lihat detail servis' }}
                        <span class="material-symbols-outlined text-lg" aria-hidden="true">arrow_forward</span>
                    </a>
                </div>
            </article>
        @empty
            <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center">
                <span class="material-symbols-outlined text-4xl text-slate-300" aria-hidden="true">fact_check</span>
                <h2 class="mt-3 text-lg font-bold text-slate-900">Belum ada permintaan persetujuan</h2>
                <p class="mx-auto mt-1 max-w-md text-sm text-slate-500">Jika bengkel mengajukan sparepart atau perbaikan tambahan, rincian dan keputusan akan muncul di sini.</p>
            </div>
        @endforelse

        @if ($approvals->hasPages())
            <div class="pt-2">{{ $approvals->links() }}</div>
        @endif
    </div>
</x-app-layout>
