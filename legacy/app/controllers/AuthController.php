<?php

class AuthController {
    public function login() {
        if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
            header('Location: ?page=dashboard');
            exit;
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../../config/database.php';

            $token = $_POST['csrf_token'] ?? '';
            if (!is_string($token) || !hash_equals($_SESSION['csrf_token'], $token)) {
                $error = 'Sesi formulir tidak berlaku. Silakan coba lagi.';
            } else {
                $email = trim((string) ($_POST['email'] ?? ''));
                $password = (string) ($_POST['password'] ?? '');
                $statement = mysqli_prepare($conn, 'SELECT id, name, password, role, is_active FROM users WHERE email = ? LIMIT 1');

                if (!$statement) {
                    throw new RuntimeException('Tidak dapat menyiapkan autentikasi pengguna.');
                }

                mysqli_stmt_bind_param($statement, 's', $email);
                mysqli_stmt_execute($statement);
                $result = mysqli_stmt_get_result($statement);
                $user = $result ? mysqli_fetch_assoc($result) : null;
                mysqli_stmt_close($statement);

                $validRoles = ['admin', 'pelanggan', 'mekanik', 'teknisi'];
                if (
                    $user
                    && password_verify($password, $user['password'])
                    && (int) $user['is_active'] === 1
                    && in_array($user['role'], $validRoles, true)
                ) {
                    session_regenerate_id(true);
                    $_SESSION['user_logged_in'] = true;
                    $_SESSION['user_id'] = (int) $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                    header('Location: ?page=dashboard');
                    exit;
                }

                $error = 'Email atau kata sandi salah, atau akun tidak aktif.';
            }
        }

        require_once __DIR__ . '/../views/login.php';
    }

    public function logout() {
        $token = $_POST['csrf_token'] ?? '';
        if (
            $_SERVER['REQUEST_METHOD'] !== 'POST'
            || !is_string($token)
            || !isset($_SESSION['csrf_token'])
            || !hash_equals($_SESSION['csrf_token'], $token)
        ) {
            http_response_code(403);
            exit('Permintaan keluar tidak valid.');
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();
        header('Location: ?page=login');
        exit;
    }
}
