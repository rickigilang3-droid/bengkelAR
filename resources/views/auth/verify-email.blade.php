@section('title', 'Verifikasi email — Bengkel AR')

<x-guest-layout>
    <header class="auth-heading">
        <p class="auth-kicker">Satu langkah lagi</p>
        <h1>Verifikasi alamat email</h1>
        <p>Tautan verifikasi telah dikirim ke email Anda. Buka pesan tersebut dan klik tautannya untuk mengaktifkan akun.</p>
    </header>

    @if (session('status') === 'verification-link-sent')
        <div class="auth-status" role="status">Tautan verifikasi baru telah dikirim ke alamat email akun Anda.</div>
    @endif

    @if ($errors->any())
        <div class="auth-alert" role="alert">{{ $errors->first() }}</div>
    @endif

    <div class="auth-form">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button class="auth-submit" type="submit">Kirim ulang tautan verifikasi <span aria-hidden="true">→</span></button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="auth-secondary-button" type="submit">Keluar dari akun</button>
        </form>
    </div>

    <p class="auth-footnote">Belum menerima email? Periksa folder spam atau kirim ulang tautan verifikasi.</p>
</x-guest-layout>
