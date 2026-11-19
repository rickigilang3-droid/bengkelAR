@extends('dashboard.admin.layouts.app')

@section('title', 'Akun Mekanik')
@section('content')
<div class="space-y-6">
    @if (session('success'))
        <div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
            <p class="font-bold">Akun mekanik belum dapat dibuat.</p>
            <ul class="mt-1 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <section class="rounded-2xl border border-outline-variant/50 bg-white p-5 shadow-sm sm:p-6">
        <h2 class="text-lg font-bold text-on-surface">Buat akun mekanik</h2>
        <p class="mt-1 text-sm text-secondary">Akun akan langsung diberi role mekanik. Sampaikan kata sandi awal kepada mekanik secara pribadi.</p>
        <form method="POST" action="{{ route('admin.mechanics.store') }}" class="mt-5 grid gap-4 sm:grid-cols-2">
            @csrf
            <div>
                <label for="mechanic-name" class="mb-1 block text-sm font-semibold text-on-surface">Nama lengkap</label>
                <input id="mechanic-name" name="name" value="{{ old('name') }}" required maxlength="255" autocomplete="name" class="w-full rounded-xl border-outline-variant text-sm focus:border-primary focus:ring-primary">
            </div>
            <div>
                <label for="mechanic-email" class="mb-1 block text-sm font-semibold text-on-surface">Email login</label>
                <input id="mechanic-email" name="email" type="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email" class="w-full rounded-xl border-outline-variant text-sm focus:border-primary focus:ring-primary">
            </div>
            <div>
                <label for="mechanic-phone" class="mb-1 block text-sm font-semibold text-on-surface">Nomor telepon (opsional)</label>
                <input id="mechanic-phone" name="phone" type="tel" value="{{ old('phone') }}" maxlength="20" autocomplete="tel" class="w-full rounded-xl border-outline-variant text-sm focus:border-primary focus:ring-primary">
            </div>
            <div>
                <label for="mechanic-password" class="mb-1 block text-sm font-semibold text-on-surface">Kata sandi awal</label>
                <input id="mechanic-password" name="password" type="password" required minlength="8" autocomplete="new-password" class="w-full rounded-xl border-outline-variant text-sm focus:border-primary focus:ring-primary">
            </div>
            <div>
                <label for="mechanic-password-confirmation" class="mb-1 block text-sm font-semibold text-on-surface">Ulangi kata sandi</label>
                <input id="mechanic-password-confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password" class="w-full rounded-xl border-outline-variant text-sm focus:border-primary focus:ring-primary">
            </div>
            <div class="flex items-end">
                <button type="submit" class="inline-flex min-h-11 items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-primary-container">
                    <span class="material-symbols-outlined text-lg" aria-hidden="true">person_add</span>
                    Buat akun mekanik
                </button>
            </div>
        </form>
    </section>

    <section class="overflow-hidden rounded-2xl border border-outline-variant/50 bg-white shadow-sm">
        <div class="border-b border-outline-variant/40 px-5 py-4 sm:px-6">
            <h2 class="font-bold text-on-surface">Akun mekanik terdaftar</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-surface-container-low text-xs uppercase tracking-wide text-secondary">
                    <tr>
                        <th class="px-5 py-3">Nama</th>
                        <th class="px-5 py-3">Email</th>
                        <th class="px-5 py-3">Telepon</th>
                        <th class="px-5 py-3">Role</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/40">
                    @forelse ($mechanics as $mechanic)
                        <tr>
                            <td class="px-5 py-3 font-semibold text-on-surface">{{ $mechanic->name }}</td>
                            <td class="px-5 py-3 text-secondary">{{ $mechanic->email }}</td>
                            <td class="px-5 py-3 text-secondary">{{ $mechanic->phone ?: '—' }}</td>
                            <td class="px-5 py-3"><span class="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-700">{{ $mechanic->role === \App\Models\User::ROLE_TECHNICIAN ? 'Teknisi' : 'Mekanik' }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-10 text-center text-secondary">Belum ada akun mekanik.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant/40 p-4">{{ $mechanics->links() }}</div>
    </section>
</div>
@endsection
