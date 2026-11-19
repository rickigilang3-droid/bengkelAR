<?php
session_start();

$page = isset($_GET['page']) && is_string($_GET['page']) ? $_GET['page'] : 'login';

if ($page !== 'login' && (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true)) {
	header('Location: ?page=login');
	exit;
}

$role = $_SESSION['user_role'] ?? '';
$roleAliases = in_array($role, ['mekanik', 'teknisi'], true) ? ['mekanik', 'teknisi'] : [$role];
$pageRoles = [
	'pelanggan' => ['admin'],
	'database_pelanggan' => ['admin'],
	'inventaris_sparepart' => ['admin'],
	'pengingat_servis' => ['admin'],
	'antrian' => ['admin', 'mekanik', 'teknisi'],
];

if (isset($pageRoles[$page]) && count(array_intersect($roleAliases, $pageRoles[$page])) === 0) {
	http_response_code(403);
	echo 'Anda tidak memiliki akses ke halaman ini.';
	exit;
}

switch ($page) {
	case 'login':
	    require_once __DIR__ . '/../app/controllers/AuthController.php';
	    $controller = new AuthController();
	    $controller->login();
	    break;
	case 'logout':
	    require_once __DIR__ . '/../app/controllers/AuthController.php';
	    $controller = new AuthController();
	    $controller->logout();
	    break;
	case 'dashboard':
	    require_once __DIR__ . '/../app/controllers/DashboardController.php';
	    $controller = new DashboardController();
	    $controller->index();
	    break;
	case 'pelanggan':
	case 'database_pelanggan':
	    require_once __DIR__ . '/../app/controllers/PelangganController.php';
	    $controller = new PelangganController();
	    $controller->index();
	    break;
	case 'antrian':
	case 'inventaris_sparepart':
	case 'pengingat_servis':
	    require_once __DIR__ . '/../app/views/' . $page . '.php';
	    break;
	default:
	    http_response_code(404);
	    echo 'Halaman tidak ditemukan.';
	    break;
}
