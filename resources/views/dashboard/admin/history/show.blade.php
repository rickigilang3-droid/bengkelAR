@extends('dashboard.admin.layouts.app')

@section('title', 'Detail Riwayat Servis')
@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <a href="{{ route('admin.history.index') }}" class="inline-flex items-center gap-2 font-semibold text-primary hover:text-primary-container">
                <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
                Kembali ke Riwayat
            </a>
            <button type="button" onclick="window.print()" class="inline-flex min-h-10 items-center gap-2 rounded-xl border border-outline-variant bg-white px-4 py-2 text-sm font-bold text-on-surface hover:bg-surface-container-low">
                <span class="material-symbols-outlined" aria-hidden="true">print</span>
                Cetak detail
            </button>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <section class="rounded-2xl border border-outline-variant/50 bg-white p-5 shadow-sm sm:p-7">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-secondary">Riwayat servis #{{ $history->id }}</p>
                            <h2 class="mt-1 text-2xl font-extrabold text-primary">{{ $history->service_type }}</h2>
                        </div>
                        <span class="rounded-full bg-emerald-100 px-3 py-1.5 text-sm font-bold text-emerald-800">Servis selesai</span>
                    </div>

                    <div class="mt-6 grid gap-5 border-t border-outline-variant/40 pt-5 sm:grid-cols-2">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-secondary">Pelanggan</p>
                            <p class="mt-1 font-bold text-on-surface">{{ $history->user?->name ?? 'Pelanggan tidak tersedia' }}</p>
                            <p class="mt-1 text-sm text-secondary">{{ $history->user?->phone ?? 'Nomor telepon belum tersedia' }}</p>
                            <p class="text-sm text-secondary">{{ $history->user?->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-secondary">Kendaraan</p>
                            <p class="mt-1 font-bold text-on-surface">{{ $history->vehicle?->brand }} {{ $history->vehicle?->model }}</p>
                            <p class="mt-1 text-sm text-secondary">{{ $history->vehicle?->vehicle_type ?? 'Kendaraan' }} · {{ $history->vehicle?->year ?? 'Tahun tidak tersedia' }}</p>
                            <p class="text-sm font-semibold text-secondary">{{ $history->vehicle?->plate_number ?? 'Plat nomor tidak tersedia' }}</p>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 border-t border-outline-variant/40 pt-5 sm:grid-cols-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-secondary">Tanggal masuk</p>
                            <p class="mt-1 text-sm font-semibold text-on-surface">{{ $history->entered_at?->format('d M Y, H:i') ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-secondary">Tanggal selesai</p>
                            <p class="mt-1 text-sm font-semibold text-on-surface">{{ $history->completed_at?->format('d M Y, H:i') ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-secondary">Durasi</p>
                            <p class="mt-1 text-sm font-semibold text-on-surface">
                                {{ $history->entered_at && $history->completed_at ? $history->duration . ' jam' : '—' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-outline-variant/40 pt-5">
                        <p class="text-xs font-bold uppercase tracking-wider text-secondary">Keluhan / pekerjaan servis</p>
                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-on-surface">{{ $history->description ?: 'Tidak ada rincian pekerjaan yang dicatat.' }}</p>
                    </div>

                    @if ($history->notes)
                        <div class="mt-5 rounded-xl bg-surface-container-low p-4">
                            <p class="text-xs font-bold uppercase tracking-wider text-secondary">Catatan teknisi</p>
                            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-on-surface">{{ $history->notes }}</p>
                        </div>
                    @endif
                </section>

                <section class="rounded-2xl border border-outline-variant/50 bg-white p-5 shadow-sm sm:p-7">
                    <div class="flex flex-wrap items-end justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-secondary">Tagihan aktual</p>
                            <h3 class="mt-1 text-3xl font-black tabular-nums text-primary">
                                {{ (float) $history->total_cost > 0 ? 'Rp' . number_format((float) $history->total_cost, 0, ',', '.') : 'Belum ditentukan' }}
                            </h3>
                        </div>
                        <a href="{{ route('admin.payments') }}" class="inline-flex min-h-10 items-center gap-2 rounded-xl bg-primary px-4 py-2 text-sm font-bold text-on-primary hover:bg-primary/90">
                            Kelola pembayaran <span class="material-symbols-outlined text-lg" aria-hidden="true">arrow_forward</span>
                        </a>
                    </div>
                    <p class="mt-3 text-sm text-secondary">Rincian hanya menampilkan pekerjaan dan biaya yang tersimpan untuk servis ini; item contoh tidak ditampilkan.</p>
                </section>
            </div>

            <aside class="space-y-4">
                <section class="rounded-2xl border border-outline-variant/50 bg-white p-5 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wider text-secondary">Status pembayaran</p>
                    <span class="mt-3 inline-flex rounded-full px-3 py-1.5 text-sm font-bold {{ $history->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : ($history->payment_status === 'failed' ? 'bg-rose-100 text-rose-800' : ($history->payment_submitted_at ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800')) }}">
                        {{ $history->payment_status === 'paid' ? 'Lunas' : ($history->payment_status === 'failed' ? 'Perlu dikirim ulang' : ($history->payment_submitted_at ? 'Menunggu verifikasi' : 'Belum dibayar')) }}
                    </span>
                    <p class="mt-4 text-xs font-bold uppercase tracking-wider text-secondary">Metode pembayaran</p>
                    <p class="mt-1 font-semibold text-on-surface">
                        {{ match ($history->payment_channel) {
                            'cash' => 'Tunai',
                            'bank_transfer' => 'Transfer bank',
                            'qris' => 'QRIS',
                            'dana' => 'DANA',
                            'ovo' => 'OVO',
                            'gopay' => 'GoPay',
                            'shopeepay' => 'ShopeePay',
                            'linkaja' => 'LinkAja',
                            default => match ($history->payment_method) { 'cash' => 'Tunai', 'transfer' => 'Transfer bank', 'card' => 'Kartu', default => 'Belum dipilih' },
                        } }}
                    </p>
                    @if ($history->payment_submitted_at)
                        <p class="mt-2 text-xs text-secondary">Dikirim {{ $history->payment_submitted_at->format('d M Y, H:i') }}</p>
                    @endif
                    @if ($history->payment_proof_path)
                        <a href="{{ route('admin.payments.proof', $history) }}" target="_blank" class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-primary underline">
                            <span class="material-symbols-outlined text-lg" aria-hidden="true">receipt_long</span>
                            Lihat bukti pembayaran
                        </a>
                    @endif
                </section>

                <section class="rounded-2xl border border-outline-variant/50 bg-white p-5 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wider text-secondary">Rating pelanggan</p>
                    @if ($history->rating)
                        <div class="mt-2 flex gap-1" aria-label="Rating {{ $history->rating }} dari 5">
                            @for ($star = 1; $star <= 5; $star++)
                                <span class="{{ $star <= $history->rating ? 'text-amber-400' : 'text-slate-200' }}">★</span>
                            @endfor
                        </div>
                        <p class="mt-1 text-sm text-secondary">{{ $history->rating }} dari 5</p>
                    @else
                        <p class="mt-2 text-sm text-secondary">Belum ada rating untuk servis ini.</p>
                    @endif
                </section>
            </aside>
        </div>
    </div>
@endsection
