<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f9fb">
    <meta name="description" content="Bengkel AR membantu Anda memantau servis kendaraan dengan lebih mudah dan teratur.">
    <title>Bengkel AR — Servis kendaraan lebih mudah</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #171b26;
            background: #f7f9fc;
            font-synthesis: none;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
        }

        * { box-sizing: border-box; }
        body { min-width: 320px; min-height: 100vh; margin: 0; }
        a { color: inherit; text-decoration: none; }
        .shell { max-width: 1180px; margin: 0 auto; padding: 0 28px; }
        .topbar { display: flex; min-height: 88px; align-items: center; justify-content: space-between; gap: 24px; }
        .brand { display: inline-flex; align-items: center; gap: 12px; font-size: 19px; font-weight: 780; letter-spacing: -.04em; }
        .brand-mark { display: grid; width: 42px; height: 42px; place-items: center; border-radius: 14px; background: #233fb0; color: white; }
        .brand-mark svg { width: 23px; height: 23px; }
        .nav-actions { display: flex; align-items: center; gap: 12px; }
        .button { display: inline-flex; min-height: 44px; align-items: center; justify-content: center; gap: 8px; border: 1px solid transparent; border-radius: 12px; padding: 0 17px; font-size: 14px; font-weight: 700; transition: background .18s ease, transform .18s ease, border-color .18s ease; }
        .button:hover { transform: translateY(-1px); }
        .button:focus-visible { outline: 3px solid #aebdff; outline-offset: 3px; }
        .button-quiet { border-color: #e0e4ec; background: white; }
        .button-quiet:hover { background: #f4f6fb; }
        .button-primary { background: #233fb0; color: white; box-shadow: 0 8px 18px #233fb026; }
        .button-primary:hover { background: #192f91; }
        .hero { display: grid; min-height: 590px; grid-template-columns: 1.05fr .95fr; align-items: center; gap: 64px; padding-top: 20px; padding-bottom: 60px; }
        .eyebrow { display: inline-flex; align-items: center; gap: 8px; border: 1px solid #dfe5ff; border-radius: 999px; padding: 8px 12px; background: #f0f3ff; color: #3149a2; font-size: 12px; font-weight: 750; letter-spacing: .035em; }
        .eyebrow-dot { width: 7px; height: 7px; border-radius: 50%; background: #4a68dc; }
        h1 { max-width: 620px; margin: 22px 0 18px; font-size: clamp(42px, 6vw, 68px); font-weight: 760; letter-spacing: -.065em; line-height: 1.04; }
        .accent { color: #2947bd; }
        .hero-copy { max-width: 520px; margin: 0; color: #626b7c; font-size: 17px; line-height: 1.75; }
        .hero-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 30px; }
        .hero-note { display: flex; align-items: center; gap: 9px; margin-top: 22px; color: #70798a; font-size: 13px; }
        .hero-note svg { flex: 0 0 auto; width: 17px; height: 17px; color: #19805b; }
        .visual { position: relative; display: grid; min-height: 410px; place-items: center; }
        .visual-glow { position: absolute; width: min(390px, 100%); aspect-ratio: 1; border-radius: 50%; background: radial-gradient(circle, #e1e8ff 0, #eaf0ff 47%, #f7f9fc 72%); }
        .work-card { position: relative; width: min(390px, 100%); overflow: hidden; border: 1px solid #e4e8f0; border-radius: 24px; background: #fff; box-shadow: 0 30px 80px #22305b1c; transform: rotate(2deg); }
        .work-card-head { display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eef0f4; padding: 21px 22px; }
        .work-card-title { margin: 0; font-size: 14px; font-weight: 730; }
        .live { display: inline-flex; align-items: center; gap: 7px; border-radius: 999px; padding: 7px 10px; background: #eaf8f1; color: #18734e; font-size: 11px; font-weight: 750; }
        .live span { width: 6px; height: 6px; border-radius: 50%; background: #28a36f; }
        .vehicle { display: flex; align-items: center; gap: 15px; padding: 22px; }
        .vehicle-icon { display: grid; width: 52px; height: 52px; flex: 0 0 auto; place-items: center; border-radius: 16px; background: #eef1ff; color: #3149a2; }
        .vehicle-icon svg { width: 27px; height: 27px; }
        .vehicle-name { margin: 0; font-size: 15px; font-weight: 730; }
        .vehicle-plate { margin: 5px 0 0; color: #747d8d; font-size: 12px; }
        .progress-wrap { padding: 0 22px 23px; }
        .progress-top { display: flex; justify-content: space-between; gap: 12px; margin-bottom: 10px; font-size: 12px; }
        .progress-top strong { font-weight: 700; }
        .progress-top span { color: #7a8291; }
        .progress { height: 7px; overflow: hidden; border-radius: 999px; background: #edf0f5; }
        .progress span { display: block; width: 68%; height: 100%; border-radius: inherit; background: linear-gradient(90deg, #324fca, #6981ec); }
        .timeline { display: grid; gap: 0; padding: 0 22px 22px; }
        .timeline-item { display: grid; grid-template-columns: 20px 1fr auto; align-items: center; gap: 10px; min-height: 45px; color: #768092; font-size: 12px; }
        .timeline-item + .timeline-item { border-top: 1px solid #f0f1f5; }
        .timeline-icon { display: grid; width: 20px; height: 20px; place-items: center; border-radius: 50%; background: #edf8f2; color: #188154; font-size: 12px; font-weight: 800; }
        .timeline-item.active { color: #25304a; font-weight: 680; }
        .timeline-item.active .timeline-icon { background: #eef1ff; color: #344fbc; }
        .timeline-item time { color: #969dab; font-size: 11px; font-weight: 500; }
        .features { padding: 22px 0 74px; }
        .features-heading { margin-bottom: 23px; text-align: center; }
        .features-heading h2 { margin: 0; font-size: 25px; letter-spacing: -.04em; }
        .features-heading p { margin: 9px 0 0; color: #6d7686; font-size: 14px; }
        .feature-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        .feature { border: 1px solid #e7eaf0; border-radius: 18px; padding: 22px; background: white; }
        .feature-icon { display: grid; width: 40px; height: 40px; place-items: center; border-radius: 13px; background: #f0f3ff; color: #304bb4; font-weight: 800; }
        .feature h3 { margin: 16px 0 7px; font-size: 15px; }
        .feature p { margin: 0; color: #6e7787; font-size: 13px; line-height: 1.65; }
        footer { border-top: 1px solid #e8ebf1; padding: 22px 0 28px; color: #7b8390; font-size: 12px; }
        .footer-inner { display: flex; justify-content: space-between; gap: 16px; }
        @media (max-width: 850px) {
            .hero { min-height: initial; grid-template-columns: 1fr; gap: 15px; padding-top: 55px; }
            .hero-copy { max-width: 620px; }
            .visual { min-height: 390px; }
            .features { padding-top: 0; }
        }
        @media (max-width: 600px) {
            .shell { padding-right: 19px; padding-left: 19px; }
            .topbar { min-height: 72px; }
            .brand { font-size: 17px; }
            .brand-mark { width: 38px; height: 38px; }
            .nav-actions { gap: 7px; }
            .button { min-height: 41px; padding: 0 12px; font-size: 13px; }
            .hero { gap: 0; padding-top: 42px; padding-bottom: 35px; }
            h1 { margin-top: 19px; font-size: clamp(40px, 12vw, 56px); }
            .hero-copy { font-size: 15px; }
            .visual { min-height: 340px; margin: 2px -3px 0; }
            .work-card { border-radius: 20px; }
            .work-card-head, .vehicle { padding-right: 17px; padding-left: 17px; }
            .progress-wrap, .timeline { padding-right: 17px; padding-left: 17px; }
            .feature-grid { grid-template-columns: 1fr; }
            .features { padding-bottom: 44px; }
            .features-heading { text-align: left; }
            .footer-inner { flex-direction: column; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    <header class="shell topbar">
        <a class="brand" href="{{ route('home') }}" aria-label="Bengkel AR beranda">
            <span class="brand-mark" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.7 6.3a5 5 0 0 0-6.4 6.4L3.5 17.5a2.1 2.1 0 0 0 3 3l4.8-4.8a5 5 0 0 0 6.4-6.4l-3.1 3.1-3.1-3.1 3.2-3Z"/>
                </svg>
            </span>
            <span>Bengkel AR</span>
        </a>
        <nav class="nav-actions" aria-label="Navigasi akun">
            <a class="button button-quiet" href="{{ route('login') }}">Masuk</a>
            <a class="button button-primary" href="{{ route('register') }}">Daftar akun</a>
        </nav>
    </header>

    <main>
        <section class="shell hero">
            <div>
                <span class="eyebrow"><span class="eyebrow-dot" aria-hidden="true"></span> Perawatan kendaraan, lebih praktis</span>
                <h1>Servis lebih tenang. <span class="accent">Perjalanan</span> lebih nyaman.</h1>
                <p class="hero-copy">Kelola informasi servis kendaraan Anda dalam satu tempat. Pantau progres pekerjaan dan lihat kembali riwayat servis dengan mudah.</p>
                <div class="hero-actions">
                    <a class="button button-primary" href="{{ route('register') }}">
                        Mulai sekarang
                        <span aria-hidden="true">→</span>
                    </a>
                    <a class="button button-quiet" href="{{ route('login') }}">Saya sudah punya akun</a>
                </div>
                <p class="hero-note">
                    <svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="m5 10 3.2 3.2L15.5 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/></svg>
                    Daftar sebagai pelanggan dalam beberapa langkah.
                </p>
            </div>

            <div class="visual" aria-label="Contoh tampilan progres servis kendaraan">
                <div class="visual-glow" aria-hidden="true"></div>
                <article class="work-card">
                    <div class="work-card-head">
                        <h2 class="work-card-title">Progres servis</h2>
                        <span class="live"><span aria-hidden="true"></span> Sedang dikerjakan</span>
                    </div>
                    <div class="vehicle">
                        <span class="vehicle-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="17" r="3"/><circle cx="18" cy="17" r="3"/><path d="m8 17 4-8h3l3 8M9 14h8M12 9l-2-2H7"/></svg>
                        </span>
                        <div>
                            <p class="vehicle-name">Kendaraan Anda</p>
                            <p class="vehicle-plate">Informasi tampil di akun pribadi</p>
                        </div>
                    </div>
                    <div class="progress-wrap">
                        <div class="progress-top"><strong>Servis berkala</strong><span>Progres pekerjaan</span></div>
                        <div class="progress" role="progressbar" aria-label="Contoh progres pekerjaan" aria-valuemin="0" aria-valuemax="100" aria-valuenow="68"><span></span></div>
                    </div>
                    <div class="timeline">
                        <div class="timeline-item"><span class="timeline-icon" aria-hidden="true">✓</span><span>Kendaraan diterima</span><time>Selesai</time></div>
                        <div class="timeline-item active"><span class="timeline-icon" aria-hidden="true">•</span><span>Pengerjaan servis</span><time>Berlangsung</time></div>
                        <div class="timeline-item"><span class="timeline-icon" aria-hidden="true">3</span><span>Siap diambil</span><time>Berikutnya</time></div>
                    </div>
                </article>
            </div>
        </section>

        <section class="shell features" aria-labelledby="features-title">
            <div class="features-heading">
                <h2 id="features-title">Semua informasi servis, lebih mudah diakses</h2>
                <p>Pengalaman yang membantu Anda tetap terhubung dengan perawatan kendaraan.</p>
            </div>
            <div class="feature-grid">
                <article class="feature">
                    <span class="feature-icon" aria-hidden="true">01</span>
                    <h3>Pantau progres</h3>
                    <p>Lihat status pekerjaan servis langsung dari dashboard akun Anda.</p>
                </article>
                <article class="feature">
                    <span class="feature-icon" aria-hidden="true">02</span>
                    <h3>Catat kendaraan</h3>
                    <p>Simpan informasi kendaraan agar riwayat perawatan lebih teratur.</p>
                </article>
                <article class="feature">
                    <span class="feature-icon" aria-hidden="true">03</span>
                    <h3>Lihat riwayat servis</h3>
                    <p>Temukan kembali catatan servis sebelumnya dengan praktis.</p>
                </article>
            </div>
        </section>
    </main>

    <footer>
        <div class="shell footer-inner">
            <span>© {{ date('Y') }} Bengkel AR</span>
            <span>Perawatan kendaraan yang lebih teratur.</span>
        </div>
    </footer>
</body>
</html>
