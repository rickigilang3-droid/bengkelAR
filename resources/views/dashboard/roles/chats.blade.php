<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-indigo-600">Portal pelanggan</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Chat dengan bengkel</h1>
            <p class="mt-1 text-sm text-slate-500">Pilih servis untuk melihat pesan dan menghubungi tim Bengkel AR.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl space-y-4 px-4 py-7 sm:px-6 lg:px-8">
        @forelse ($queues as $queue)
            @php($latestChat = $queue->chats->first())
            <a href="{{ route('customer.service.show', $queue) }}#conversation" class="group flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-300 hover:shadow-md sm:flex-row sm:items-center sm:justify-between">
                <div class="flex min-w-0 items-start gap-4">
                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-indigo-50 text-indigo-700">
                        <span class="material-symbols-outlined" aria-hidden="true">chat</span>
                    </span>
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="font-bold text-slate-900">Servis #{{ str_pad($queue->id, 3, '0', STR_PAD_LEFT) }}</h2>
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ $queue->chats_count }} pesan</span>
                        </div>
                        <p class="mt-1 text-sm text-slate-600">{{ $queue->vehicle?->brand }} {{ $queue->vehicle?->model }} · {{ $queue->vehicle?->plate_number }}</p>
                        <p class="mt-2 truncate text-sm text-slate-500">
                            {{ $latestChat?->message ?? 'Belum ada pesan. Mulai percakapan dengan bengkel.' }}
                        </p>
                    </div>
                </div>
                <span class="inline-flex shrink-0 items-center gap-1 self-end text-sm font-bold text-indigo-700 group-hover:gap-2 sm:self-center">
                    Buka chat <span class="material-symbols-outlined text-lg" aria-hidden="true">arrow_forward</span>
                </span>
            </a>
        @empty
            <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center">
                <span class="material-symbols-outlined text-4xl text-slate-300" aria-hidden="true">chat_bubble</span>
                <h2 class="mt-3 text-lg font-bold text-slate-900">Belum ada percakapan servis</h2>
                <p class="mx-auto mt-1 max-w-md text-sm text-slate-500">Chat akan tersedia setelah servis Anda masuk ke antrean bengkel.</p>
                <a href="{{ route('customer.bookings') }}" class="mt-5 inline-flex min-h-10 items-center justify-center rounded-xl bg-indigo-700 px-4 py-2 text-sm font-bold text-white hover:bg-indigo-800">Booking servis</a>
            </div>
        @endforelse

        @if ($queues->hasPages())
            <div class="pt-2">{{ $queues->links() }}</div>
        @endif
    </div>
</x-app-layout>
