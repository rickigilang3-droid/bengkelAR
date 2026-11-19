<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-indigo-600">Bengkel AR <span class="mx-1 text-slate-300">/</span> Portal pelanggan</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Halo, {{ auth()->user()->name }}</h1>
                <p class="mt-1 text-sm text-slate-500">Semua informasi kendaraan dan servis Anda dalam satu tempat.</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="8" r="3.5"/><path d="M5 20c.7-3.1 3.2-5 7-5s6.3 1.9 7 5M19 4v5m-2.5-2.5h5"/></svg>
                Kelola akun
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-7 px-4 py-7 sm:px-6 lg:px-8">
        @if (session('status'))
            <div role="status" class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm text-emerald-800">
                <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <section aria-label="Ringkasan akun" class="grid gap-4 lg:grid-cols-[0.8fr_1.7fr]">
            <article class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-800 via-indigo-700 to-blue-600 p-6 text-white shadow-lg shadow-indigo-900/10 sm:p-8">
                <div class="pointer-events-none absolute -right-12 -top-16 h-48 w-48 rounded-full border border-white/10"></div>
                <div class="pointer-events-none absolute -right-4 -top-8 h-32 w-32 rounded-full border border-white/10"></div>
                <div class="relative">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-semibold text-indigo-50">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span>
                        Ringkasan akun
                    </span>
                    <p class="mt-7 text-sm font-medium text-indigo-100">Kendaraan terdaftar</p>
                    <div class="mt-1 flex items-end gap-3">
                        <p class="text-5xl font-bold tracking-tight">{{ number_format($vehicleCount) }}</p>
                        <p class="mb-1.5 text-sm text-indigo-100">kendaraan</p>
                    </div>
                    <p class="mt-4 max-w-xs text-sm leading-6 text-indigo-100">Data kendaraan Anda tersimpan aman dan mudah ditemukan saat dibutuhkan.</p>
                    <a href="{{ route('profile.edit') }}" class="mt-6 inline-flex min-h-10 items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-bold text-indigo-800 transition hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-700">
                        Lihat akun
                        <span aria-hidden="true">→</span>
                    </a>
                </div>
            </article>

            <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-7">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Status terkini</p>
                        <h2 class="mt-1 text-lg font-bold text-slate-900">Servis aktif</h2>
                    </div>
                    @if ($activeQueue)
                        <span class="inline-flex w-fit items-center gap-2 rounded-full px-3 py-1.5 text-xs font-bold {{ $activeQueue->status === 'in_progress' ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-800' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $activeQueue->status === 'in_progress' ? 'bg-blue-500' : 'bg-amber-500' }}"></span>
                            {{ $activeQueue->status === 'in_progress' ? 'Sedang dikerjakan' : 'Menunggu giliran' }}
                        </span>
                    @else
                        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">Tidak ada servis aktif</span>
                    @endif
                </div>

                @if ($activeQueue)
                    @php
                        $vehicleName = trim(($activeQueue->vehicle?->brand ?? '') . ' ' . ($activeQueue->vehicle?->model ?? ''));
                    @endphp
                    <div class="grid gap-6 px-5 py-6 sm:grid-cols-[1fr_auto] sm:items-center sm:px-7">
                        <div class="flex min-w-0 items-start gap-4">
                            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-indigo-50 text-indigo-700">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="6" cy="17" r="3"/><circle cx="18" cy="17" r="3"/><path d="m8 17 4-8h3l3 8M9 14h8M12 9l-2-2H7"/></svg>
                            </span>
                            <div class="min-w-0">
                                <h3 class="truncate text-lg font-bold text-slate-900">{{ $vehicleName !== '' ? $vehicleName : 'Kendaraan Anda' }}</h3>
                                <p class="mt-1 text-sm font-medium tracking-wide text-slate-500">{{ $activeQueue->vehicle?->plate_number ?? 'Nomor polisi belum tersedia' }}</p>
                                <p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-600">{{ $activeQueue->complaint }}</p>
                            </div>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-4 py-3 sm:min-w-40">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Kendaraan masuk</p>
                            <p class="mt-1 text-sm font-bold text-slate-800">{{ $activeQueue->entered_at?->format('d M Y') ?? '—' }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">{{ $activeQueue->entered_at?->format('H:i') ?? '' }}</p>
                        </div>
                    </div>
                    <div class="px-5 pb-6 sm:px-7">
                        <div class="flex items-center justify-between text-xs font-semibold">
                            <span class="text-slate-500">Progres servis</span>
                            <span class="text-indigo-700">{{ $activeQueue->status === 'in_progress' ? 'Sedang dikerjakan' : 'Menunggu giliran' }}</span>
                        </div>
                        <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-gradient-to-r from-indigo-600 to-blue-500 transition-all {{ $activeQueue->status === 'in_progress' ? 'w-2/3' : 'w-1/3' }}"></div>
                        </div>
                        <div class="mt-2 flex justify-between text-[11px] font-medium text-slate-400">
                            <span>Diterima</span><span>Dikerjakan</span><span>Selesai</span>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-center px-6 py-10 text-center">
                        <span class="grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-slate-500">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                        </span>
                        <h3 class="mt-4 font-bold text-slate-900">Belum ada servis berjalan</h3>
                        <p class="mt-1 max-w-sm text-sm leading-6 text-slate-500">Jika kendaraan Anda masuk antrean servis, informasi progresnya akan tampil di sini.</p>
                    </div>
                @endif
            </article>
        </section>

        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-2 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-7">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="grid h-9 w-9 place-items-center rounded-xl bg-violet-50 text-violet-700">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M7 3v3m10-3v3M4 9h16M5 5h14a1 1 0 0 1 1 1v13H4V6a1 1 0 0 1 1-1Z"/><path d="m8 14 2 2 5-5"/></svg>
                        </span>
                        <h2 class="text-lg font-bold text-slate-900">Riwayat servis</h2>
                    </div>
                    <p class="mt-2 text-sm text-slate-500">Catatan perawatan kendaraan yang telah selesai.</p>
                </div>
                <span class="w-fit rounded-full bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-600">{{ $recentHistory->count() }} terbaru</span>
            </div>

            @if ($recentHistory->isEmpty())
                <div class="flex flex-col items-center px-6 py-12 text-center">
                    <span class="grid h-14 w-14 place-items-center rounded-2xl bg-violet-50 text-violet-600">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M7 3v3m10-3v3M4 9h16M5 5h14a1 1 0 0 1 1 1v13H4V6a1 1 0 0 1 1-1Z"/><path d="M8 13h3m-3 4h8"/></svg>
                    </span>
                    <h3 class="mt-4 font-bold text-slate-900">Riwayat Anda akan tampil di sini</h3>
                    <p class="mt-1 max-w-sm text-sm leading-6 text-slate-500">Setelah servis selesai, detail pekerjaan dan biayanya akan tersimpan di bagian ini.</p>
                </div>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($recentHistory as $history)
                        <li class="flex flex-col gap-4 px-5 py-4 transition hover:bg-slate-50/70 sm:flex-row sm:items-center sm:justify-between sm:px-7">
                            <div class="flex min-w-0 items-start gap-3">
                                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-slate-100 text-slate-600">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="m14.7 6.3a5 5 0 0 0-6.4 6.4L3.5 17.5a2.1 2.1 0 0 0 3 3l4.8-4.8a5 5 0 0 0 6.4-6.4l-3.1 3.1-3.1-3.1 3.2-3Z"/></svg>
                                </span>
                                <div class="min-w-0">
                                    <p class="truncate font-bold text-slate-900">{{ $history->service_type }}</p>
                                    <p class="mt-1 truncate text-sm text-slate-500">
                                        {{ trim(($history->vehicle?->brand ?? '') . ' ' . ($history->vehicle?->model ?? '')) ?: 'Kendaraan' }}
                                        @if ($history->vehicle?->plate_number) · {{ $history->vehicle->plate_number }} @endif
                                        @if ($history->completed_at) · {{ $history->completed_at->format('d M Y') }} @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between gap-4 pl-[52px] sm:justify-end sm:pl-0">
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">Selesai</span>
                                @if ($history->total_cost !== null)
                                    <p class="font-bold tabular-nums text-slate-900">Rp{{ number_format((float) $history->total_cost, 0, ',', '.') }}</p>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
</x-app-layout>
