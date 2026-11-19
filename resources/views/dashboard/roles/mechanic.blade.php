<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-indigo-600">Bengkel AR · Ruang kerja teknis</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Dashboard mekanik</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola antrean, catatan pemeriksaan, kilometer, serta pengajuan sparepart.</p>
        </div>
    </x-slot>

    <main class="mx-auto max-w-7xl space-y-5 px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <p class="font-bold">Periksa kembali data pekerjaan:</p>
                <ul class="mt-1 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <section aria-label="Ringkasan pekerjaan" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['label' => 'Menunggu', 'value' => $queueCounts['waiting'] ?? 0, 'status' => 'waiting'],
                ['label' => 'Sedang dikerjakan', 'value' => $queueCounts['in_progress'] ?? 0, 'status' => 'in_progress'],
                ['label' => 'Selesai', 'value' => $queueCounts['completed'] ?? 0, 'status' => 'completed'],
                ['label' => 'Pengajuan menunggu', 'value' => $pendingApprovalCount, 'status' => 'pending_approval'],
            ] as $stat)
                <a href="{{ route('dashboard.mechanic', ['status' => $stat['status']]) }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-indigo-300">
                    <p class="text-sm font-medium text-slate-500">{{ $stat['label'] }}</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $stat['value'] }}</p>
                </a>
            @endforeach
        </section>

        <form method="GET" action="{{ route('dashboard.mechanic') }}" class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row">
            <label class="sr-only" for="queue-search">Cari pekerjaan</label>
            <input id="queue-search" name="search" value="{{ $search }}" maxlength="100" class="min-h-11 flex-1 rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Cari pelanggan, plat nomor, motor, atau keluhan">
            <label class="sr-only" for="queue-status">Filter status</label>
            <select id="queue-status" name="status" class="min-h-11 rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="active" @selected($statusFilter === 'active')>Pekerjaan aktif</option>
                <option value="waiting" @selected($statusFilter === 'waiting')>Menunggu</option>
                <option value="in_progress" @selected($statusFilter === 'in_progress')>Sedang dikerjakan</option>
                <option value="completed" @selected($statusFilter === 'completed')>Selesai</option>
                <option value="cancelled" @selected($statusFilter === 'cancelled')>Dibatalkan</option>
                <option value="pending_approval" @selected($statusFilter === 'pending_approval')>Pengajuan menunggu</option>
                <option value="all" @selected($statusFilter === 'all')>Semua status</option>
            </select>
            <button type="submit" class="min-h-11 rounded-xl bg-indigo-700 px-5 py-2 text-sm font-bold text-white hover:bg-indigo-800">Terapkan</button>
        </form>

        @if ($queues->isEmpty())
            <section class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center">
                <h2 class="font-semibold text-slate-900">Tidak ada pekerjaan pada filter ini</h2>
                <p class="mt-2 text-sm text-slate-500">Coba ubah pencarian atau pilih status lainnya.</p>
            </section>
        @else
            <section aria-label="Daftar antrean servis" class="space-y-4">
                @foreach ($queues as $queue)
                    @php($isActive = in_array($queue->status, ['waiting', 'in_progress'], true))
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h2 class="text-lg font-semibold text-slate-900">{{ $queue->vehicle?->brand }} {{ $queue->vehicle?->model }}</h2>
                                    <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-800">{{ str_replace('_', ' ', ucfirst($queue->status)) }}</span>
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">Prioritas {{ ucfirst($queue->priority) }}</span>
                                </div>
                                <p class="mt-2 text-sm text-slate-600">{{ $queue->vehicle?->plate_number }} · {{ $queue->user?->name ?? 'Pelanggan dihapus' }}</p>
                                <p class="mt-4 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $queue->complaint }}</p>
                                @if ($queue->user?->phone)
                                    <p class="mt-2 text-sm text-slate-600">Kontak pelanggan: {{ $queue->user->phone }}</p>
                                @endif
                                @if ($queue->vehicle?->current_odometer_km !== null)
                                    <p class="mt-3 text-sm font-semibold text-slate-700">Odometer terakhir: {{ number_format($queue->vehicle->current_odometer_km, 0, ',', '.') }} km</p>
                                @else
                                    <p class="mt-3 text-sm font-semibold text-amber-700">Odometer belum dicatat</p>
                                @endif
                                @if ($queue->entered_at)
                                    <p class="mt-3 text-xs text-slate-500">Masuk {{ $queue->entered_at->format('d M Y, H:i') }}</p>
                                @endif
                            </div>

                            @if ($isActive)
                                <form method="POST" action="{{ route('dashboard.mechanic.queue-status', $queue) }}" class="shrink-0">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $queue->status === 'waiting' ? 'in_progress' : 'completed' }}">
                                    <button type="submit" class="inline-flex min-h-11 items-center justify-center rounded-xl bg-indigo-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        {{ $queue->status === 'waiting' ? 'Mulai pekerjaan' : 'Tandai selesai' }}
                                    </button>
                                </form>
                            @endif
                        </div>

                        @if ($isActive)
                            <div class="mt-5 grid gap-4 border-t border-slate-100 pt-5 lg:grid-cols-2">
                                <div class="space-y-4">
                                    <form method="POST" action="{{ route('dashboard.mechanic.queue-notes', $queue) }}" class="space-y-3">
                                        @csrf
                                        @method('PATCH')
                                        <div>
                                            <label for="work-notes-{{ $queue->id }}" class="block text-sm font-bold text-slate-800">Catatan pemeriksaan dan pekerjaan</label>
                                            <textarea id="work-notes-{{ $queue->id }}" name="notes" rows="3" maxlength="5000" class="mt-2 block w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Hasil pemeriksaan, pekerjaan yang dilakukan, atau catatan untuk admin">{{ old('notes', $queue->notes) }}</textarea>
                                        </div>
                                        <button type="submit" class="inline-flex min-h-10 items-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50">Simpan catatan</button>
                                    </form>

                                    <form method="POST" action="{{ route('dashboard.mechanic.queue-odometer', $queue) }}" class="space-y-3 rounded-xl bg-slate-50 p-4">
                                        @csrf
                                        @method('PATCH')
                                        <div>
                                            <label for="queue-odometer-{{ $queue->id }}" class="block text-sm font-bold text-slate-800">Perbarui kilometer kendaraan</label>
                                            <input id="queue-odometer-{{ $queue->id }}" name="odometer_km" type="number" min="{{ $queue->vehicle?->current_odometer_km ?? 0 }}" max="9999999" value="{{ old('odometer_km', $queue->odometer_km ?? $queue->vehicle?->current_odometer_km) }}" required class="mt-2 block w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <button type="submit" class="inline-flex min-h-10 items-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-bold text-slate-700 hover:bg-white">Simpan kilometer</button>
                                    </form>
                                </div>

                                <form method="POST" action="{{ route('dashboard.mechanic.queue-parts', $queue) }}" class="space-y-3">
                                    @csrf
                                    <h3 class="text-sm font-bold text-slate-800">Ajukan sparepart ke pelanggan</h3>
                                    <p class="text-xs leading-5 text-slate-600">Pelanggan harus menyetujui sebelum biaya ditambahkan dan stok inventaris dikurangi.</p>
                                    <div>
                                        <label for="inventory-{{ $queue->id }}" class="sr-only">Pilih sparepart inventaris</label>
                                        <select id="inventory-{{ $queue->id }}" name="inventory_id" required class="mt-1 block w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">Pilih sparepart inventaris</option>
                                            @foreach ($inventories as $inventory)
                                                <option value="{{ $inventory->id }}" @selected(old('inventory_id') == $inventory->id)>{{ $inventory->name }} · stok {{ $inventory->stock }} {{ $inventory->unit }} · Rp{{ number_format((float) $inventory->selling_price, 0, ',', '.') }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($inventories->isEmpty())
                                        <p class="text-xs text-amber-700">Stok inventaris kosong. Minta admin menambahkan stok sebelum mengajukan sparepart.</p>
                                    @endif
                                    <div class="grid gap-3 sm:grid-cols-3">
                                        <div>
                                            <label for="quantity-{{ $queue->id }}" class="mb-1 block text-xs font-semibold text-slate-600">Jumlah</label>
                                            <input id="quantity-{{ $queue->id }}" name="quantity" type="number" min="1" max="100" value="{{ old('quantity', 1) }}" required class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label for="part-odometer-{{ $queue->id }}" class="mb-1 block text-xs font-semibold text-slate-600">Kilometer saat ini</label>
                                            <input id="part-odometer-{{ $queue->id }}" name="odometer_km" type="number" min="{{ $queue->vehicle?->current_odometer_km ?? 0 }}" max="9999999" value="{{ old('odometer_km', $queue->odometer_km ?? $queue->vehicle?->current_odometer_km) }}" required class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label for="interval-{{ $queue->id }}" class="mb-1 block text-xs font-semibold text-slate-600">Pengingat berikutnya (km)</label>
                                            <input id="interval-{{ $queue->id }}" name="interval_km" type="number" min="1" max="9999999" value="{{ old('interval_km') }}" required class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                    </div>
                                    <details class="rounded-lg bg-slate-50 px-3 py-2 text-xs text-slate-600">
                                        <summary class="cursor-pointer font-semibold">Panduan interval perawatan umum</summary>
                                        <ul class="mt-2 space-y-2">
                                            @foreach ($maintenanceGuides as $partName => $guide)
                                                <li><strong>{{ $partName }}</strong> · {{ $guide['action'] }} sekitar {{ number_format($guide['interval_km'], 0, ',', '.') }} km. {{ $guide['guidance'] }}</li>
                                            @endforeach
                                        </ul>
                                    </details>
                                    <div>
                                        <label for="part-notes-{{ $queue->id }}" class="mb-1 block text-xs font-semibold text-slate-600">Diagnosa atau catatan (opsional)</label>
                                        <input id="part-notes-{{ $queue->id }}" name="notes" value="{{ old('notes') }}" maxlength="1000" class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: kondisi komponen dan alasan penggantian">
                                    </div>
                                    <button type="submit" @disabled($inventories->isEmpty()) class="inline-flex min-h-10 items-center rounded-xl bg-indigo-700 px-4 py-2 text-sm font-bold text-white hover:bg-indigo-800 disabled:cursor-not-allowed disabled:opacity-50">Kirim untuk persetujuan</button>
                                </form>
                            </div>
                        @elseif ($queue->notes)
                            <div class="mt-5 rounded-xl bg-slate-50 p-4">
                                <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">Catatan pekerjaan</h3>
                                <p class="mt-2 whitespace-pre-line text-sm text-slate-700">{{ $queue->notes }}</p>
                            </div>
                        @endif

                        @if ($queue->approvalItems->isNotEmpty())
                            <div class="mt-4 rounded-xl border border-slate-100 p-4">
                                <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">Pengajuan sparepart dan keputusan pelanggan</h3>
                                <ul class="mt-2 space-y-2 text-sm text-slate-700">
                                    @foreach ($queue->approvalItems as $approval)
                                        <li class="flex flex-wrap items-center justify-between gap-2">
                                            <span>{{ $approval->item_name }} × {{ $approval->quantity }} · Rp{{ number_format((float) $approval->estimated_cost, 0, ',', '.') }}</span>
                                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $approval->status === 'approved' ? 'bg-emerald-50 text-emerald-700' : ($approval->status === 'rejected' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700') }}">{{ ucfirst($approval->status) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if ($queue->partReplacements->isNotEmpty())
                            <div class="mt-4 rounded-xl bg-slate-50 p-4">
                                <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500">Sparepart yang sudah disetujui dan tercatat</h3>
                                <ul class="mt-2 space-y-1 text-sm text-slate-700">
                                    @foreach ($queue->partReplacements as $replacement)
                                        <li>{{ $replacement->inventory?->name ?? 'Sparepart dihapus' }} × {{ $replacement->quantity }}{{ $replacement->notes ? ' · ' . $replacement->notes : '' }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </article>
                @endforeach
            </section>

            <div>{{ $queues->links() }}</div>
        @endif
    </main>
</x-app-layout>
