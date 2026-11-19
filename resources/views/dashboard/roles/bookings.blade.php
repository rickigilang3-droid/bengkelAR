<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-indigo-600">Portal pelanggan <span class="mx-1 text-slate-300">/</span> Booking</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Booking servis</h1>
            <p class="mt-1 text-sm text-slate-500">Ajukan jadwal servis dan pantau keputusan bengkel.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-7 px-4 py-7 sm:px-6 lg:px-8">
        @if (session('status'))
            <div role="status" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">{{ session('status') }}</div>
        @endif
        @if (session('error') || $errors->any())
            <div role="alert" class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <p class="font-bold">{{ session('error') ?? 'Periksa kembali data yang Anda masukkan.' }}</p>
                @if ($errors->any())
                    <ul class="mt-2 list-inside list-disc space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                @endif
            </div>
        @endif

        <div class="grid items-start gap-6 xl:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)]">
            <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                <div class="mb-6">
                    <p class="text-xs font-bold uppercase tracking-wider text-indigo-600">Jadwalkan kunjungan</p>
                    <h2 class="mt-1 text-xl font-bold text-slate-900">Ajukan booking baru</h2>
                </div>

                @if ($vehicles->isEmpty())
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-5 text-center">
                        <p class="font-semibold text-slate-800">Tambahkan kendaraan terlebih dahulu</p>
                        <p class="mt-1 text-sm text-slate-500">Booking servis perlu terhubung dengan kendaraan di garasi Anda.</p>
                        <a href="{{ route('customer.vehicles') }}" class="mt-4 inline-flex min-h-10 items-center rounded-xl bg-indigo-700 px-4 py-2 text-sm font-bold text-white hover:bg-indigo-800">Buka garasi</a>
                    </div>
                @else
                    <form method="POST" action="{{ route('customer.bookings.store') }}" class="space-y-5">
                        @csrf
                        <div>
                            <label for="vehicle_id" class="mb-1.5 block text-sm font-semibold text-slate-700">Kendaraan</label>
                            <select id="vehicle_id" name="vehicle_id" required class="block w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Pilih kendaraan</option>
                                @foreach ($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" @selected(old('vehicle_id') == $vehicle->id)>{{ $vehicle->brand }} {{ $vehicle->model }} · {{ $vehicle->plate_number }}</option>
                                @endforeach
                            </select>
                            @error('vehicle_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="preferred_date" class="mb-1.5 block text-sm font-semibold text-slate-700">Tanggal pilihan</label>
                                <input id="preferred_date" name="preferred_date" type="date" min="{{ now()->toDateString() }}" value="{{ old('preferred_date') }}" required class="block w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('preferred_date')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="preferred_time" class="mb-1.5 block text-sm font-semibold text-slate-700">Jam pilihan</label>
                                <select id="preferred_time" name="preferred_time" required class="block w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Pilih jam</option>
                                    @foreach (['09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00'] as $time)
                                        <option value="{{ $time }}" @selected(old('preferred_time') === $time)>{{ $time }} WIB</option>
                                    @endforeach
                                </select>
                                @error('preferred_time')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div>
                            <label for="service_type" class="mb-1.5 block text-sm font-semibold text-slate-700">Jenis servis</label>
                            <select id="service_type" name="service_type" required class="block w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Pilih jenis servis</option>
                                @foreach (['Servis berkala', 'Ganti oli', 'Perbaikan mesin', 'Rem dan kaki-kaki', 'Kelistrikan', 'Lainnya'] as $serviceType)
                                    <option value="{{ $serviceType }}" @selected(old('service_type') === $serviceType)>{{ $serviceType }}</option>
                                @endforeach
                            </select>
                            @error('service_type')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="complaint" class="mb-1.5 block text-sm font-semibold text-slate-700">Keluhan atau catatan</label>
                            <textarea id="complaint" name="complaint" rows="4" maxlength="2000" required placeholder="Jelaskan kendala atau pekerjaan yang dibutuhkan..." class="block w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('complaint') }}</textarea>
                            @error('complaint')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="inline-flex min-h-11 w-full items-center justify-center rounded-xl bg-indigo-700 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Kirim permintaan booking</button>
                    </form>
                @endif
            </section>

            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-5 sm:px-7">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Aktivitas</p>
                    <h2 class="mt-1 text-xl font-bold text-slate-900">Permintaan booking saya</h2>
                </div>
                @if ($bookings->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <p class="font-bold text-slate-800">Belum ada permintaan booking</p>
                        <p class="mt-1 text-sm text-slate-500">Booking yang Anda ajukan akan tampil di sini.</p>
                    </div>
                @else
                    <ul class="divide-y divide-slate-100">
                        @foreach ($bookings as $booking)
                            @php
                                $statusClass = match ($booking->status) {
                                    'accepted' => 'bg-emerald-50 text-emerald-700',
                                    'cancelled' => 'bg-rose-50 text-rose-700',
                                    'rescheduled' => 'bg-blue-50 text-blue-700',
                                    default => 'bg-amber-50 text-amber-800',
                                };
                                $statusLabel = match ($booking->status) {
                                    'accepted' => 'Disetujui',
                                    'cancelled' => 'Dibatalkan',
                                    'rescheduled' => 'Dijadwalkan ulang',
                                    default => 'Menunggu konfirmasi',
                                };
                            @endphp
                            <li class="px-5 py-5 sm:px-7">
                                <div class="flex flex-wrap items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900">{{ $booking->service_type }}</p>
                                        <p class="mt-1 text-sm text-slate-500">{{ $booking->vehicle?->brand }} {{ $booking->vehicle?->model }} · {{ $booking->vehicle?->plate_number }}</p>
                                    </div>
                                    <span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusClass }}">{{ $statusLabel }}</span>
                                </div>
                                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $booking->complaint }}</p>
                                <p class="mt-3 text-xs font-semibold text-slate-500">{{ $booking->preferred_date?->format('d M Y') }} · {{ $booking->preferred_time }}</p>
                            </li>
                        @endforeach
                    </ul>
                    <div class="border-t border-slate-100 px-5 py-4 sm:px-7">{{ $bookings->links() }}</div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
