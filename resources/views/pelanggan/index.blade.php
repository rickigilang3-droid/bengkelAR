@extends('layouts.app')

@section('title', 'Pelanggan - Bengkel AR')

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="mx-auto max-w-6xl px-6 py-10">
        <div class="mb-8 flex flex-col gap-4 rounded-3xl bg-white p-8 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Halaman Pelanggan</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-900">Manajemen Pelanggan & Motor</h1>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-2xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700">Kembali ke Dashboard</a>
        </div>

        <div class="grid gap-8 lg:grid-cols-2">
            <div class="rounded-3xl bg-white p-8 shadow-sm">
                <h2 class="mb-4 text-xl font-semibold text-slate-900">Tambah Pelanggan</h2>
                <p class="mb-6 text-sm text-slate-500">Form ini hanya contoh. Backend data pelanggan dapat ditambahkan kemudian.</p>
                <form class="space-y-4">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Nama Pelanggan</label>
                        <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none" type="text" placeholder="Masukkan nama pelanggan">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">No. HP</label>
                        <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none" type="text" placeholder="0812xxxxxxx">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Merk Motor</label>
                        <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none" type="text" placeholder="Yamaha, Honda, dll.">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">No. Polisi</label>
                        <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none" type="text" placeholder="B 1234 ABC">
                    </div>
                    <button class="rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700" type="button">Simpan Pelanggan</button>
                </form>
            </div>
            <div class="rounded-3xl bg-white p-8 shadow-sm">
                <h2 class="mb-4 text-xl font-semibold text-slate-900">Data Pelanggan</h2>
                <p class="mb-6 text-sm text-slate-500">Daftar pelanggan dan data motor ditampilkan di sini.</p>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-700">
                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">No. HP</th>
                                <th class="px-4 py-3">Motor</th>
                                <th class="px-4 py-3">No. Polisi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <tr>
                                <td class="px-4 py-4">Agus Santoso</td>
                                <td class="px-4 py-4">081234567890</td>
                                <td class="px-4 py-4">Yamaha Nmax</td>
                                <td class="px-4 py-4">B 1234 CD</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4">Dewi Putri</td>
                                <td class="px-4 py-4">081298765432</td>
                                <td class="px-4 py-4">Honda Vario</td>
                                <td class="px-4 py-4">B 5678 EF</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
