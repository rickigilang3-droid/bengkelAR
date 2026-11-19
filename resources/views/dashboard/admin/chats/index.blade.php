@extends('dashboard.admin.layouts.app')

@section('title', 'Chat Pelanggan')
@section('content')
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-on-surface">Chat Pelanggan</h2>
            <p class="mt-1 text-sm text-secondary">Pilih servis untuk membuka percakapan dan membalas pesan pelanggan.</p>
        </div>

        <div class="overflow-hidden rounded-2xl border border-outline-variant/50 bg-surface-container-lowest shadow-sm">
            @forelse ($queues as $queue)
                @php($latestChat = $queue->chats->first())
                <a href="{{ route('admin.queue.show', $queue->id) }}#chat" class="group flex flex-col gap-4 border-b border-outline-variant/30 p-5 transition last:border-b-0 hover:bg-primary/5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex min-w-0 items-start gap-4">
                        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-primary/10 text-primary">
                            <span class="material-symbols-outlined" aria-hidden="true">chat</span>
                        </span>
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-bold text-on-surface">{{ $queue->user?->name ?? 'Pelanggan' }}</h3>
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ $queue->chats_count }} pesan</span>
                            </div>
                            <p class="mt-1 text-sm text-secondary">Servis #{{ str_pad($queue->id, 3, '0', STR_PAD_LEFT) }} · {{ $queue->vehicle?->brand }} {{ $queue->vehicle?->model }} ({{ $queue->vehicle?->plate_number }})</p>
                            <p class="mt-2 truncate text-sm text-secondary">{{ $latestChat?->message ?? 'Belum ada pesan. Anda dapat memulai percakapan.' }}</p>
                        </div>
                    </div>
                    <span class="inline-flex shrink-0 items-center gap-1 self-end text-sm font-bold text-primary group-hover:gap-2 sm:self-center">
                        Buka chat <span class="material-symbols-outlined text-lg" aria-hidden="true">arrow_forward</span>
                    </span>
                </a>
            @empty
                <div class="px-6 py-14 text-center">
                    <span class="material-symbols-outlined text-4xl text-secondary/40" aria-hidden="true">chat_bubble</span>
                    <h3 class="mt-3 font-bold text-on-surface">Belum ada servis untuk dihubungi</h3>
                    <p class="mt-1 text-sm text-secondary">Percakapan pelanggan akan tersedia setelah servis masuk ke antrean.</p>
                </div>
            @endforelse
        </div>

        @if ($queues->hasPages())
            <div>{{ $queues->links() }}</div>
        @endif
    </div>
@endsection
