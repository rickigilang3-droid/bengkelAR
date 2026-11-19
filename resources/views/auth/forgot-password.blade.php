@section('title', 'Pulihkan kata sandi — Bengkel AR')

<x-guest-layout>
    <header class="auth-heading">
        <p class="auth-kicker">Pemulihan akun</p>
        <h1>Atur ulang kata sandi</h1>
        <p>Masukkan email yang terhubung ke akun Anda. Kami akan mengirimkan tautan untuk membuat kata sandi baru.</p>
    </header>

    <x-auth-session-status class="auth-status" :status="session('status')" />

    @if ($errors->any())
        <div class="auth-alert" role="alert">{{ $errors->first() }}</div>
    @endif

    <form class="auth-form" method="POST" action="{{ route('password.email') }}">
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
                autocomplete="email"
                inputmode="email"
                aria-describedby="{{ $errors->has('email') ? 'email-error' : '' }}"
            >
            @if ($errors->has('email'))
                <p class="auth-error" id="email-error">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <button class="auth-submit" type="submit">Kirim tautan pemulihan <span aria-hidden="true">→</span></button>
    </form>

    <p class="auth-bottom-link"><a href="{{ route('login') }}">← Kembali ke halaman masuk</a></p>
</x-guest-layout>
