<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-indigo-600">Portal pelanggan <span class="mx-1 text-slate-300">/</span> Detail servis</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Detail servis #{{ str_pad($serviceQueue->id, 3, '0', STR_PAD_LEFT) }}</h1>
                <p class="mt-1 text-sm text-slate-500">Informasi pekerjaan, persetujuan sparepart, dan percakapan dengan bengkel.</p>
            </div>
            <a href="{{ route('customer.notifications') }}" class="inline-flex min-h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:border-indigo-200 hover:text-indigo-700">Kembali ke notifikasi</a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl space-y-6 px-4 py-7 sm:px-6 lg:px-8">
        @if (session('status'))
            <div role="status" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm font-medium text-emerald-800">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div role="alert" class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3.5 text-sm text-rose-800">
                <p class="font-bold">Keputusan belum dapat disimpan.</p>
                <ul class="mt-2 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <section class="grid gap-4 sm:grid-cols-2">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Kendaraan</p>
                <h2 class="mt-2 text-lg font-bold text-slate-900">{{ $serviceQueue->vehicle?->brand }} {{ $serviceQueue->vehicle?->model }}</h2>
                <p class="mt-1 text-sm font-semibold tracking-wide text-slate-500">{{ $serviceQueue->vehicle?->plate_number }}</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Status servis</p>
                <span class="mt-3 inline-flex rounded-full px-3 py-1.5 text-sm font-bold {{ $serviceQueue->status === 'completed' ? 'bg-emerald-50 text-emerald-700' : ($serviceQueue->status === 'cancelled' ? 'bg-rose-50 text-rose-700' : 'bg-blue-50 text-blue-700') }}">
                    {{ match ($serviceQueue->status) { 'waiting' => 'Menunggu antrean', 'in_progress' => 'Sedang dikerjakan', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan', default => ucfirst($serviceQueue->status) } }}
                </span>
                <p class="mt-2 text-sm text-slate-500">Masuk {{ $serviceQueue->entered_at?->format('d M Y, H:i') ?? '—' }}</p>
            </article>
        </section>

        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Keluhan awal</p>
            <h2 class="mt-1 text-lg font-bold text-slate-900">Pekerjaan servis</h2>
            <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $serviceQueue->complaint }}</p>
            @if ($serviceQueue->notes)
                <div class="mt-5 rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Catatan bengkel</p>
                    <p class="mt-1 text-sm leading-6 text-slate-700">{{ $serviceQueue->notes }}</p>
                </div>
            @endif
        </section>

        <section id="approvals" class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-5 sm:px-7">
                <p class="text-xs font-bold uppercase tracking-wider text-indigo-600">Konfirmasi pelanggan</p>
                <h2 class="mt-1 text-xl font-bold text-slate-900">Persetujuan sparepart / perbaikan tambahan</h2>
                <p class="mt-1 text-sm text-slate-500">Tinjau rincian dan pilih setujui atau tolak. Keputusan akan langsung dikirimkan ke bengkel.</p>
            </div>
            @if ($serviceQueue->approvalItems->isEmpty())
                <div class="px-6 py-10 text-center">
                    <p class="font-semibold text-slate-800">Belum ada permintaan persetujuan</p>
                    <p class="mt-1 text-sm text-slate-500">Jika bengkel mengajukan barang tambahan, detailnya akan tersedia di sini.</p>
                </div>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($serviceQueue->approvalItems as $item)
                        <li class="p-5 sm:p-7">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="text-lg font-bold text-slate-900">{{ $item->item_name }}</h3>
                                        <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $item->status === 'approved' ? 'bg-emerald-50 text-emerald-700' : ($item->status === 'rejected' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-800') }}">
                                            {{ match ($item->status) { 'approved' => 'Disetujui', 'rejected' => 'Ditolak', default => 'Menunggu keputusan Anda' } }}
                                        </span>
                                    </div>
                                    <p class="mt-2 text-2xl font-extrabold tabular-nums text-slate-900">Rp{{ number_format((float) $item->estimated_cost, 0, ',', '.') }}</p>
                                    @if ($item->inventory_id)
                                        <p class="mt-1 text-sm text-slate-600">Jumlah {{ $item->quantity }} · kilometer {{ number_format((int) $item->odometer_km, 0, ',', '.') }} km · interval pengingat {{ number_format((int) $item->interval_km, 0, ',', '.') }} km</p>
                                    @endif
                                    @if ($item->notes)
                                        <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $item->notes }}</p>
                                    @endif
                                    <p class="mt-3 text-xs text-slate-400">Diajukan {{ $item->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                @if ($item->status === 'pending')
                                    <div class="flex shrink-0 flex-col gap-2 sm:min-w-40">
                                        <form method="POST" action="{{ route('customer.approvals.respond', $item) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">
                                                <span class="material-symbols-outlined text-[18px]" aria-hidden="true">check_circle</span>
                                                Setujui
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('customer.approvals.respond', $item) }}" onsubmit="return confirm('Tolak permintaan penggantian barang ini?')">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="inline-flex min-h-10 w-full items-center justify-center rounded-xl border border-rose-200 bg-white px-4 py-2 text-sm font-bold text-rose-700 transition hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">Tolak</button>
                                        </form>
                                    </div>
                                @elseif ($item->responded_at)
                                    <p class="shrink-0 text-xs text-slate-500">Diputuskan {{ $item->responded_at->format('d M Y, H:i') }}</p>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        <section id="conversation" class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-5 sm:px-7">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Komunikasi</p>
                <h2 class="mt-1 text-xl font-bold text-slate-900">Percakapan dengan bengkel</h2>
            </div>
            @if ($serviceQueue->chats->isEmpty())
                <p class="px-6 py-8 text-center text-sm text-slate-500">Belum ada pesan untuk servis ini.</p>
            @else
                <ul class="space-y-3 p-5 sm:p-7">
                    @foreach ($serviceQueue->chats as $chat)
                        <li class="max-w-2xl rounded-2xl p-4 {{ $chat->sender_id === auth()->id() ? 'ml-auto bg-indigo-50' : 'bg-slate-50' }}">
                            <div class="flex items-center justify-between gap-4">
                                <p class="text-xs font-bold text-slate-700">{{ $chat->sender?->name ?? 'Bengkel AR' }}</p>
                                <time datetime="{{ $chat->created_at->toIso8601String() }}" class="text-[11px] text-slate-400">{{ $chat->created_at->format('d M Y, H:i') }}</time>
                            </div>
                            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $chat->message }}</p>
                            @if ($chat->attachment_path)
                                <a href="{{ $chat->attachment_path }}" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex text-xs font-bold text-indigo-700 underline">Lihat lampiran</a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
            <div class="border-t border-slate-100 p-5 sm:p-7">
                <form id="customer-service-chat-form" data-endpoint="{{ url('/api/chats/' . $serviceQueue->id) }}" class="space-y-3">
                    @csrf
                    <label for="customer-service-chat-message" class="block text-sm font-bold text-slate-800">Kirim pesan ke Bengkel AR</label>
                    <textarea id="customer-service-chat-message" name="message" rows="3" maxlength="1000" required placeholder="Tulis pertanyaan atau informasi tambahan tentang servis ini..." class="block w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <p id="customer-service-chat-feedback" role="status" aria-live="polite" class="text-sm text-slate-500"></p>
                        <button id="customer-service-chat-submit" type="submit" class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-indigo-700 px-4 py-2 text-sm font-bold text-white transition hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-wait disabled:opacity-60">
                            <span class="material-symbols-outlined text-[18px]" aria-hidden="true">send</span>
                            Kirim pesan
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <script>
        document.getElementById('customer-service-chat-form')?.addEventListener('submit', async function (event) {
            event.preventDefault();

            const form = event.currentTarget;
            const messageField = form.querySelector('[name="message"]');
            const feedback = document.getElementById('customer-service-chat-feedback');
            const submitButton = document.getElementById('customer-service-chat-submit');
            const message = messageField.value.trim();

            if (!message) {
                feedback.textContent = 'Tulis pesan terlebih dahulu.';
                feedback.className = 'text-sm font-semibold text-rose-700';
                return;
            }

            submitButton.disabled = true;
            feedback.textContent = 'Mengirim pesan...';
            feedback.className = 'text-sm text-slate-500';

            try {
                const response = await fetch(form.dataset.endpoint, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                    },
                    body: new FormData(form),
                });
                const result = await response.json().catch(() => ({}));

                if (!response.ok || !result.success) {
                    throw new Error(result.error || result.message || (response.status === 419
                        ? 'Sesi Anda berakhir. Muat ulang halaman lalu coba kirim lagi.'
                        : 'Pesan belum berhasil dikirim. Silakan coba lagi.'));
                }

                window.location.reload();
            } catch (error) {
                feedback.textContent = error instanceof Error ? error.message : 'Pesan gagal dikirim. Silakan coba lagi.';
                feedback.className = 'text-sm font-semibold text-rose-700';
                submitButton.disabled = false;
            }
        });
    </script>
</x-app-layout>
