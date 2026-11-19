<!DOCTYPE html>

<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Bengkel AR - Login</title>
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<!-- Design System Configuration -->
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "tertiary": "#003d27",
                    "on-secondary": "#ffffff",
                    "on-tertiary-container": "#3fd298",
                    "on-primary-fixed": "#001453",
                    "on-tertiary-fixed-variant": "#005236",
                    "tertiary-fixed": "#6ffbbe",
                    "secondary-container": "#d0e1fb",
                    "on-primary-fixed-variant": "#173bab",
                    "surface-container": "#eceef0",
                    "on-secondary-fixed-variant": "#38485d",
                    "on-surface-variant": "#444653",
                    "surface-variant": "#e0e3e5",
                    "tertiary-fixed-dim": "#4edea3",
                    "primary-fixed-dim": "#b8c4ff",
                    "primary-fixed": "#dde1ff",
                    "on-error-container": "#93000a",
                    "surface-container-lowest": "#ffffff",
                    "surface-container-high": "#e6e8ea",
                    "outline-variant": "#c4c5d5",
                    "inverse-surface": "#2d3133",
                    "error": "#ba1a1a",
                    "surface-container-low": "#f2f4f6",
                    "primary": "#00288e",
                    "on-secondary-fixed": "#0b1c30",
                    "on-error": "#ffffff",
                    "secondary-fixed-dim": "#b7c8e1",
                    "primary-container": "#1e40af",
                    "on-primary-container": "#a8b8ff",
                    "secondary-fixed": "#d3e4fe",
                    "background": "#f7f9fb",
                    "surface": "#f7f9fb",
                    "on-secondary-container": "#54647a",
                    "inverse-on-surface": "#eff1f3",
                    "surface-tint": "#3755c3",
                    "on-tertiary-fixed": "#002113",
                    "on-tertiary": "#ffffff",
                    "outline": "#757684",
                    "surface-dim": "#d8dadc",
                    "secondary": "#505f76",
                    "on-primary": "#ffffff",
                    "tertiary-container": "#00563a",
                    "surface-bright": "#f7f9fb",
                    "on-surface": "#191c1e",
                    "inverse-primary": "#b8c4ff",
                    "surface-container-highest": "#e0e3e5",
                    "on-background": "#191c1e",
                    "error-container": "#ffdad6"
            },
            "borderRadius": {
                    "DEFAULT": "0.125rem",
                    "lg": "0.25rem",
                    "xl": "0.5rem",
                    "full": "0.75rem"
            },
            "spacing": {
                    "sm": "8px",
                    "margin-mobile": "16px",
                    "md": "16px",
                    "base": "4px",
                    "margin-desktop": "32px",
                    "gutter": "24px",
                    "xs": "4px",
                    "lg": "24px",
                    "max-width": "1440px",
                    "xl": "32px"
            },
            "fontFamily": {
                    "label-md": [
                            "Inter", "sans-serif"
                    ],
                    "body-md": [
                            "Inter", "sans-serif"
                    ],
                    "body-lg": [
                            "Inter", "sans-serif"
                    ],
                    "headline-lg": [
                            "Inter", "sans-serif"
                    ],
                    "headline-md": [
                            "Inter", "sans-serif"
                    ],
                    "tabular-nums": [
                            "Inter", "sans-serif"
                    ],
                    "headline-lg-mobile": [
                            "Inter", "sans-serif"
                    ]
            },
            "fontSize": {
                    "label-md": [
                            "12px",
                            {
                                    "lineHeight": "16px",
                                    "letterSpacing": "0.05em",
                                    "fontWeight": "600"
                            }
                    ],
                    "body-md": [
                            "14px",
                            {
                                    "lineHeight": "20px",
                                    "fontWeight": "400"
                            }
                    ],
                    "body-lg": [
                            "16px",
                            {
                                    "lineHeight": "24px",
                                    "fontWeight": "400"
                            }
                    ],
                    "headline-lg": [
                            "30px",
                            {
                                    "lineHeight": "38px",
                                    "letterSpacing": "-0.02em",
                                    "fontWeight": "700"
                            }
                    ],
                    "headline-md": [
                            "20px",
                            {
                                    "lineHeight": "28px",
                                    "fontWeight": "600"
                            }
                    ],
                    "tabular-nums": [
                            "14px",
                            {
                                    "lineHeight": "20px",
                                    "fontWeight": "500"
                            }
                    ],
                    "headline-lg-mobile": [
                            "24px",
                            {
                                    "lineHeight": "32px",
                                    "letterSpacing": "-0.02em",
                                    "fontWeight": "700"
                            }
                    ]
            }
          }
        }
      }
    </script>
