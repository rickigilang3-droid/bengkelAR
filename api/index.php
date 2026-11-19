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

// Set storage path for Laravel
$_ENV['LARAVEL_STORAGE_PATH'] = '/tmp/storage';
$_SERVER['LARAVEL_STORAGE_PATH'] = '/tmp/storage';

// If using SQLite and DB file in /tmp does not exist, copy database.sqlite
$sqliteOriginal = __DIR__ . '/../database/database.sqlite';
$sqliteTmp = '/tmp/database.sqlite';
if (file_exists($sqliteOriginal) && !file_exists($sqliteTmp)) {
    @copy($sqliteOriginal, $sqliteTmp);
}

// Forward to public/index.php
require __DIR__ . '/../public/index.php';
