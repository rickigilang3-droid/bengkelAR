<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-indigo-600">Portal pelanggan <span class="mx-1 text-slate-300">/</span> Garasi</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Kendaraan saya</h1>
            <p class="mt-1 text-sm text-slate-500">Simpan data kendaraan agar booking dan riwayat servis mudah dikelola.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-7 px-4 py-7 sm:px-6 lg:px-8">
        @if (session('status') || session('error') || $errors->any())
            <div role="{{ session('error') || $errors->any() ? 'alert' : 'status' }}" class="rounded-2xl border px-4 py-3 text-sm {{ session('error') || $errors->any() ? 'border-rose-200 bg-rose-50 text-rose-800' : 'border-emerald-200 bg-emerald-50 text-emerald-800' }}">
                <p class="font-semibold">{{ session('error') ?? session('status') ?? 'Periksa kembali data kendaraan.' }}</p>
                @if ($errors->any())<ul class="mt-2 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>@endif
            </div>
        @endif

        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
            <div class="mb-5">
                <p class="text-xs font-bold uppercase tracking-wider text-indigo-600">Garasi pribadi</p>
                <h2 class="mt-1 text-xl font-bold text-slate-900">Tambah kendaraan</h2>
            </div>
            <form method="POST" action="{{ route('customer.vehicles.store') }}" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @csrf
                <div><label for="vehicle_type" class="mb-1.5 block text-sm font-semibold text-slate-700">Jenis</label><input id="vehicle_type" name="vehicle_type" value="{{ old('vehicle_type', 'Motor') }}" required maxlength="50" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                <div><label for="brand" class="mb-1.5 block text-sm font-semibold text-slate-700">Merek</label><input id="brand" name="brand" value="{{ old('brand') }}" required maxlength="100" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                <div><label for="model" class="mb-1.5 block text-sm font-semibold text-slate-700">Model</label><input id="model" name="model" value="{{ old('model') }}" required maxlength="100" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                <div><label for="year" class="mb-1.5 block text-sm font-semibold text-slate-700">Tahun</label><input id="year" name="year" type="number" min="1950" max="{{ now()->year + 1 }}" value="{{ old('year', now()->year) }}" required class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                <div><label for="color" class="mb-1.5 block text-sm font-semibold text-slate-700">Warna</label><input id="color" name="color" value="{{ old('color') }}" required maxlength="50" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                <div><label for="plate_number" class="mb-1.5 block text-sm font-semibold text-slate-700">Nomor polisi</label><input id="plate_number" name="plate_number" value="{{ old('plate_number') }}" required maxlength="20" class="w-full rounded-xl border-slate-300 text-sm uppercase shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                <div class="sm:col-span-2 lg:col-span-3"><button type="submit" class="inline-flex min-h-11 items-center justify-center rounded-xl bg-indigo-700 px-5 py-2.5 text-sm font-bold text-white hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Simpan kendaraan</button></div>
            </form>
        </section>

        <section>
            <div class="mb-4 flex items-end justify-between gap-3">
                <div><p class="text-xs font-bold uppercase tracking-wider text-slate-400">Data tersimpan</p><h2 class="mt-1 text-xl font-bold text-slate-900">Garasi saya <span class="text-slate-400">({{ $vehicles->count() }})</span></h2></div>
            </div>
            @if ($vehicles->isEmpty())
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center text-sm text-slate-500">Belum ada kendaraan di garasi. Tambahkan kendaraan melalui formulir di atas.</div>
            @else
                <div class="grid gap-5 lg:grid-cols-2">
                    @foreach ($vehicles as $vehicle)
                        <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                            <div class="mb-5 flex items-start justify-between gap-4">
                                <div>
                                    <span class="inline-flex rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-700">{{ $vehicle->vehicle_type }}</span>
                                    <h3 class="mt-3 text-lg font-bold text-slate-900">{{ $vehicle->brand }} {{ $vehicle->model }}</h3>
                                    <p class="mt-1 text-sm font-semibold tracking-wide text-slate-500">{{ $vehicle->plate_number }}</p>
                                </div>
                                <span class="rounded-xl bg-slate-50 px-3 py-2 text-right"><span class="block text-xs text-slate-400">Servis</span><span class="mt-0.5 block text-sm font-bold text-slate-700">{{ $vehicle->service_histories_count }}</span></span>
                            </div>
                            @php
                                $latestMaintenanceRecords = $vehicle->maintenanceRecords
                                    ->unique(fn ($record) => mb_strtolower(trim($record->part_name)));
                            @endphp
                            <section class="mb-5 rounded-2xl border border-indigo-100 bg-indigo-50/60 p-4">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wide text-indigo-600">Kilometer saat ini</p>
                                        <p class="mt-1 text-xl font-extrabold tabular-nums text-slate-900">
                                            {{ $vehicle->current_odometer_km !== null ? number_format($vehicle->current_odometer_km, 0, ',', '.') . ' km' : 'Belum dicatat' }}
                                        </p>
                                    </div>
                                    <form method="POST" action="{{ route('customer.vehicles.odometer', $vehicle) }}" class="flex flex-wrap items-end gap-2">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label for="odometer-{{ $vehicle->id }}" class="mb-1 block text-xs font-semibold text-slate-600">Perbarui kilometer</label>
                                            <input id="odometer-{{ $vehicle->id }}" name="current_odometer_km" type="number" min="{{ $vehicle->current_odometer_km ?? 0 }}" max="9999999" value="{{ old('current_odometer_km', $vehicle->current_odometer_km) }}" required class="w-36 rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: 12500">
                                        </div>
                                        <button type="submit" class="inline-flex min-h-10 items-center rounded-lg bg-indigo-700 px-3 py-2 text-sm font-bold text-white hover:bg-indigo-800">Simpan km</button>
                                    </form>
                                </div>
                                <p class="mt-2 text-xs text-slate-500">Isi sesuai angka odometer motor. Angka kilometer tidak dapat diturunkan.</p>
                            </section>

                            <section class="mb-5">
                                <h4 class="text-sm font-bold text-slate-900">Riwayat penggantian & pengingat</h4>
                                <p class="mt-1 text-xs leading-5 text-slate-500">Catat komponen dan interval sesuai rekomendasi buku manual atau bengkel. Sistem tidak menetapkan interval otomatis.</p>
                                @if ($latestMaintenanceRecords->isNotEmpty())
                                    <ul class="mt-3 space-y-2">
                                        @foreach ($latestMaintenanceRecords as $record)
                                            @php
                                                $dueAtKm = $record->due_at_km;
                                                $maintenanceAction = config('maintenance.guides.' . $record->part_name . '.action', 'Periksa');
                                            @endphp
                                            <li class="rounded-xl border {{ $dueAtKm !== null && $vehicle->current_odometer_km !== null && $vehicle->current_odometer_km >= $dueAtKm ? 'border-amber-200 bg-amber-50' : 'border-slate-200 bg-white' }} p-3">
                                                <div class="flex flex-wrap items-start justify-between gap-2">
                                                    <div>
                                                        <p class="font-bold text-slate-900">{{ $record->part_name }}</p>
                                                        <p class="mt-1 text-xs text-slate-500">Diganti {{ $record->replaced_at->format('d M Y') }} pada {{ number_format($record->odometer_km, 0, ',', '.') }} km</p>
                                                    </div>
                                                    @if ($dueAtKm !== null)
                                                        @if ($vehicle->current_odometer_km === null)
                                                            <span class="text-xs font-semibold text-slate-600">Perbarui kilometer untuk melihat jadwal</span>
                                                        @elseif ($vehicle->current_odometer_km >= $dueAtKm)
                                                            <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-800">
                                                                @if ($maintenanceAction === 'Periksa')
                                                                    Waktunya periksa kondisi {{ mb_strtolower($record->part_name) }}
                                                                @else
                                                                    Sudah mencapai jadwal ganti {{ mb_strtolower($record->part_name) }}
                                                                @endif
                                                            </span>
                                                        @else
                                                            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-800">
                                                                @if ($maintenanceAction === 'Periksa')
                                                                    Periksa {{ mb_strtolower($record->part_name) }} pada {{ number_format($dueAtKm, 0, ',', '.') }} km · sisa {{ number_format($dueAtKm - $vehicle->current_odometer_km, 0, ',', '.') }} km
                                                                @else
                                                                    Ganti {{ mb_strtolower($record->part_name) }} pada {{ number_format($dueAtKm, 0, ',', '.') }} km · sisa {{ number_format($dueAtKm - $vehicle->current_odometer_km, 0, ',', '.') }} km
                                                                @endif
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="text-xs font-semibold text-slate-500">Interval belum diatur</span>
                                                    @endif
                                                </div>
                                                @if ($record->notes)
                                                    <p class="mt-2 text-xs text-slate-600">{{ $record->notes }}</p>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="mt-3 rounded-xl bg-slate-50 p-3 text-xs text-slate-500">Belum ada komponen yang dicatat. Tambahkan riwayat setelah melakukan penggantian.</p>
                                @endif
                            </section>

                            <details class="mb-5 rounded-2xl border border-slate-200 bg-white">
                                <summary class="cursor-pointer px-4 py-3 text-sm font-bold text-indigo-700">Catat penggantian komponen</summary>
                                <form method="POST" action="{{ route('customer.vehicles.maintenance.store', $vehicle) }}" data-maintenance-form data-maintenance-guides="{{ json_encode(config('maintenance.guides'), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG) }}" class="grid gap-3 border-t border-slate-100 p-4 sm:grid-cols-2">
                                    @csrf
                                    <div>
                                        <label for="part-name-{{ $vehicle->id }}" class="mb-1 block text-xs font-semibold text-slate-600">Nama sparepart/servis</label>
                                        <input id="part-name-{{ $vehicle->id }}" name="part_name" list="maintenance-parts-{{ $vehicle->id }}" data-part-name required maxlength="120" class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Pilih atau ketik nama komponen">
                                        <datalist id="maintenance-parts-{{ $vehicle->id }}">
                                            @foreach (config('maintenance.guides') as $part => $guide)
                                                <option value="{{ $part }}"></option>
                                            @endforeach
                                        </datalist>
                                    </div>
                                    <div>
                                        <label for="replaced-at-{{ $vehicle->id }}" class="mb-1 block text-xs font-semibold text-slate-600">Tanggal diganti</label>
                                        <input id="replaced-at-{{ $vehicle->id }}" name="replaced_at" type="date" max="{{ now()->toDateString() }}" value="{{ now()->toDateString() }}" required class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="replacement-odometer-{{ $vehicle->id }}" class="mb-1 block text-xs font-semibold text-slate-600">Kilometer saat diganti</label>
                                        <input id="replacement-odometer-{{ $vehicle->id }}" name="odometer_km" type="number" min="0" max="9999999" value="{{ $vehicle->current_odometer_km }}" required class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: 12500">
                                    </div>
                                    <div>
                                        <label for="interval-{{ $vehicle->id }}" class="mb-1 block text-xs font-semibold text-slate-600">Ingatkan lagi setelah (km)</label>
                                        <input id="interval-{{ $vehicle->id }}" name="interval_km" type="number" min="1" max="9999999" required data-maintenance-interval class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Wajib diisi">
                                        <p data-maintenance-guidance class="mt-1 text-xs leading-5 text-slate-500">Pilih komponen untuk melihat panduan interval. Untuk komponen lain, isi sesuai manual atau anjuran bengkel.</p>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="maintenance-notes-{{ $vehicle->id }}" class="mb-1 block text-xs font-semibold text-slate-600">Catatan (opsional)</label>
                                        <input id="maintenance-notes-{{ $vehicle->id }}" name="notes" maxlength="1000" class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Merek oli, jenis komponen, atau catatan bengkel">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <button type="submit" class="inline-flex min-h-10 items-center rounded-lg bg-indigo-700 px-4 py-2 text-sm font-bold text-white hover:bg-indigo-800">Simpan riwayat penggantian</button>
                                    </div>
                                </form>
                            </details>

                            <form method="POST" action="{{ route('customer.vehicles.update', $vehicle) }}" class="grid gap-3 sm:grid-cols-2">
                                @csrf
                                @method('PUT')
                                <div><label for="type-{{ $vehicle->id }}" class="mb-1 block text-xs font-semibold text-slate-600">Jenis</label><input id="type-{{ $vehicle->id }}" name="vehicle_type" value="{{ $vehicle->vehicle_type }}" required maxlength="50" class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                                <div><label for="brand-{{ $vehicle->id }}" class="mb-1 block text-xs font-semibold text-slate-600">Merek</label><input id="brand-{{ $vehicle->id }}" name="brand" value="{{ $vehicle->brand }}" required maxlength="100" class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                                <div><label for="model-{{ $vehicle->id }}" class="mb-1 block text-xs font-semibold text-slate-600">Model</label><input id="model-{{ $vehicle->id }}" name="model" value="{{ $vehicle->model }}" required maxlength="100" class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                                <div><label for="year-{{ $vehicle->id }}" class="mb-1 block text-xs font-semibold text-slate-600">Tahun</label><input id="year-{{ $vehicle->id }}" name="year" type="number" min="1950" max="{{ now()->year + 1 }}" value="{{ $vehicle->year }}" required class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                                <div><label for="color-{{ $vehicle->id }}" class="mb-1 block text-xs font-semibold text-slate-600">Warna</label><input id="color-{{ $vehicle->id }}" name="color" value="{{ $vehicle->color }}" required maxlength="50" class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                                <div><label for="plate-{{ $vehicle->id }}" class="mb-1 block text-xs font-semibold text-slate-600">Nomor polisi</label><input id="plate-{{ $vehicle->id }}" name="plate_number" value="{{ $vehicle->plate_number }}" required maxlength="20" class="w-full rounded-lg border-slate-300 text-sm uppercase focus:border-indigo-500 focus:ring-indigo-500"></div>
                                <div class="flex flex-wrap gap-2 pt-1 sm:col-span-2">
                                    <button type="submit" class="inline-flex min-h-10 items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-bold text-white hover:bg-slate-700">Simpan perubahan</button>
                            </form>
                                    <form method="POST" action="{{ route('customer.vehicles.destroy', $vehicle) }}" onsubmit="return confirm('Hapus kendaraan ini dari garasi?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex min-h-10 items-center rounded-lg border border-rose-200 px-4 py-2 text-sm font-bold text-rose-700 hover:bg-rose-50">Hapus</button>
                                    </form>
                                </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
