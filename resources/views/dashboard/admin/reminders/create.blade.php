@extends('dashboard.admin.layouts.app')

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ route('admin.reminders.store') }}" class="bg-white rounded-lg border border-outline-variant p-lg">
        @csrf
        
        <h2 class="font-headline-md text-headline-md text-primary mb-lg">Buat Pengingat Baru</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-lg mb-lg">
            <div>
                <label class="block font-semibold text-on-surface mb-2">Pelanggan <span class="text-error">*</span></label>
                <select name="user_id" class="w-full px-4 py-2 border border-outline rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('user_id') border-error @enderror" required>
                    <option value="">Pilih Pelanggan</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" @if(old('user_id') == $customer->id) selected @endif>{{ $customer->name }}</option>
                    @endforeach
                </select>
                @error('user_id') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block font-semibold text-on-surface mb-2">Kendaraan</label>
                <select name="vehicle_id" class="w-full px-4 py-2 border border-outline rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('vehicle_id') border-error @enderror">
                    <option value="">Semua Kendaraan</option>
                    @foreach($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}" @if(old('vehicle_id') == $vehicle->id) selected @endif>{{ $vehicle->brand }} {{ $vehicle->model }} - {{ $vehicle->plate_number }}</option>
                    @endforeach
                </select>
                @error('vehicle_id') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-lg mb-lg">
            <div>
                <label class="block font-semibold text-on-surface mb-2">Tipe Pengingat <span class="text-error">*</span></label>
                <select name="type" class="w-full px-4 py-2 border border-outline rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('type') border-error @enderror" required>
                    <option value="">Pilih Tipe</option>
                    <option value="service" @if(old('type') === 'service') selected @endif>Service</option>
                    <option value="maintenance" @if(old('type') === 'maintenance') selected @endif>Maintenance</option>
                    <option value="inspection" @if(old('type') === 'inspection') selected @endif>Inspection</option>
                </select>
                @error('type') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block font-semibold text-on-surface mb-2">Tanggal Pengingat <span class="text-error">*</span></label>
                <input type="date" name="reminder_date" class="w-full px-4 py-2 border border-outline rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('reminder_date') border-error @enderror" value="{{ old('reminder_date') }}" required>
                @error('reminder_date') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mb-lg">
            <label class="block font-semibold text-on-surface mb-2">Judul <span class="text-error">*</span></label>
            <input type="text" name="title" class="w-full px-4 py-2 border border-outline rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('title') border-error @enderror" placeholder="Contoh: Servis Berkala Mobil" value="{{ old('title') }}" required>
            @error('title') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-lg">
            <label class="block font-semibold text-on-surface mb-2">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full px-4 py-2 border border-outline rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('description') border-error @enderror" placeholder="Masukkan deskripsi pengingat...">{{ old('description') }}</textarea>
            @error('description') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-semibold text-on-surface mb-2">Notifikasi Sebelum (Hari) <span class="text-error">*</span></label>
            <input type="number" name="notify_days_before" min="0" max="30" class="w-full px-4 py-2 border border-outline rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('notify_days_before') border-error @enderror" placeholder="Contoh: 3" value="{{ old('notify_days_before', 3) }}" required>
            @error('notify_days_before') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-4 mt-lg">
            <button type="submit" class="px-6 py-3 bg-primary text-white font-semibold rounded-lg hover:bg-primary-container transition-colors">
                Buat Pengingat
            </button>
            <a href="{{ route('admin.reminders.index') }}" class="px-6 py-3 bg-surface-container text-on-surface font-semibold rounded-lg hover:bg-surface-container-low transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
