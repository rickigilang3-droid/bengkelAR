@section('title', 'Daftar — Bengkel AR')

<x-guest-layout>
    <header class="auth-heading">
        <p class="auth-kicker">Mulai dari sini</p>
        <h1>Buat akun pelanggan</h1>
        <p>Daftarkan akun untuk mengelola kendaraan dan mengikuti servis Anda.</p>
    </header>

    @if ($errors->any())
        <div class="auth-alert" role="alert">
            <p class="m-0 font-semibold">Periksa kembali informasi berikut:</p>
            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="auth-form" method="POST" action="{{ route('register') }}">
        @csrf

        <div class="auth-field">
            <label class="auth-label" for="name">Nama lengkap</label>
            <input
                class="auth-input"
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="Nama sesuai identitas"
                required
                autofocus
                autocomplete="name"
                maxlength="255"
                aria-describedby="{{ $errors->has('name') ? 'name-error' : '' }}"
            >
            @if ($errors->has('name'))
                <p class="auth-error" id="name-error">{{ $errors->first('name') }}</p>
            @endif
        </div>

        <div class="auth-field">
            <label class="auth-label" for="email">Alamat email</label>
            <input
                class="auth-input"
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="nama@email.com"
                required
                autocomplete="email"
                inputmode="email"
                aria-describedby="{{ $errors->has('email') ? 'email-error' : '' }}"
            >
            @if ($errors->has('email'))
                <p class="auth-error" id="email-error">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <div class="auth-field">
            <label class="auth-label" for="password">Kata sandi</label>
            <div class="auth-input-wrap">
                <input
                    class="auth-input has-toggle"
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Buat kata sandi"
                    required
                    autocomplete="new-password"
                    aria-describedby="{{ $errors->has('password') ? 'password-hint password-error' : 'password-hint' }}"
                >
                <button class="auth-password-toggle" type="button" data-password-toggle aria-controls="password" aria-pressed="false">Tampilkan</button>
            </div>
            <p class="auth-footnote !mt-0 !text-left" id="password-hint">Gunakan kata sandi yang kuat dan mudah Anda ingat.</p>
            @if ($errors->has('password'))
                <p class="auth-error" id="password-error">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <div class="auth-field">
            <label class="auth-label" for="password_confirmation">Ulangi kata sandi</label>
            <div class="auth-input-wrap">
                <input
                    class="auth-input has-toggle"
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    placeholder="Masukkan kembali kata sandi"
                    required
                    autocomplete="new-password"
                >
                <button class="auth-password-toggle" type="button" data-password-toggle aria-controls="password_confirmation" aria-pressed="false">Tampilkan</button>
            </div>
        </div>

        <button class="auth-submit" type="submit">
            Buat akun pelanggan
            <span aria-hidden="true">→</span>
        </button>
    </form>

    <p class="auth-footnote">Pendaftaran baru membuat akun pelanggan. Peran staf hanya dapat diberikan oleh administrator.</p>
    <p class="auth-bottom-link">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
</x-guest-layout>
