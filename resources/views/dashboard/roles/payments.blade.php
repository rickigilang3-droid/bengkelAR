<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-indigo-600">Portal pelanggan</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Pembayaran servis</h1>
            <p class="mt-1 text-sm text-slate-500">Tinjau tagihan, pilih pembayaran tunai atau transfer, dan pantau verifikasinya.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl space-y-5 px-4 py-7 sm:px-6 lg:px-8">
        @if (session('status'))
            <div role="status" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div role="alert" class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <p class="font-bold">Pembayaran belum dapat diproses.</p>
                <ul class="mt-1 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        @foreach ($activeCharges as $queue)
            @php
                $approvedTotal = (float) $queue->approvalItems->sum('estimated_cost');
            @endphp
            <article class="overflow-hidden rounded-3xl border border-indigo-200 bg-indigo-50/60 shadow-sm">
                <div class="border-b border-indigo-100 p-5 sm:px-7">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-lg font-bold text-slate-900">Perkiraan biaya servis #{{ str_pad($queue->id, 3, '0', STR_PAD_LEFT) }}</h2>
                        <span class="rounded-full bg-indigo-100 px-2.5 py-1 text-xs font-bold text-indigo-800">Servis sedang berjalan</span>
                    </div>
                    <p class="mt-1 text-sm text-slate-600">{{ $queue->vehicle?->brand }} {{ $queue->vehicle?->model }} · {{ $queue->vehicle?->plate_number }}</p>
                </div>
                <div class="space-y-3 p-5 sm:px-7">
                    @foreach ($queue->approvalItems as $item)
                        <div class="flex flex-col justify-between gap-1 border-b border-indigo-100 pb-3 last:border-0 sm:flex-row sm:items-start">
                            <div>
                                <p class="font-semibold text-slate-800">{{ $item->item_name }}</p>
                                @if ($item->notes)
                                    <p class="mt-1 text-xs text-slate-500">{{ $item->notes }}</p>
                                @endif
                            </div>
                            <p class="shrink-0 font-bold tabular-nums text-slate-900">Rp{{ number_format((float) $item->estimated_cost, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                    <div class="flex items-center justify-between gap-4 pt-1">
                        <p class="font-bold text-slate-800">Subtotal yang telah disetujui</p>
                        <p class="text-xl font-extrabold tabular-nums text-indigo-800">Rp{{ number_format($approvedTotal, 0, ',', '.') }}</p>
                    </div>
                    <p class="text-xs leading-5 text-slate-600">Jumlah ini otomatis berasal dari sparepart/perbaikan yang Anda setujui. Tagihan final dan pilihan pembayaran akan tersedia setelah servis selesai.</p>
                    <a href="{{ route('customer.service.show', $queue) }}#approvals" class="inline-flex items-center gap-1 text-sm font-bold text-indigo-700 hover:underline">
                        Lihat persetujuan servis <span class="material-symbols-outlined text-lg" aria-hidden="true">arrow_forward</span>
                    </a>
                </div>
            </article>
        @endforeach

        @forelse ($histories as $history)
            <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-4 border-b border-slate-100 p-5 sm:flex-row sm:items-start sm:justify-between sm:px-7">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-lg font-bold text-slate-900">{{ $history->service_type }}</h2>
                            <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $history->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : ($history->payment_status === 'failed' ? 'bg-rose-100 text-rose-800' : ($history->payment_submitted_at ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800')) }}">
                                {{ $history->payment_status === 'paid' ? 'Lunas' : ($history->payment_status === 'failed' ? 'Perlu dikirim ulang' : ($history->payment_submitted_at ? 'Menunggu verifikasi' : 'Belum dibayar')) }}
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-slate-500">Servis selesai {{ $history->completed_at?->format('d M Y') ?? '—' }} · {{ $history->vehicle?->brand }} {{ $history->vehicle?->model }} ({{ $history->vehicle?->plate_number }})</p>
                        <p class="mt-3 text-2xl font-extrabold tabular-nums text-slate-900">
                            {{ (float) $history->total_cost > 0 ? 'Rp' . number_format((float) $history->total_cost, 0, ',', '.') : 'Tagihan belum tersedia' }}
                        </p>
                        <details class="mt-3 text-sm">
                            <summary class="cursor-pointer font-bold text-indigo-700">Lihat rincian nota</summary>
                            <div class="mt-2 whitespace-pre-line rounded-xl bg-slate-50 p-4 leading-6 text-slate-700">{{ $history->description }}</div>
                        </details>
                    </div>
                    <a href="{{ route('customer.history') }}" class="inline-flex min-h-10 items-center justify-center gap-2 self-start rounded-xl border border-slate-200 px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50">
                        Lihat riwayat <span class="material-symbols-outlined text-lg" aria-hidden="true">arrow_forward</span>
                    </a>
                </div>
                <a href="{{ route('customer.history.show', $history) }}" class="inline-flex items-center gap-1 px-5 pb-4 text-sm font-bold text-indigo-700 hover:underline sm:px-7">
                    Buka / cetak nota <span class="material-symbols-outlined text-lg" aria-hidden="true">receipt_long</span>
                </a>

                @if ($history->payment_status !== 'paid' && (float) $history->total_cost > 0)
                    @if ($history->payment_status === 'pending' && $history->payment_submitted_at)
                        <div class="p-5 sm:px-7">
                            <p class="text-sm font-semibold text-blue-800">Pembayaran {{ str_replace('_', ' ', strtoupper($history->payment_channel ?? $history->payment_method ?? 'tunai')) }} Anda sudah dikirim. Admin sedang memverifikasi.</p>
                            <p class="mt-1 text-xs text-slate-500">Dikirim {{ $history->payment_submitted_at->format('d M Y, H:i') }}</p>
                        </div>
                    @else
                        <form method="POST" action="{{ route('customer.payments.submit', $history) }}" enctype="multipart/form-data" class="space-y-4 p-5 sm:px-7">
                            @csrf
                            <div>
                                <label for="payment-channel-{{ $history->id }}" class="block text-sm font-bold text-slate-800">Pilih metode pembayaran</label>
                                <select id="payment-channel-{{ $history->id }}" name="payment_channel" required data-payment-channel data-proof-id="payment-proof-{{ $history->id }}" class="mt-2 block w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Pilih metode</option>
                                    <option value="cash">Tunai di bengkel</option>
                                    <option value="bank_transfer">Transfer bank</option>
                                    <option value="qris">QRIS</option>
                                    <option value="dana">DANA</option>
                                    <option value="ovo">OVO</option>
                                    <option value="gopay">GoPay</option>
                                    <option value="shopeepay">ShopeePay</option>
                                    <option value="linkaja">LinkAja</option>
                                </select>
                                <p class="mt-2 text-xs leading-5 text-slate-500">Transfer, QRIS, dan e-wallet dibayar manual. Minta rekening atau QR resmi Bengkel AR lewat chat terlebih dahulu, lalu unggah bukti. Jangan transfer ke tujuan yang belum diverifikasi.</p>
                            </div>
                            <div data-payment-details="{{ $history->id }}" class="hidden" aria-live="polite">
                                @php
                                    $paymentAmount = 'Rp' . number_format((float) $history->total_cost, 0, ',', '.');
                                @endphp
                                <div data-payment-detail="cash" class="hidden rounded-2xl border border-amber-200 bg-amber-50 p-4">
                                    <p class="text-sm font-bold text-amber-900">Pembayaran Tunai di Bengkel</p>
                                    <p class="mt-1 text-sm text-amber-800">Bayar sebesar <strong>{{ $paymentAmount }}</strong> langsung kepada kasir saat pengambilan kendaraan.</p>
                                </div>
                                <div data-payment-detail="bank_transfer" class="hidden rounded-2xl border border-indigo-200 bg-indigo-50 p-4">
                                    <p class="text-sm font-bold text-indigo-900">Transfer Bank</p>
                                    <p class="mt-1 text-sm text-indigo-800">Nomor rekening bank belum dikonfigurasi. Hubungi bengkel melalui <a href="{{ route('customer.chats') }}" class="font-bold underline">chat</a> untuk meminta rekening resmi, atau pilih metode e-wallet yang tersedia.</p>
                                    <p class="mt-2 text-sm text-indigo-800">Nominal tagihan: <strong>{{ $paymentAmount }}</strong>.</p>
                                </div>
                                <div data-payment-detail="qris" class="hidden rounded-2xl border border-indigo-200 bg-indigo-50 p-4">
                                    <p class="text-sm font-bold text-indigo-900">Pembayaran QRIS</p>
                                    <div class="mt-3 grid gap-3 sm:grid-cols-[150px_1fr] sm:items-center">
                                        <img src="{{ asset('images/qris-bengkel.jpe') }}" alt="QRIS resmi Bengkel AR" class="mx-auto aspect-square w-36 rounded-xl border border-indigo-100 bg-white object-contain p-2 shadow-sm">
                                        <p class="text-sm text-indigo-800">Pindai QRIS resmi Bengkel AR, lalu bayar sebesar <strong>{{ $paymentAmount }}</strong>. Setelah berhasil, unggah bukti pembayaran di bawah.</p>
                                    </div>
                                </div>
                                @foreach (['dana' => ['label' => 'DANA', 'prefix' => '9101'], 'ovo' => ['label' => 'OVO', 'prefix' => '9102'], 'gopay' => ['label' => 'GoPay', 'prefix' => '9103'], 'shopeepay' => ['label' => 'ShopeePay', 'prefix' => '9104'], 'linkaja' => ['label' => 'LinkAja', 'prefix' => '9105']] as $channel => $wallet)
                                    @php
                                        $referenceNumber = $wallet['prefix'] . str_pad((string) $history->id, 10, '0', STR_PAD_LEFT);
                                    @endphp
                                    <div data-payment-detail="{{ $channel }}" class="hidden rounded-2xl border border-indigo-200 bg-indigo-50 p-4">
                                        <p class="text-sm font-bold text-indigo-900">Pembayaran {{ $wallet['label'] }}</p>
                                        <div class="mt-3 rounded-xl border border-indigo-100 bg-white p-3">
                                            <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Nomor {{ $wallet['label'] }}</p>
                                            <div class="mt-1 flex flex-wrap items-center justify-between gap-2">
                                                <strong class="font-mono text-lg text-slate-900" data-copy-text>{{ config('services.ewallet.number') }}</strong>
                                                <button type="button" data-copy-button class="rounded-lg bg-indigo-100 px-3 py-1.5 text-xs font-bold text-indigo-700 hover:bg-indigo-200">Salin nomor</button>
                                            </div>
                                            <p class="mt-1 text-xs text-slate-600">Penerima: <strong>{{ config('services.ewallet.name') }}</strong></p>
                                        </div>
                                        <div class="mt-3 rounded-xl border border-indigo-100 bg-white p-3">
                                            <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Referensi</p>
                                            <div class="mt-1 flex flex-wrap items-center justify-between gap-2">
                                                <strong class="font-mono text-base text-slate-900" data-copy-text>{{ $referenceNumber }}</strong>
                                                <button type="button" data-copy-button class="rounded-lg bg-indigo-100 px-3 py-1.5 text-xs font-bold text-indigo-700 hover:bg-indigo-200">Salin referensi</button>
                                            </div>
                                            <p class="mt-1 text-xs text-slate-500">Kode ini bukan nomor tujuan transfer.</p>
                                        </div>
                                        <p class="mt-2 text-sm text-indigo-800">Pastikan nama penerima sesuai sebelum membayar. Nominal: <strong>{{ $paymentAmount }}</strong>.</p>
                                    </div>
                                @endforeach
                            </div>
                            <div>
                                <label for="payment-proof-{{ $history->id }}" class="block text-sm font-bold text-slate-800">Bukti pembayaran <span class="font-normal text-slate-500">(wajib untuk pembayaran non-tunai, JPG/PNG/PDF maks. 5 MB)</span></label>
                                <input id="payment-proof-{{ $history->id }}" type="file" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" class="mt-2 block w-full rounded-xl border border-slate-300 text-sm file:mr-4 file:border-0 file:bg-slate-100 file:px-4 file:py-2.5 file:font-semibold file:text-slate-700">
                            </div>
                            <button type="submit" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-indigo-700 px-5 py-2.5 text-sm font-bold text-white hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">
                                <span class="material-symbols-outlined text-lg" aria-hidden="true">send</span>
                                Kirim pembayaran
                            </button>
                        </form>
                    @endif
                @elseif ($history->payment_status === 'paid')
                    <p class="p-5 text-sm font-semibold text-emerald-800 sm:px-7">Pembayaran telah diverifikasi lunas{{ $history->payment_channel ? ' melalui ' . str_replace('_', ' ', strtoupper($history->payment_channel)) : '' }}.</p>
                @else
                    <p class="p-5 text-sm text-amber-800 sm:px-7">Tagihan sedang disiapkan bengkel. Tombol pembayaran akan tersedia setelah total biaya dimasukkan.</p>
                @endif
            </article>
        @empty
            <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center">
                <span class="material-symbols-outlined text-4xl text-slate-300" aria-hidden="true">payments</span>
                <h2 class="mt-3 text-lg font-bold text-slate-900">Tidak ada tagihan yang perlu dibayar</h2>
                <p class="mx-auto mt-1 max-w-md text-sm text-slate-500">Tagihan lunas tidak ditampilkan di halaman pembayaran. Lihat riwayat servis untuk membuka nota dan bukti pembayaran sebelumnya.</p>
                <a href="{{ route('customer.history') }}" class="mt-4 inline-flex min-h-10 items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50">
                    Buka riwayat servis <span class="material-symbols-outlined text-lg" aria-hidden="true">arrow_forward</span>
                </a>
            </div>
        @endforelse

        @if ($histories->hasPages())
            <div>{{ $histories->links() }}</div>
        @endif
    </div>
    <script>
        document.querySelectorAll('[data-copy-button]').forEach((button) => {
            button.addEventListener('click', async () => {
                const text = button.parentElement.querySelector('[data-copy-text]')?.textContent?.trim();
                if (!text) return;

                try {
                    await navigator.clipboard.writeText(text);
                    button.textContent = 'Tersalin';
                    window.setTimeout(() => {
                        button.textContent = 'Salin';
                    }, 1600);
                } catch (error) {
                    window.prompt('Salin nomor berikut:', text);
                }
            });
        });

        document.querySelectorAll('[data-payment-channel]').forEach((select) => {
            const proofInput = document.getElementById(select.dataset.proofId);
            const detailsContainer = select.closest('form')?.querySelector('[data-payment-details]');
            const updateProofRequirement = () => {
                proofInput.required = select.value !== '' && select.value !== 'cash';

                if (detailsContainer) {
                    detailsContainer.classList.toggle('hidden', select.value === '');
                    detailsContainer.querySelectorAll('[data-payment-detail]').forEach((detail) => {
                        detail.classList.toggle('hidden', detail.dataset.paymentDetail !== select.value);
                    });
                }
            };
            select.addEventListener('change', updateProofRequirement);
            updateProofRequirement();
        });
    </script>
</x-app-layout>
