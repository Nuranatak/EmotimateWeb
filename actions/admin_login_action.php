<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/role_helpers.php';
require_once __DIR__ . '/../includes/auth_helpers.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../config/database.php';

session_start_safe();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . app_url('admin/login.php'), true, 302);
    exit;
}

if (!csrf_verify()) {
    set_flash('error', 'Invalid security token. Please try again.');
    header('Location: ' . app_url('admin/login.php'), true, 302);
    exit;
}

$email = strtolower(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    set_flash('error', 'Please enter your email and password.');
    header('Location: ' . app_url('admin/login.php'), true, 302);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_flash('error', 'Please enter a valid email address.');
    header('Location: ' . app_url('admin/login.php'), true, 302);
    exit;
}

$user = authenticate_user($conn, $email, $password);

if ($user === null) {
    set_flash('error', 'Invalid email or password.');
    header('Location: ' . app_url('admin/login.php'), true, 302);
    exit;
}

if (!user_is_admin_in_db($conn, $user['id'])) {
    set_flash('error', 'Access denied. This account does not have administrator privileges.');
    header('Location: ' . app_url('admin/login.php'), true, 302);
    exit;
}

login_user_session($user, $conn);
$_SESSION['user_role'] = 'admin';

set_flash('success', 'Welcome, ' . $user['name'] . '. Admin panel loaded.');
header('Location: ' . app_url('admin/dashboard.php'), true, 302);
exit;
