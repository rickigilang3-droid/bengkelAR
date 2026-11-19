<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$allowedRoles = $allowedRoles ?? [];
$userRole = $_SESSION['user_role'] ?? '';

if (
    !isset($_SESSION['user_logged_in'])
    || $_SESSION['user_logged_in'] !== true
    || !in_array($userRole, $allowedRoles, true)
) {
    http_response_code(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true ? 403 : 401);
    exit('Anda tidak memiliki akses ke halaman ini.');
}
