<?php
// Konfigurasi koneksi database
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'servis_motor';
$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}
