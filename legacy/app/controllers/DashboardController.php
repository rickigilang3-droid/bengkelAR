<?php
class DashboardController {
    public function index() {
        if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
            header('Location: ?page=login');
            exit;
        }

        $role = $_SESSION['user_role'] ?? '';
        if (!in_array($role, ['admin', 'pelanggan', 'mekanik', 'teknisi'], true)) {
            http_response_code(403);
            echo 'Role akun belum memiliki akses dashboard.';
            return;
        }

        require_once __DIR__ . '/../views/dashboard.php';
    }
}
