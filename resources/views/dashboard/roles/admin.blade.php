<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-indigo-600">Bengkel AR <span class="mx-1 text-slate-300">/</span> Panel admin</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Ringkasan bengkel</h1>
                <p class="mt-1 text-sm text-slate-500">Pantau pelanggan, tim, dan aktivitas servis hari ini.</p>
            </div>
            <div class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-600 shadow-sm">
                <svg class="h-4 w-4 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M7 3v3m10-3v3M4 9h16M5 5h14a1 1 0 0 1 1 1v14H4V6a1 1 0 0 1 1-1Z"/></svg>
                Hari ini · {{ now()->format('d/m/Y') }}
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-7 px-4 py-7 sm:px-6 lg:px-8">
        @if (session('status'))
            <div role="status" class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm text-emerald-800">
                <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <section aria-label="Statistik bengkel" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6">
            @foreach ([
                ['Pelanggan', $customerCount, 'Akun pelanggan', 'users', 'bg-indigo-50 text-indigo-700'],
                ['Mekanik', $mechanicCount, 'Tim teknis', 'wrench', 'bg-violet-50 text-violet-700'],
                ['Menunggu', $queueCounts->get('waiting', 0), 'Perlu ditangani', 'clock', 'bg-amber-50 text-amber-700'],
                ['Dikerjakan', $queueCounts->get('in_progress', 0), 'Servis berjalan', 'tool', 'bg-blue-50 text-blue-700'],
                ['Selesai hari ini', $completedTodayCount, 'Pekerjaan tuntas', 'check', 'bg-emerald-50 text-emerald-700'],
            ] as [$label, $value, $hint, $icon, $iconClass])
                <article class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-indigo-100 hover:shadow-md">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-500">{{ $label }}</p>
                            <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($value) }}</p>
                        </div>
                        <span class="grid h-10 w-10 place-items-center rounded-xl {{ $iconClass }}">
                            @if ($icon === 'users')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M16 20v-1.5a4.5 4.5 0 0 0-4.5-4.5h-3A4.5 4.5 0 0 0 4 18.5V20m6-10a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm7-6a3 3 0 0 1 0 5.8m1 4.4a4.5 4.5 0 0 1 2 3.8V20"/></svg>
                            @elseif ($icon === 'wrench')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M14.7 6.3a5 5 0 0 0-6.4 6.4L3.5 17.5a2.1 2.1 0 0 0 3 3l4.8-4.8a5 5 0 0 0 6.4-6.4l-3.1 3.1-3.1-3.1 3.2-3Z"/></svg>
                            @elseif ($icon === 'clock')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                            @elseif ($icon === 'tool')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M14.7 6.3a5 5 0 0 0-6.4 6.4L3.5 17.5a2.1 2.1 0 0 0 3 3l4.8-4.8a5 5 0 0 0 6.4-6.4l-3.1 3.1-3.1-3.1 3.2-3Z"/></svg>
                            @else
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg>
                            @endif
                        </span>
                    </div>
                    <p class="mt-3 border-t border-slate-100 pt-3 text-xs font-medium text-slate-400">{{ $hint }}</p>
                </article>
            @endforeach
            <a href="{{ route('admin.history.index') }}" class="group rounded-2xl border border-slate-200 border-t-4 border-t-emerald-500 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-slate-500">Pendapatan bulan ini</p>
                        <p class="mt-3 text-2xl font-bold tracking-tight text-slate-900">Rp{{ number_format((float) $monthlyRevenue, 0, ',', '.') }}</p>
                    </div>
                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-emerald-50 text-emerald-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18m-13 5h4"/></svg>
                    </span>
                </div>
                <p class="mt-3 flex items-center justify-between border-t border-slate-100 pt-3 text-xs font-medium text-slate-400">
                    <span>Pembayaran yang sudah lunas</span>
                    <span class="font-bold text-emerald-700 group-hover:underline">Lihat riwayat</span>
                </p>
            </a>
        </section>

        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-7">
                <div class="flex items-center gap-3">
                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-indigo-50 text-indigo-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="6" cy="17" r="3"/><circle cx="18" cy="17" r="3"/><path d="m8 17 4-8h3l3 8M9 14h8M12 9l-2-2H7"/></svg>
                    </span>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Antrean servis terbaru</h2>
                        <p class="mt-1 text-sm text-slate-500">Aktivitas kendaraan yang tercatat di bengkel.</p>
                    </div>
                </div>
                <span class="w-fit rounded-full bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-600">{{ $recentQueues->count() }} antrean terbaru</span>
            </div>

            @if ($recentQueues->isEmpty())
                <div class="flex flex-col items-center px-6 py-14 text-center">
                    <span class="grid h-14 w-14 place-items-center rounded-2xl bg-indigo-50 text-indigo-600">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="6" cy="17" r="3"/><circle cx="18" cy="17" r="3"/><path d="m8 17 4-8h3l3 8M9 14h8M12 9l-2-2H7"/></svg>
                    </span>
                    <h3 class="mt-4 font-bold text-slate-900">Belum ada antrean servis</h3>
                    <p class="mt-1 max-w-sm text-sm leading-6 text-slate-500">Antrean akan muncul di sini saat kendaraan dicatat untuk servis.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                        <thead class="bg-slate-50/80">
                            <tr>
                                <th scope="col" class="whitespace-nowrap px-5 py-3.5 text-xs font-bold uppercase tracking-wide text-slate-400 sm:px-7">Pelanggan</th>
                                <th scope="col" class="whitespace-nowrap px-5 py-3.5 text-xs font-bold uppercase tracking-wide text-slate-400">Kendaraan</th>
                                <th scope="col" class="whitespace-nowrap px-5 py-3.5 text-xs font-bold uppercase tracking-wide text-slate-400">Prioritas</th>
                                <th scope="col" class="whitespace-nowrap px-5 py-3.5 text-xs font-bold uppercase tracking-wide text-slate-400">Status</th>
                                <th scope="col" class="whitespace-nowrap px-5 py-3.5 text-xs font-bold uppercase tracking-wide text-slate-400 sm:pr-7">Masuk</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($recentQueues as $queue)
                                @php
                                    $statusStyle = match ($queue->status) {
                                        'waiting' => 'bg-amber-50 text-amber-800 ring-amber-200',
                                        'in_progress' => 'bg-blue-50 text-blue-800 ring-blue-200',
                                        'completed' => 'bg-emerald-50 text-emerald-800 ring-emerald-200',
                                        'cancelled' => 'bg-slate-100 text-slate-600 ring-slate-200',
                                        default => 'bg-slate-100 text-slate-700 ring-slate-200',
                                    };
                                    $priorityStyle = match ($queue->priority) {
                                        'high' => 'text-rose-700',
                                        'medium' => 'text-amber-700',
                                        default => 'text-slate-500',
                                    };
                                    $vehicleName = trim(($queue->vehicle?->brand ?? '') . ' ' . ($queue->vehicle?->model ?? ''));
                                @endphp
                                <tr class="transition hover:bg-slate-50/80">
                                    <td class="whitespace-nowrap px-5 py-4 sm:px-7">
                                        <div class="flex items-center gap-3">
                                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-slate-100 text-xs font-bold text-slate-600">{{ strtoupper(substr($queue->user?->name ?? 'P', 0, 1)) }}</span>
                                            <span class="font-semibold text-slate-800">{{ $queue->user?->name ?? 'Pelanggan dihapus' }}</span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <p class="font-semibold text-slate-800">{{ $vehicleName !== '' ? $vehicleName : 'Kendaraan' }}</p>
                                        <p class="mt-0.5 text-xs text-slate-400">{{ $queue->vehicle?->plate_number ?? 'Nomor polisi tidak tersedia' }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold {{ $priorityStyle }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $queue->priority === 'high' ? 'bg-rose-500' : ($queue->priority === 'medium' ? 'bg-amber-500' : 'bg-slate-400') }}"></span>
                                            {{ match ($queue->priority) { 'high' => 'Tinggi', 'medium' => 'Sedang', default => 'Rendah' } }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-bold ring-1 ring-inset {{ $statusStyle }}">
                                            {{ match ($queue->status) { 'waiting' => 'Menunggu', 'in_progress' => 'Dikerjakan', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan', default => ucfirst($queue->status) } }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-slate-500 sm:pr-7">{{ $queue->entered_at?->format('d M Y, H:i') ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
