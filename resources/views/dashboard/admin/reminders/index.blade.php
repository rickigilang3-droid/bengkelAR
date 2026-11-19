@extends('dashboard.admin.layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="font-headline-md text-headline-md text-primary">Pengingat Servis</h2>
            <p class="font-body-md text-body-md text-secondary mt-xs">Kelola pengingat servis dan notifikasi untuk pelanggan</p>
        </div>
        <a href="{{ route('admin.reminders.create') }}" class="rounded-lg bg-primary px-6 py-3 font-semibold text-white hover:bg-primary-container transition-colors inline-block">
            <span class="material-symbols-outlined align-middle mr-2">add</span>
            Buat Pengingat
        </a>
    </div>

    <!-- Reminders Filter -->
    <div class="bg-white rounded-lg border border-outline-variant p-6">
        <form method="GET" action="{{ route('admin.reminders.index') }}" class="mb-6">
            <div class="flex gap-4 mb-6">
                <select name="type" class="flex-1 px-4 py-2 border border-outline rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">Semua Tipe Pengingat</option>
                    <option value="service" @if(request('type') === 'service') selected @endif>Service</option>
                    <option value="maintenance" @if(request('type') === 'maintenance') selected @endif>Maintenance</option>
                    <option value="inspection" @if(request('type') === 'inspection') selected @endif>Inspection</option>
                </select>
                <select name="status" class="px-4 py-2 border border-outline rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">Semua Status</option>
                    <option value="active" @if(request('status') === 'active') selected @endif>Aktif</option>
                    <option value="completed" @if(request('status') === 'completed') selected @endif>Selesai</option>
                    <option value="cancelled" @if(request('status') === 'cancelled') selected @endif>Dibatalkan</option>
                </select>
                <button type="submit" class="px-6 py-2 bg-primary text-white font-semibold rounded-lg hover:bg-primary-container">Filter</button>
            </div>
        </form>

        <!-- Reminders Table -->
        <table class="w-full">
            <thead class="bg-surface-container-low border-b border-outline-variant">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold text-on-surface">Judul</th>
                    <th class="px-6 py-4 text-left font-semibold text-on-surface">Pelanggan</th>
                    <th class="px-6 py-4 text-left font-semibold text-on-surface">Tanggal Pengingat</th>
                    <th class="px-6 py-4 text-left font-semibold text-on-surface">Tipe</th>
                    <th class="px-6 py-4 text-left font-semibold text-on-surface">Status</th>
                    <th class="px-6 py-4 text-left font-semibold text-on-surface">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse ($reminders as $reminder)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="px-6 py-4 font-semibold">{{ $reminder->title }}</td>
                        <td class="px-6 py-4">{{ $reminder->user->name }}</td>
                        <td class="px-6 py-4">{{ $reminder->reminder_date->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 rounded-full 
                                @if($reminder->type === 'service') bg-blue-100 text-blue-700
                                @elseif($reminder->type === 'maintenance') bg-yellow-100 text-yellow-700
                                @else bg-purple-100 text-purple-700
                                @endif
                                text-sm font-semibold">{{ ucfirst($reminder->type) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 rounded-full 
                                @if($reminder->status === 'active') bg-green-100 text-green-700
                                @elseif($reminder->status === 'completed') bg-gray-100 text-gray-700
                                @else bg-red-100 text-red-700
                                @endif
                                text-sm font-semibold">{{ ucfirst($reminder->status) }}</span>
                        </td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="{{ route('admin.reminders.edit', $reminder->id) }}" class="text-primary hover:text-primary-container font-semibold">Edit</a>
                            @if($reminder->status === 'active')
                            <form method="POST" action="{{ route('admin.reminders.send', $reminder->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-blue-600 hover:text-blue-800 font-semibold">Kirim</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-on-surface-variant">Tidak ada data pengingat</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $reminders->links() }}
    </div>
</div>
@endsection
