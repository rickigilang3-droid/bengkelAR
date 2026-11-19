<x-app-layout>
    @php($emailVerified = $user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && $user->hasVerifiedEmail())

    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-indigo-600">Bengkel AR <span class="mx-1 text-slate-300">/</span> Pengaturan akun</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Profil akun</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola informasi pribadi, keamanan, dan preferensi akun Anda.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl space-y-6 px-4 py-7 sm:px-6 lg:px-8">
        @if (session('status') === 'profile-updated')
            <div role="status" class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm text-emerald-800">
                <span class="material-symbols-outlined text-[20px]" aria-hidden="true">check_circle</span>
                <span>Informasi profil berhasil diperbarui.</span>
            </div>
        @elseif (session('status') === 'password-updated')
            <div role="status" class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm text-emerald-800">
                <span class="material-symbols-outlined text-[20px]" aria-hidden="true">check_circle</span>
                <span>Kata sandi berhasil diperbarui.</span>
            </div>
        @elseif (session('status') === 'verification-link-sent')
            <div role="status" class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm text-emerald-800">
                <span class="material-symbols-outlined text-[20px]" aria-hidden="true">mark_email_read</span>
                <span>Tautan verifikasi baru telah dikirim ke alamat email Anda.</span>
            </div>
        @endif

        <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-900 via-indigo-800 to-blue-700 p-6 text-white shadow-lg shadow-indigo-900/10 sm:p-8">
            <div class="pointer-events-none absolute -right-10 -top-20 h-56 w-56 rounded-full border border-white/10"></div>
            <div class="pointer-events-none absolute -right-2 -top-12 h-40 w-40 rounded-full border border-white/10"></div>
            <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex min-w-0 items-center gap-4">
                    <span class="grid h-16 w-16 shrink-0 place-items-center rounded-2xl border border-white/20 bg-white/10 text-2xl font-extrabold uppercase shadow-inner">
                        {{ mb_substr($user->name, 0, 1) }}
                    </span>
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-indigo-200">Akun Bengkel AR</p>
                        <h2 class="mt-1 truncate text-2xl font-bold tracking-tight">{{ $user->name }}</h2>
                        <p class="mt-1 truncate text-sm text-indigo-100">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 sm:justify-end">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-bold">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span>
                        {{ match ($user->role) { 'admin' => 'Administrator', 'pelanggan' => 'Pelanggan', 'mekanik' => 'Mekanik', 'teknisi' => 'Teknisi', default => 'Pengguna' } }}
                    </span>
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-bold">
                        <span class="material-symbols-outlined text-[16px]" aria-hidden="true">{{ $emailVerified ? 'verified' : 'mark_email_unread' }}</span>
                        {{ $emailVerified ? 'Email terverifikasi' : 'Email belum terverifikasi' }}
                    </span>
                </div>
            </div>
        </section>

        <div class="grid gap-6">
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-start gap-4 border-b border-slate-100 px-5 py-5 sm:px-7">
                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-indigo-50 text-indigo-700">
                        <span class="material-symbols-outlined" aria-hidden="true">person_edit</span>
                    </span>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Informasi pribadi</h2>
                        <p class="mt-1 text-sm text-slate-500">Perbarui nama, email, nomor telepon, dan alamat Anda.</p>
                    </div>
                </div>
                <div class="p-5 sm:p-7">
                    @include('profile.partials.update-profile-information-form', ['user' => $user])
                </div>
            </section>

            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-start gap-4 border-b border-slate-100 px-5 py-5 sm:px-7">
                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-blue-50 text-blue-700">
                        <span class="material-symbols-outlined" aria-hidden="true">lock</span>
                    </span>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Keamanan akun</h2>
                        <p class="mt-1 text-sm text-slate-500">Gunakan kata sandi yang kuat dan tidak digunakan di tempat lain.</p>
                    </div>
                </div>
                <div class="p-5 sm:p-7">
                    @include('profile.partials.update-password-form')
                </div>
            </section>

            <section class="overflow-hidden rounded-3xl border border-rose-200 bg-white shadow-sm">
                <div class="flex items-start gap-4 border-b border-rose-100 bg-rose-50/60 px-5 py-5 sm:px-7">
                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-rose-100 text-rose-700">
                        <span class="material-symbols-outlined" aria-hidden="true">delete_forever</span>
                    </span>
                    <div>
                        <h2 class="text-lg font-bold text-rose-900">Hapus akun</h2>
                        <p class="mt-1 text-sm text-rose-700/80">Tindakan ini permanen dan tidak dapat dibatalkan.</p>
                    </div>
                </div>
                <div class="p-5 sm:p-7">
                    @include('profile.partials.delete-user-form')
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
