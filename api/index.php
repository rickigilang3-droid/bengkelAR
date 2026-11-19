<?php

// Ensure /tmp storage paths exist for Vercel Serverless environment
$storageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/storage/app/public',
    '/tmp/bootstrap/cache',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Set storage and cache paths for Laravel
$_ENV['LARAVEL_STORAGE_PATH'] = '/tmp/storage';
$_SERVER['LARAVEL_STORAGE_PATH'] = '/tmp/storage';
putenv('LARAVEL_STORAGE_PATH=/tmp/storage');

$_ENV['APP_STORAGE'] = '/tmp/storage';
$_SERVER['APP_STORAGE'] = '/tmp/storage';
putenv('APP_STORAGE=/tmp/storage');

$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_SERVER['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');

// Handle SQLite database in /tmp if using SQLite
$dbConnection = getenv('DB_CONNECTION') ?: ($_ENV['DB_CONNECTION'] ?? 'sqlite');
if ($dbConnection === 'sqlite') {
    $sqliteTmp = '/tmp/database.sqlite';
    $sqliteOriginal = __DIR__ . '/../database/database.sqlite';

    if (!file_exists($sqliteTmp)) {
        if (file_exists($sqliteOriginal)) {
            @copy($sqliteOriginal, $sqliteTmp);
        } else {
            @touch($sqliteTmp);
        }
        @chmod($sqliteTmp, 0666);
    }

    $_ENV['DB_DATABASE'] = $sqliteTmp;
    $_SERVER['DB_DATABASE'] = $sqliteTmp;
    putenv("DB_DATABASE={$sqliteTmp}");
}

// Forward to public/index.php
require __DIR__ . '/../public/index.php';
