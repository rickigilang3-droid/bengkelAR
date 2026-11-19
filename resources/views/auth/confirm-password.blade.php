@section('title', 'Konfirmasi kata sandi — Bengkel AR')

<x-guest-layout>
    <header class="auth-heading">
        <p class="auth-kicker">Verifikasi keamanan</p>
        <h1>Konfirmasi kata sandi</h1>
        <p>Untuk melanjutkan ke area aman, konfirmasikan kata sandi akun Anda.</p>
    </header>

    @if ($errors->any())
        <div class="auth-alert" role="alert">{{ $errors->first() }}</div>
    @endif

    <form class="auth-form" method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="auth-field">
            <label class="auth-label" for="password">Kata sandi</label>
            <div class="auth-input-wrap">
                <input
                    class="auth-input has-toggle"
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Masukkan kata sandi"
                    required
                    autofocus
                    autocomplete="current-password"
                    aria-describedby="{{ $errors->has('password') ? 'password-error' : '' }}"
                >
                <button class="auth-password-toggle" type="button" data-password-toggle aria-controls="password" aria-pressed="false">Tampilkan</button>
            </div>
            @if ($errors->has('password'))
                <p class="auth-error" id="password-error">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <button class="auth-submit" type="submit">Konfirmasi dan lanjutkan <span aria-hidden="true">→</span></button>
    </form>
</x-guest-layout>
