<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-indigo-600">Riwayat servis / Nota</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Nota servis #{{ $history->id }}</h1>
                <p class="mt-1 text-sm text-slate-500">Rincian pekerjaan, barang yang disetujui, dan status pembayaran.</p>
            </div>
            <button type="button" onclick="window.print()" class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-indigo-700 px-4 py-2 text-sm font-bold text-white hover:bg-indigo-800 print:hidden">
                <span class="material-symbols-outlined text-lg" aria-hidden="true">print</span>
                Cetak nota
            </button>
        </div>
    </x-slot>

    <div class="mx-auto max-w-3xl px-4 py-7 sm:px-6 lg:px-8">
        <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm print:rounded-none print:border-0 print:shadow-none">
            <div class="border-b border-slate-100 bg-slate-50 px-6 py-6 sm:px-8">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-700">Bengkel AR · Nota servis</p>
                <div class="mt-3 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900">{{ $history->service_type }}</h2>
                        <p class="mt-1 text-sm text-slate-500">Nota #{{ $history->id }}</p>
                    </div>
                    <p class="text-sm font-semibold text-slate-600">{{ $history->completed_at?->format('d M Y, H:i') ?? '—' }}</p>
                </div>
            </div>

            <div class="grid gap-5 border-b border-slate-100 px-6 py-5 sm:grid-cols-2 sm:px-8">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Kendaraan</p>
                    <p class="mt-1 font-bold text-slate-900">{{ $history->vehicle?->brand }} {{ $history->vehicle?->model }}</p>
                    <p class="mt-1 text-sm text-slate-500">{{ $history->vehicle?->plate_number }} · {{ $history->vehicle?->year }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Waktu servis</p>
                    <p class="mt-1 text-sm text-slate-700">Masuk: {{ $history->entered_at?->format('d M Y, H:i') ?? '—' }}</p>
                    <p class="mt-1 text-sm text-slate-700">Selesai: {{ $history->completed_at?->format('d M Y, H:i') ?? '—' }}</p>
                </div>
            </div>

            <div class="px-6 py-6 sm:px-8">
                <h3 class="text-sm font-extrabold uppercase tracking-wider text-slate-500">Rincian pekerjaan dan barang</h3>
                <div class="mt-3 whitespace-pre-line rounded-2xl bg-slate-50 p-4 text-sm leading-7 text-slate-700">{{ $history->description }}</div>
                @if ($history->notes)
                    <div class="mt-4">
                        <h3 class="text-sm font-extrabold uppercase tracking-wider text-slate-500">Catatan bengkel</h3>
                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $history->notes }}</p>
                    </div>
                @endif
            </div>

            <div class="border-t border-slate-100 px-6 py-5 sm:px-8">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total pembayaran</p>
                        <p class="mt-1 text-2xl font-black tabular-nums text-slate-900">
                            {{ (float) $history->total_cost > 0 ? 'Rp' . number_format((float) $history->total_cost, 0, ',', '.') : 'Belum ditentukan' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Metode</p>
                        <p class="mt-1 font-bold text-slate-800">
                            {{ match ($history->payment_channel) {
                                'cash' => 'Tunai',
                                'bank_transfer' => 'Transfer bank',
                                'qris' => 'QRIS',
                                'dana' => 'DANA',
                                'ovo' => 'OVO',
                                'gopay' => 'GoPay',
                                'shopeepay' => 'ShopeePay',
                                'linkaja' => 'LinkAja',
                                default => $history->payment_method === 'cash' ? 'Tunai' : ($history->payment_method === 'transfer' ? 'Transfer bank' : 'Belum dipilih'),
                            } }}
                        </p>
                        <span class="mt-1 inline-flex rounded-full px-2.5 py-1 text-xs font-bold {{ $history->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : ($history->payment_status === 'failed' ? 'bg-rose-100 text-rose-800' : ($history->payment_submitted_at ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800')) }}">
                            {{ $history->payment_status === 'paid' ? 'Lunas' : ($history->payment_status === 'failed' ? 'Perlu dikirim ulang' : ($history->payment_submitted_at ? 'Menunggu verifikasi' : 'Belum dibayar')) }}
                        </span>
                    </div>
                </div>
                @if ($history->payment_submitted_at)
                    <p class="mt-3 text-xs text-slate-500">Pembayaran dikirim {{ $history->payment_submitted_at->format('d M Y, H:i') }}</p>
                @endif
            </div>
        </article>
        <a href="{{ route('customer.history') }}" class="mt-5 inline-flex items-center gap-2 text-sm font-bold text-indigo-700 hover:underline print:hidden">
            <span class="material-symbols-outlined text-lg" aria-hidden="true">arrow_back</span>
            Kembali ke riwayat
        </a>
    </div>
</x-app-layout>
