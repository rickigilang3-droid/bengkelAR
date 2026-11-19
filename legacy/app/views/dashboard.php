<?php
$allowedRoles = ['admin', 'pelanggan', 'mekanik', 'teknisi'];
require_once __DIR__ . '/../middleware/require_roles.php';

$role = $_SESSION['user_role'] ?? '';
$roleLabel = in_array($role, ['mekanik', 'teknisi'], true) ? 'Mekanik / teknisi' : ucfirst($role);
$name = htmlspecialchars($_SESSION['user_name'] ?? 'Pengguna', ENT_QUOTES, 'UTF-8');
$cards = [];

if ($role === 'admin') {
    $cards = [
        ['Antrian servis', 'Pantau kendaraan yang menunggu dan sedang dikerjakan.', '?page=antrian', 'Buka antrian'],
        ['Database pelanggan', 'Lihat halaman data pelanggan yang tersedia.', '?page=database_pelanggan', 'Buka pelanggan'],
        ['Inventaris', 'Buka tampilan inventaris suku cadang.', '?page=inventaris_sparepart', 'Buka inventaris'],
        ['Pengingat servis', 'Buka tampilan pengingat servis.', '?page=pengingat_servis', 'Buka pengingat'],
    ];
} elseif (in_array($role, ['mekanik', 'teknisi'], true)) {
    $cards = [
        ['Antrian servis', 'Lihat tampilan pekerjaan servis bengkel.', '?page=antrian', 'Buka antrian'],
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f9fb">
    <title>Dashboard · Bengkel AR</title>
    <style>
        :root { color-scheme: light; font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; color: #191c1e; background: #f7f9fb; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; }
        a { color: inherit; }
        .shell { max-width: 1120px; margin: 0 auto; padding: 28px 20px 56px; }
        .topbar { display: flex; justify-content: space-between; align-items: center; gap: 16px; }
        .brand { display: flex; align-items: center; gap: 12px; font-weight: 750; letter-spacing: -.03em; }
        .brand-mark { display: grid; width: 42px; height: 42px; place-items: center; border-radius: 14px; background: #1e40af; color: white; }
        .logout { border: 1px solid #d5d9e1; border-radius: 11px; padding: 10px 14px; background: white; font: inherit; font-size: 14px; font-weight: 650; text-decoration: none; }
        .intro { margin: 54px 0 28px; }
        .eyebrow { color: #314fb4; font-size: 13px; font-weight: 700; letter-spacing: .04em; }
        h1 { margin: 8px 0 8px; font-size: clamp(28px, 5vw, 42px); letter-spacing: -.04em; line-height: 1.12; }
        .muted { color: #596273; line-height: 1.6; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 16px; }
        .card { display: flex; min-height: 196px; flex-direction: column; align-items: flex-start; border: 1px solid #e0e3e8; border-radius: 18px; padding: 22px; background: white; box-shadow: 0 5px 18px #1725540a; }
        .card h2 { margin: 0; font-size: 18px; letter-spacing: -.02em; }
        .card p { margin: 10px 0 22px; font-size: 14px; }
        .action { margin-top: auto; border-radius: 10px; padding: 10px 13px; background: #eef1ff; color: #18338f; font-size: 14px; font-weight: 700; text-decoration: none; }
        .notice { border: 1px solid #cbd6ff; border-radius: 16px; padding: 20px; background: #f0f3ff; }
        .notice p { margin: 6px 0 0; }
        @media (max-width: 540px) { .shell { padding: 18px 16px 36px; } .intro { margin-top: 40px; } .logout { padding: 9px 11px; } }
    </style>
</head>
<body>
    <main class="shell">
        <header class="topbar">
            <div class="brand"><span class="brand-mark" aria-hidden="true">B</span><span>Bengkel AR</span></div>
            <form method="post" action="?page=logout">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                <button class="logout" type="submit">Keluar</button>
            </form>
        </header>

        <section class="intro">
            <p class="eyebrow"><?= htmlspecialchars($roleLabel, ENT_QUOTES, 'UTF-8') ?> · Ruang kerja</p>
            <h1>Selamat datang, <?= $name ?></h1>
            <p class="muted">Pilih area kerja yang sesuai dengan akses akun Anda.</p>
        </section>

        <?php if ($cards): ?>
            <section class="grid" aria-label="Area kerja">
                <?php foreach ($cards as [$title, $description, $href, $action]): ?>
                    <article class="card">
                        <h2><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h2>
                        <p class="muted"><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></p>
                        <a class="action" href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?> <span aria-hidden="true">→</span></a>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php else: ?>
            <section class="notice">
                <strong>Portal pelanggan</strong>
                <p class="muted">Untuk mengelola kendaraan dan melihat informasi servis, lanjutkan melalui portal pelanggan Bengkel AR.</p>
                <a class="action" href="../login">Buka portal pelanggan <span aria-hidden="true">→</span></a>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>
