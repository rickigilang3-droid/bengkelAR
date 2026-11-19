@section('title', 'Masuk — Bengkel AR')

<x-guest-layout>
    <header class="auth-heading">
        <p class="auth-kicker">Selamat datang kembali</p>
        <h1>Masuk ke akun Anda</h1>
        <p>Lanjutkan memantau kendaraan dan informasi servis Anda.</p>
    </header>

    <x-auth-session-status class="auth-status" :status="session('status')" />

    @if ($errors->any())
        <div class="auth-alert" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form class="auth-form" method="POST" action="{{ route('login') }}">
        @csrf

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
                autofocus
                autocomplete="username"
                inputmode="email"
                aria-describedby="{{ $errors->has('email') ? 'email-error' : '' }}"
            >
            @if ($errors->has('email'))
                <p class="auth-error" id="email-error">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <div class="auth-field">
            <div class="auth-check-row">
                <label class="auth-label" for="password">Kata sandi</label>
                @if (Route::has('password.request'))
                    <a class="auth-inline-link" href="{{ route('password.request') }}">Lupa kata sandi?</a>
                @endif
            </div>
            <div class="auth-input-wrap">
                <input
                    class="auth-input has-toggle"
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Masukkan kata sandi"
                    required
                    autocomplete="current-password"
                    aria-describedby="{{ $errors->has('password') ? 'password-error' : '' }}"
                >
                <button class="auth-password-toggle" type="button" data-password-toggle aria-controls="password" aria-pressed="false">Tampilkan</button>
            </div>
            @if ($errors->has('password'))
                <p class="auth-error" id="password-error">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <div class="auth-check-row">
            <label class="auth-checkbox-label" for="remember">
                <input class="auth-checkbox" id="remember" type="checkbox" name="remember" value="1" @checked(old('remember'))>
                Ingat saya di perangkat ini
            </label>
        </div>

        <button class="auth-submit" type="submit">
            Masuk ke akun
            <span aria-hidden="true">→</span>
        </button>
    </form>

    <p class="auth-bottom-link">Belum terdaftar? <a href="{{ route('register') }}">Buat akun pelanggan</a></p>
    <p class="auth-footnote">Dengan masuk, Anda dapat mengakses informasi akun dan servis kendaraan Anda.</p>
</x-guest-layout>
