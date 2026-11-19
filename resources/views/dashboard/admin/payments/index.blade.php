@extends('dashboard.admin.layouts.app')

@section('title', 'Pembayaran Servis')
@section('content')
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-on-surface">Pembayaran Servis</h2>
            <p class="mt-1 text-sm text-secondary">Atur tagihan, periksa bukti transfer, dan konfirmasi pembayaran pelanggan.</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <article class="rounded-2xl border border-outline-variant/50 bg-surface-container-lowest p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-secondary">Menunggu verifikasi</p>
                <p class="mt-2 text-3xl font-black text-on-surface">{{ number_format($awaitingVerification) }}</p>
            </article>
            <article class="rounded-2xl border border-outline-variant/50 bg-surface-container-lowest p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-secondary">Total tagihan belum lunas</p>
                <p class="mt-2 text-3xl font-black text-on-surface">Rp{{ number_format((float) $unpaidTotal, 0, ',', '.') }}</p>
            </article>
        </div>

        <div class="space-y-4">
            @forelse ($histories as $history)
                <article class="rounded-2xl border border-outline-variant/50 bg-surface-container-lowest p-5 shadow-sm">
                    <div class="flex flex-col gap-4 border-b border-outline-variant/30 pb-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-bold text-on-surface">{{ $history->user?->name }} · {{ $history->service_type }}</h3>
                                <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $history->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : ($history->payment_status === 'failed' ? 'bg-rose-100 text-rose-800' : ($history->payment_submitted_at ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800')) }}">
                                    {{ $history->payment_status === 'paid' ? 'Lunas' : ($history->payment_status === 'failed' ? 'Perlu dikirim ulang' : ($history->payment_submitted_at ? 'Menunggu verifikasi' : 'Belum dibayar')) }}
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-secondary">#{{ $history->id }} · {{ $history->vehicle?->brand }} {{ $history->vehicle?->model }} ({{ $history->vehicle?->plate_number }}) · {{ $history->completed_at?->format('d M Y') }}</p>
                            <details class="mt-2 text-sm">
                                <summary class="cursor-pointer font-bold text-primary">Lihat nota pekerjaan dan item</summary>
                                <p class="mt-2 whitespace-pre-line leading-6 text-secondary">{{ $history->description }}</p>
                            </details>
                            @if ($history->payment_channel)
                                <p class="mt-2 text-xs font-semibold text-secondary">Metode pelanggan: {{ str_replace('_', ' ', strtoupper($history->payment_channel)) }}</p>
                            @endif
                        </div>
                        @if ($history->payment_proof_path)
                            <a href="{{ route('admin.payments.proof', $history) }}" class="inline-flex min-h-10 shrink-0 items-center justify-center gap-2 rounded-xl border border-indigo-200 px-4 py-2 text-sm font-bold text-indigo-700 hover:bg-indigo-50" target="_blank">
                                <span class="material-symbols-outlined text-lg" aria-hidden="true">receipt_long</span>
                                Lihat bukti pembayaran
                            </a>
                        @elseif ($history->payment_submitted_at && $history->payment_method === 'cash')
                            <span class="inline-flex min-h-10 items-center gap-2 rounded-xl bg-amber-50 px-4 py-2 text-sm font-bold text-amber-800">Konfirmasi tunai di bengkel</span>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('admin.payments.update', $history) }}" class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="cost-{{ $history->id }}" class="mb-1 block text-xs font-bold text-secondary">Total tagihan (Rp)</label>
                            <input id="cost-{{ $history->id }}" type="number" min="0" max="9999999999" step="1" name="total_cost" value="{{ (int) $history->total_cost }}" required class="w-full rounded-xl border border-outline-variant bg-white px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label for="method-{{ $history->id }}" class="mb-1 block text-xs font-bold text-secondary">Metode pembayaran</label>
                            <select id="method-{{ $history->id }}" name="payment_channel" class="w-full rounded-xl border border-outline-variant bg-white px-3 py-2 text-sm">
                                <option value="">Belum ditentukan</option>
                                <option value="cash" @selected($history->payment_channel === 'cash')>Tunai</option>
                                <option value="bank_transfer" @selected($history->payment_channel === 'bank_transfer')>Transfer bank</option>
                                <option value="qris" @selected($history->payment_channel === 'qris')>QRIS</option>
                                <option value="dana" @selected($history->payment_channel === 'dana')>DANA</option>
                                <option value="ovo" @selected($history->payment_channel === 'ovo')>OVO</option>
                                <option value="gopay" @selected($history->payment_channel === 'gopay')>GoPay</option>
                                <option value="shopeepay" @selected($history->payment_channel === 'shopeepay')>ShopeePay</option>
                                <option value="linkaja" @selected($history->payment_channel === 'linkaja')>LinkAja</option>
                            </select>
                        </div>
                        <div>
                            <label for="status-{{ $history->id }}" class="mb-1 block text-xs font-bold text-secondary">Status pembayaran</label>
                            <select id="status-{{ $history->id }}" name="payment_status" class="w-full rounded-xl border border-outline-variant bg-white px-3 py-2 text-sm">
                                <option value="pending" @selected($history->payment_status === 'pending')>{{ $history->payment_submitted_at ? 'Menunggu verifikasi' : 'Belum dibayar' }}</option>
                                <option value="paid" @selected($history->payment_status === 'paid')>Lunas</option>
                                <option value="failed" @selected($history->payment_status === 'failed')>Ditolak / perlu dikirim ulang</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="inline-flex min-h-10 w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 py-2 text-sm font-bold text-on-primary hover:bg-primary/90">
                                <span class="material-symbols-outlined text-lg" aria-hidden="true">save</span>
                                Simpan pembayaran
                            </button>
                        </div>
                    </form>
                </article>
            @empty
                <div class="rounded-2xl border border-outline-variant/50 bg-surface-container-lowest px-6 py-14 text-center">
                    <span class="material-symbols-outlined text-4xl text-secondary/40" aria-hidden="true">payments</span>
                    <h3 class="mt-3 font-bold text-on-surface">Belum ada riwayat pembayaran</h3>
                </div>
            @endforelse
        </div>

        @if ($histories->hasPages())
            <div>{{ $histories->links() }}</div>
        @endif
    </div>
@endsection
