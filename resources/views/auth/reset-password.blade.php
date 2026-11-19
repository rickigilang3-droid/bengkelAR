@section('title', 'Kata sandi baru — Bengkel AR')

<x-guest-layout>
    <header class="auth-heading">
        <p class="auth-kicker">Hampir selesai</p>
        <h1>Buat kata sandi baru</h1>
        <p>Pilih kata sandi baru untuk melindungi akun Bengkel AR Anda.</p>
    </header>

    @if ($errors->any())
        <div class="auth-alert" role="alert">{{ $errors->first() }}</div>
    @endif

    <form class="auth-form" method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="auth-field">
            <label class="auth-label" for="email">Alamat email</label>
            <input
                class="auth-input"
                id="email"
                type="email"
                name="email"
                value="{{ old('email', $request->email) }}"
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
            <label class="auth-label" for="password">Kata sandi baru</label>
            <div class="auth-input-wrap">
                <input
                    class="auth-input has-toggle"
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Masukkan kata sandi baru"
                    required
                    autocomplete="new-password"
                    aria-describedby="{{ $errors->has('password') ? 'password-error' : '' }}"
                >
                <button class="auth-password-toggle" type="button" data-password-toggle aria-controls="password" aria-pressed="false">Tampilkan</button>
            </div>
            @if ($errors->has('password'))
                <p class="auth-error" id="password-error">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <div class="auth-field">
            <label class="auth-label" for="password_confirmation">Ulangi kata sandi baru</label>
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

        <button class="auth-submit" type="submit">Simpan kata sandi baru <span aria-hidden="true">→</span></button>
    </form>
</x-guest-layout>
