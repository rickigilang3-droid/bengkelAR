@extends('dashboard.admin.layouts.app')

@section('title', $title)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-on-surface">Tambah Antrean Baru</h2>
            <p class="text-sm text-secondary mt-1">Daftarkan pelanggan walk-in secara manual ke dalam sistem antrean.</p>
        </div>
        <a href="{{ route('admin.queue.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium text-secondary bg-surface-container-low hover:bg-surface-container hover:text-primary transition-colors border border-outline-variant/50">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            Kembali
        </a>
    </div>

    <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/40 overflow-hidden">
        <form action="{{ route('admin.queue.store') }}" method="POST" class="p-6 md:p-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Customer Selection -->
                <div>
                    <label for="user_id" class="block text-sm font-bold text-on-surface mb-2">Pilih Pelanggan <span class="text-error">*</span></label>
                    <div class="relative">
                        <select name="user_id" id="user_id" required class="w-full appearance-none bg-surface-container-lowest border border-outline-variant rounded-xl px-4 py-3 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                            <option value="" disabled selected>-- Pilih Pelanggan --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('user_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->phone ?? $customer->email }})
                                </option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-secondary pointer-events-none">arrow_drop_down</span>
                    </div>
                    @error('user_id')
                        <p class="text-xs text-error mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Vehicle Selection -->
                <div>
                    <label for="vehicle_id" class="block text-sm font-bold text-on-surface mb-2">Pilih Kendaraan <span class="text-error">*</span></label>
                    <div class="relative">
                        <select name="vehicle_id" id="vehicle_id" required class="w-full appearance-none bg-surface-container-lowest border border-outline-variant rounded-xl px-4 py-3 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                            <option value="" disabled selected>-- Pilih Kendaraan --</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" data-user="{{ $vehicle->user_id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->plate_number }} - {{ $vehicle->brand }} {{ $vehicle->model }}
                                </option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-secondary pointer-events-none">arrow_drop_down</span>
                    </div>
                    <p class="text-[11px] text-secondary mt-1.5">*Kendaraan akan difilter sesuai pelanggan</p>
                    @error('vehicle_id')
                        <p class="text-xs text-error mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-8">
                <!-- Complaint -->
                <label for="complaint" class="block text-sm font-bold text-on-surface mb-2">Keluhan / Masalah Kendaraan <span class="text-error">*</span></label>
                <textarea name="complaint" id="complaint" rows="4" required placeholder="Jelaskan detail masalah, contoh: Ganti oli rutin dan rem depan blong..." class="w-full bg-surface-container-lowest border border-outline-variant rounded-xl px-4 py-3 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors resize-none">{{ old('complaint') }}</textarea>
                @error('complaint')
                    <p class="text-xs text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-8">
                <!-- Priority Selection -->
                <label class="block text-sm font-bold text-on-surface mb-3">Tingkat Prioritas <span class="text-error">*</span></label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="relative flex cursor-pointer">
                        <input type="radio" name="priority" value="low" class="peer sr-only" {{ old('priority', 'low') === 'low' ? 'checked' : '' }}>
                        <div class="w-full p-4 rounded-xl border-2 border-outline-variant/50 bg-surface-container-lowest peer-checked:border-green-500 peer-checked:bg-green-50/50 hover:bg-surface-container-low transition-all">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="material-symbols-outlined text-green-500 text-[20px]">low_priority</span>
                                <span class="font-bold text-sm text-on-surface">Rendah</span>
                            </div>
                            <p class="text-xs text-secondary">Servis berkala atau ringan.</p>
                        </div>
                    </label>

                    <label class="relative flex cursor-pointer">
                        <input type="radio" name="priority" value="medium" class="peer sr-only" {{ old('priority') === 'medium' ? 'checked' : '' }}>
                        <div class="w-full p-4 rounded-xl border-2 border-outline-variant/50 bg-surface-container-lowest peer-checked:border-orange-500 peer-checked:bg-orange-50/50 hover:bg-surface-container-low transition-all">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="material-symbols-outlined text-orange-500 text-[20px]">error_outline</span>
                                <span class="font-bold text-sm text-on-surface">Sedang</span>
                            </div>
                            <p class="text-xs text-secondary">Perbaikan komponen vital.</p>
                        </div>
                    </label>

                    <label class="relative flex cursor-pointer">
                        <input type="radio" name="priority" value="high" class="peer sr-only" {{ old('priority') === 'high' ? 'checked' : '' }}>
                        <div class="w-full p-4 rounded-xl border-2 border-outline-variant/50 bg-surface-container-lowest peer-checked:border-error peer-checked:bg-error/10 hover:bg-surface-container-low transition-all">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="material-symbols-outlined text-error text-[20px]">warning</span>
                                <span class="font-bold text-sm text-on-surface">Tinggi</span>
                            </div>
                            <p class="text-xs text-secondary">Darurat atau mogok parah.</p>
                        </div>
                    </label>
                </div>
                @error('priority')
                    <p class="text-xs text-error mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-outline-variant/30">
                <button type="reset" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-secondary hover:bg-surface-container-low hover:text-on-surface transition-colors">
                    Reset Form
                </button>
                <button type="submit" class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold text-white bg-primary hover:bg-primary/90 shadow-md shadow-primary/20 transition-all active:scale-[0.98]">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                    Tambahkan ke Antrean
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Script untuk menyembunyikan kendaraan yang bukan milik pelanggan yang dipilih
    document.addEventListener('DOMContentLoaded', function() {
        const userSelect = document.getElementById('user_id');
        const vehicleSelect = document.getElementById('vehicle_id');
        const vehicleOptions = Array.from(vehicleSelect.options).filter(opt => opt.value !== "");

        function filterVehicles() {
            const selectedUserId = userSelect.value;
            
            // Tampilkan kembali placeholder, kosongkan pilihan saat ini
            vehicleSelect.value = "";
            
            // Sembunyikan kendaraan yang user_id-nya tidak cocok
            vehicleOptions.forEach(opt => {
                if (opt.dataset.user === selectedUserId) {
                    opt.style.display = '';
                } else {
                    opt.style.display = 'none';
                }
            });
        }

        userSelect.addEventListener('change', filterVehicles);
        
        // Jalankan script saat halaman di-load jika user lama (old input/error validation) tersimpan
        if (userSelect.value) {
            filterVehicles();
        }
    });
</script>
@endsection