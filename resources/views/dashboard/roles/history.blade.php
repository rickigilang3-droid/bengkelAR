<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-indigo-600">Portal pelanggan <span class="mx-1 text-slate-300">/</span> Riwayat</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Riwayat servis</h1>
            <p class="mt-1 text-sm text-slate-500">Lihat pekerjaan dan biaya servis kendaraan Anda.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-7 sm:px-6 lg:px-8">
        <section class="grid gap-4 sm:grid-cols-2">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-semibold text-slate-500">Total kunjungan servis</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($totalServices) }}</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-semibold text-slate-500">Total biaya tercatat</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">Rp{{ number_format((float) $totalSpent, 0, ',', '.') }}</p>
            </article>
        </section>

        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-5 sm:px-7">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Catatan kendaraan</p>
                <h2 class="mt-1 text-xl font-bold text-slate-900">Semua riwayat servis</h2>
            </div>
            @if ($histories->isEmpty())
                <div class="px-6 py-14 text-center">
                    <p class="font-bold text-slate-800">Riwayat servis belum tersedia</p>
                    <p class="mt-1 text-sm text-slate-500">Setelah servis selesai, catatannya akan tampil di halaman ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-500 sm:px-7">Tanggal</th>
                                <th scope="col" class="px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-500">Kendaraan</th>
                                <th scope="col" class="px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-500">Pekerjaan</th>
                                <th scope="col" class="px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-500">Pembayaran</th>
                                <th scope="col" class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wide text-slate-500 sm:pr-7">Biaya</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($histories as $history)
                                <tr class="align-top">
                                    <td class="whitespace-nowrap px-5 py-4 text-slate-600 sm:px-7">{{ $history->completed_at?->format('d M Y') ?? '—' }}</td>
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-slate-800">{{ $history->vehicle?->brand }} {{ $history->vehicle?->model }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $history->vehicle?->plate_number }}</p>
                                    </td>
                                    <td class="max-w-sm px-5 py-4">
                                        <p class="font-semibold text-slate-800">{{ $history->service_type }}</p>
                                        <details class="mt-1 text-xs">
                                            <summary class="cursor-pointer font-semibold text-indigo-700">Lihat nota servis</summary>
                                            <p class="mt-2 whitespace-pre-line leading-5 text-slate-600">{{ $history->description }}</p>
                                            @if ($history->notes)
                                                <p class="mt-2 whitespace-pre-line text-slate-500">Catatan: {{ $history->notes }}</p>
                                            @endif
                                        </details>
                                        <a href="{{ route('customer.history.show', $history) }}" class="mt-2 inline-flex items-center gap-1 text-xs font-bold text-indigo-700 hover:underline">
                                            Buka nota servis <span class="material-symbols-outlined text-sm" aria-hidden="true">arrow_forward</span>
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $history->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700' : ($history->payment_status === 'failed' ? 'bg-rose-50 text-rose-700' : ($history->payment_submitted_at ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-800')) }}">
                                            {{ $history->payment_status === 'paid' ? 'Lunas' : ($history->payment_status === 'failed' ? 'Perlu dikirim ulang' : ($history->payment_submitted_at ? 'Menunggu verifikasi' : 'Belum lunas')) }}
                                        </span>
                                        @if ($history->payment_channel)
                                            <p class="mt-1 text-xs text-slate-500">{{ str_replace('_', ' ', strtoupper($history->payment_channel)) }}</p>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-right font-bold tabular-nums text-slate-900 sm:pr-7">{{ (float) $history->total_cost > 0 ? 'Rp' . number_format((float) $history->total_cost, 0, ',', '.') : 'Belum ditentukan' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-slate-100 px-5 py-4 sm:px-7">{{ $histories->links() }}</div>
            @endif
        </section>
    </div>
</x-app-layout>
