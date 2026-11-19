@extends('dashboard.admin.layouts.app')

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ route('admin.reminders.update', $reminder_id ?? 1) }}" class="bg-white rounded-lg border border-outline-variant p-lg">
        @csrf
        @method('PUT')
        
        <h2 class="font-headline-md text-headline-md text-primary mb-lg">Edit Pengingat</h2>

        <div class="mb-lg">
            <label class="block font-semibold text-on-surface mb-2">Judul <span class="text-error">*</span></label>
            <input type="text" name="title" class="w-full px-4 py-2 border border-outline rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('title') border-error @enderror" placeholder="Contoh: Servis Berkala Mobil" value="{{ old('title', 'Servis Berkala - Avanza') }}" required>
            @error('title') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-lg">
            <label class="block font-semibold text-on-surface mb-2">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full px-4 py-2 border border-outline rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('description') border-error @enderror" placeholder="Masukkan deskripsi pengingat...">{{ old('description', 'Pengingat untuk servis berkala kendaraan Toyota Avanza') }}</textarea>
            @error('description') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-lg mb-lg">
            <div>
                <label class="block font-semibold text-on-surface mb-2">Tanggal Pengingat <span class="text-error">*</span></label>
                <input type="date" name="reminder_date" class="w-full px-4 py-2 border border-outline rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('reminder_date') border-error @enderror" value="{{ old('reminder_date', '2026-05-20') }}" required>
                @error('reminder_date') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block font-semibold text-on-surface mb-2">Notifikasi Sebelum (Hari) <span class="text-error">*</span></label>
                <input type="number" name="notify_days_before" min="0" class="w-full px-4 py-2 border border-outline rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('notify_days_before') border-error @enderror" placeholder="Contoh: 3" value="{{ old('notify_days_before', 3) }}" required>
                @error('notify_days_before') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="px-6 py-3 bg-primary text-white font-semibold rounded-lg hover:bg-primary-container transition-colors">
                Simpan Perubahan
            </button>
            <form method="POST" action="{{ route('admin.reminders.destroy', $reminder_id ?? 1) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengingat ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-3 bg-error text-white font-semibold rounded-lg hover:opacity-90 transition-opacity">
                    Hapus
                </button>
            </form>
            <a href="{{ route('admin.reminders.index') }}" class="px-6 py-3 bg-surface-container text-on-surface font-semibold rounded-lg hover:bg-surface-container-low transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