<style>
        /* Add inter font */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    </style>
<style>
    body {
      min-height: max(884px, 100dvh);
    }
  </style>
  </head>
<body class="bg-surface text-on-surface min-h-screen flex flex-col justify-center px-margin-mobile py-8 md:items-center">
<!-- Container for centering on larger screens if necessary, though requested mobile -->
<div class="w-full max-w-sm mx-auto flex flex-col">
<!-- Brand Logo Area -->
<div class="flex flex-col items-center justify-center mb-xl">
<div class="w-16 h-16 bg-primary-container text-on-primary-container rounded-xl flex items-center justify-center mb-sm shadow-sm">
<span class="material-symbols-outlined text-[32px]" style="font-variation-settings: 'FILL' 1;">handyman</span>
</div>
<h1 class="font-headline-lg-mobile text-headline-lg-mobile text-primary tracking-tight">Bengkel AR</h1>
</div>
<!-- Greeting -->
<div class="text-center mb-xl">
<h2 class="font-headline-md text-headline-md text-on-surface mb-xs">Selamat Datang Kembali</h2>
<p class="font-body-md text-body-md text-on-surface-variant">Masuk ke akun Bengkel AR Anda</p>
</div>
<!-- Login Form -->
<form action="?page=login" class="flex flex-col w-full" method="POST">
<input name="csrf_token" type="hidden" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/>
<?php if (!empty($error)): ?>
<p class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>
<!-- Email/Username Input -->
<div class="mb-md">
<label class="block font-label-md text-label-md text-on-surface mb-xs" for="email">Email</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">person</span>
<input autocomplete="email" class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg pl-10 pr-md py-3 font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all placeholder:text-outline" id="email" name="email" placeholder="nama@email.com" required="" type="email" value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/>
</div>
</div>
<!-- Password Input -->
<div class="mb-sm">
<label class="block font-label-md text-label-md text-on-surface mb-xs" for="password">Kata Sandi</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">lock</span>
<input autocomplete="current-password" class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg pl-10 pr-md py-3 font-body-md text-body-md text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all placeholder:text-outline" id="password" name="password" placeholder="Masukkan kata sandi" required="" type="password"/>
</div>
</div>
<!-- Forgot Password Link -->
<div class="flex justify-end mb-xl">
<a class="font-label-md text-label-md text-primary hover:text-primary-container transition-colors" href="#">Lupa Kata Sandi?</a>
</div>
<!-- Submit Button -->
<button class="w-full bg-primary text-on-primary font-label-md text-label-md py-4 rounded-xl hover:bg-primary-container active:scale-[0.98] transition-all flex justify-center items-center gap-2 shadow-sm" type="submit">
<span>Masuk</span>
<span class="material-symbols-outlined text-[18px]">arrow_forward</span>
</button>
</form>
<!-- Footer Link -->
<div class="mt-xl text-center">
<p class="font-body-md text-body-md text-on-surface-variant">
                Belum punya akun? <a class="font-label-md text-label-md text-primary hover:underline ml-1" href="#">Hubungi Admin</a>
</p>
</div>
</div>
</body></html>