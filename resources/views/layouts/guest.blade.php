<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#f5f7fc">
    <title>@yield('title', config('app.name', 'Bengkel AR'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            color-scheme: light;
            font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #172033;
            background: #f5f7fc;
            font-synthesis: none;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
        }

        * { box-sizing: border-box; }
        body { min-width: 320px; min-height: 100vh; margin: 0; }
        a { color: inherit; }
        button, input { font: inherit; }
        .auth-page { min-height: 100vh; padding: 24px clamp(18px, 5vw, 72px) 28px; }
        .auth-topbar { display: flex; max-width: 1320px; min-height: 52px; align-items: center; justify-content: space-between; gap: 20px; margin: 0 auto; }
        .auth-brand { display: inline-flex; align-items: center; gap: 11px; color: #18213c; font-size: 19px; font-weight: 800; letter-spacing: -.045em; text-decoration: none; }
        .auth-brand-mark { display: grid; width: 40px; height: 40px; place-items: center; border-radius: 13px; background: #2947bd; color: white; }
        .auth-brand-mark svg { width: 22px; height: 22px; }
        .auth-top-link { color: #647087; font-size: 14px; text-decoration: none; }
        .auth-top-link strong { margin-left: 4px; color: #2947bd; font-weight: 750; }
        .auth-top-link:hover strong { text-decoration: underline; text-underline-offset: 3px; }
        .auth-main { display: grid; width: min(1050px, 100%); min-height: min(690px, calc(100vh - 128px)); grid-template-columns: .92fr 1.08fr; overflow: hidden; margin: 32px auto 0; border: 1px solid #e6eaf2; border-radius: 28px; background: white; box-shadow: 0 24px 80px #1e2d5712; }
        .auth-aside { position: relative; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; padding: clamp(30px, 4vw, 54px); background: linear-gradient(145deg, #1d348b 0%, #2947bd 56%, #4968d4 100%); color: white; }
        .auth-aside::before, .auth-aside::after { position: absolute; width: 300px; height: 300px; border: 1px solid #ffffff20; border-radius: 50%; content: ""; pointer-events: none; }
        .auth-aside::before { top: 25%; right: -175px; }
        .auth-aside::after { top: 31%; right: -125px; width: 200px; height: 200px; }
        .auth-aside-content, .auth-aside-foot { position: relative; z-index: 1; }
        .auth-aside-label { display: inline-flex; align-items: center; gap: 8px; border: 1px solid #ffffff35; border-radius: 999px; padding: 8px 11px; background: #ffffff12; color: #eff2ff; font-size: 11px; font-weight: 750; letter-spacing: .06em; text-transform: uppercase; }
        .auth-aside-label span { width: 7px; height: 7px; border-radius: 50%; background: #7df0bb; box-shadow: 0 0 0 4px #7df0bb25; }
        .auth-aside h2 { max-width: 390px; margin: 23px 0 13px; font-size: clamp(30px, 3.4vw, 43px); font-weight: 750; letter-spacing: -.055em; line-height: 1.08; }
        .auth-aside-copy { max-width: 360px; margin: 0; color: #e0e6ff; font-size: 15px; line-height: 1.75; }
        .auth-illustration { display: grid; width: 100%; max-width: 310px; aspect-ratio: 1.35; place-items: center; align-self: center; margin: 26px 0; border: 1px solid #ffffff24; border-radius: 24px; background: linear-gradient(145deg, #ffffff1a, #ffffff08); }
        .auth-illustration svg { width: 58%; max-height: 170px; color: #fff; filter: drop-shadow(0 15px 20px #111c4b38); }
        .auth-illustration-img { width: 100%; height: 100%; object-fit: contain; border-radius: 23px; filter: drop-shadow(0 16px 18px #111c4b55); }
        .auth-aside-foot { color: #d3dcff; font-size: 12px; }
        .auth-content { display: flex; align-items: center; justify-content: center; padding: clamp(28px, 5vw, 70px); }
        .auth-form-wrap { width: min(420px, 100%); }
        .auth-heading { margin-bottom: 27px; }
        .auth-kicker { margin: 0 0 8px; color: #3452c4; font-size: 12px; font-weight: 800; letter-spacing: .09em; text-transform: uppercase; }
        .auth-heading h1 { margin: 0; color: #172033; font-size: clamp(29px, 3vw, 36px); font-weight: 760; letter-spacing: -.055em; line-height: 1.15; }
        .auth-heading p { margin: 10px 0 0; color: #748096; font-size: 14px; line-height: 1.65; }
        .auth-form { display: grid; gap: 18px; }
        .auth-field { display: grid; gap: 7px; }
        .auth-label { color: #30394c; font-size: 13px; font-weight: 700; }
        .auth-input-wrap { position: relative; }
        .auth-input { display: block; width: 100%; min-height: 49px; border: 1px solid #dfe4ed; border-radius: 12px; padding: 0 14px; outline: none; background: #fff; color: #1c2538; font-size: 14px; transition: border-color .16s ease, box-shadow .16s ease, background .16s ease; }
        .auth-input::placeholder { color: #a0a8b7; }
        .auth-input:hover { border-color: #bbc5d8; }
        .auth-input:focus { border-color: #536ddb; box-shadow: 0 0 0 4px #536ddb1c; }
        .auth-input.has-toggle { padding-right: 76px; }
        .auth-password-toggle { position: absolute; top: 50%; right: 12px; border: 0; padding: 5px 3px; transform: translateY(-50%); background: transparent; color: #596a9e; cursor: pointer; font-size: 12px; font-weight: 700; }
        .auth-password-toggle:hover { color: #233fb0; }
        .auth-error { margin: 0; color: #b42335; font-size: 12px; line-height: 1.45; }
        .auth-status { margin-bottom: 18px; border: 1px solid #bce7d1; border-radius: 12px; padding: 12px 14px; background: #effaf4; color: #176844; font-size: 13px; line-height: 1.55; }
        .auth-alert { margin-bottom: 18px; border: 1px solid #ffd1d1; border-radius: 12px; padding: 12px 14px; background: #fff5f5; color: #a52232; font-size: 13px; line-height: 1.55; }
        .auth-check-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .auth-checkbox-label { display: inline-flex; align-items: center; gap: 9px; color: #69758b; font-size: 13px; cursor: pointer; }
        .auth-checkbox { width: 16px; height: 16px; border: 1px solid #c8d0de; border-radius: 5px; accent-color: #2947bd; }
        .auth-inline-link { color: #304db8; font-size: 13px; font-weight: 700; text-decoration: none; }
        .auth-inline-link:hover { text-decoration: underline; text-underline-offset: 3px; }
        .auth-submit { display: inline-flex; width: 100%; min-height: 50px; align-items: center; justify-content: center; gap: 9px; border: 0; border-radius: 12px; padding: 0 18px; background: #2947bd; color: white; cursor: pointer; font-size: 14px; font-weight: 750; box-shadow: 0 8px 18px #2947bd26; transition: background .16s ease, transform .16s ease, box-shadow .16s ease; }
        .auth-submit:hover { transform: translateY(-1px); background: #203b9f; box-shadow: 0 11px 22px #2947bd35; }
        .auth-submit:focus-visible, .auth-password-toggle:focus-visible, .auth-top-link:focus-visible, .auth-inline-link:focus-visible { outline: 3px solid #acbaff; outline-offset: 3px; }
        .auth-submit:disabled { cursor: wait; opacity: .7; }
        .auth-divider { display: flex; align-items: center; gap: 13px; margin: 21px 0 0; color: #9aa2b1; font-size: 11px; }
        .auth-divider::before, .auth-divider::after { height: 1px; flex: 1; background: #edf0f4; content: ""; }
        .auth-bottom-link { margin: 19px 0 0; color: #778196; font-size: 13px; text-align: center; }
        .auth-bottom-link a { margin-left: 3px; color: #304db8; font-weight: 750; text-decoration: none; }
        .auth-bottom-link a:hover { text-decoration: underline; text-underline-offset: 3px; }
        .auth-footnote { margin: 22px 0 0; color: #a0a7b4; font-size: 11px; line-height: 1.6; text-align: center; }
        .auth-action-row { display: flex; flex-wrap: wrap; align-items: center; gap: 12px; }
        .auth-secondary-button { display: inline-flex; min-height: 44px; align-items: center; justify-content: center; border: 1px solid #dfe4ed; border-radius: 11px; padding: 0 15px; background: #fff; color: #49556b; cursor: pointer; font-size: 13px; font-weight: 700; }
        .auth-secondary-button:hover { background: #f6f7fa; }
        .auth-page-footer { max-width: 1320px; margin: 16px auto 0; color: #9aa2af; font-size: 11px; text-align: center; }
        @media (max-width: 820px) {
            .auth-page { padding: 18px 18px 22px; }
            .auth-main { width: min(540px, 100%); min-height: initial; grid-template-columns: 1fr; margin-top: 24px; }
            .auth-aside { display: none; }
            .auth-content { padding: clamp(27px, 7vw, 50px); }
            .auth-page-footer { margin-top: 14px; }
        }
        @media (max-width: 480px) {
            .auth-page { padding: 14px 12px 18px; }
            .auth-topbar { padding: 0 4px; }
            .auth-brand { font-size: 17px; }
            .auth-brand-mark { width: 36px; height: 36px; }
            .auth-top-link { font-size: 12px; }
            .auth-main { border-radius: 20px; margin-top: 17px; }
            .auth-content { padding: 28px 21px 25px; }
            .auth-heading { margin-bottom: 23px; }
            .auth-form { gap: 16px; }
            .auth-check-row { align-items: flex-start; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    <div class="auth-page">
        <header class="auth-topbar">
            <a class="auth-brand" href="{{ route('home') }}" aria-label="Bengkel AR beranda">
                <span class="auth-brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.7 6.3a5 5 0 0 0-6.4 6.4L3.5 17.5a2.1 2.1 0 0 0 3 3l4.8-4.8a5 5 0 0 0 6.4-6.4l-3.1 3.1-3.1-3.1 3.2-3Z"/>
                    </svg>
                </span>
                <span>Bengkel AR</span>
            </a>
            <a class="auth-top-link" href="{{ request()->routeIs('register') ? route('login') : route('register') }}">
                {{ request()->routeIs('register') ? 'Sudah punya akun?' : 'Belum punya akun?' }}
                <strong>{{ request()->routeIs('register') ? 'Masuk' : 'Daftar' }}</strong>
            </a>
        </header>

        <main class="auth-main">
            <aside class="auth-aside" aria-label="Tentang Bengkel AR">
                <div class="auth-aside-content">
                    <span class="auth-aside-label"><span aria-hidden="true"></span> Perawatan lebih teratur</span>
                    <h2>Kendaraan terawat, perjalanan terasa nyaman.</h2>
                    <p class="auth-aside-copy">Satu akun untuk menyimpan kendaraan, mengikuti progres servis, dan melihat kembali riwayat perawatan Anda.</p>
                </div>
                <div class="auth-illustration" aria-hidden="true">
                    <img class="auth-illustration-img" src="{{ asset('images/yamaha-r1.jpg') }}" alt="Motor Yamaha YZF-R1">
                </div>
                <p class="auth-aside-foot">Mudah dipantau. Praktis digunakan. #ServisLebihTenang</p>
            </aside>

            <section class="auth-content">
                <div class="auth-form-wrap">
                    {{ $slot }}
                </div>
            </section>
        </main>

        <footer class="auth-page-footer">© {{ date('Y') }} Bengkel AR · Perawatan kendaraan yang lebih teratur.</footer>
    </div>
    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.getAttribute('aria-controls'));
                if (!input) return;

                const reveal = input.type === 'password';
                input.type = reveal ? 'text' : 'password';
                button.textContent = reveal ? 'Sembunyikan' : 'Tampilkan';
                button.setAttribute('aria-pressed', String(reveal));
            });
        });
    </script>
</body>
</html>
