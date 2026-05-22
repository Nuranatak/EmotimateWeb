<?php

declare(strict_types=1);

/**
 * Require an authenticated administrator.
 * Guests are sent to admin login; non-admins receive an access denied message.
 */

require_once __DIR__ . '/session.php';
require_once __DIR__ . '/role_helpers.php';
require_once __DIR__ . '/flash.php';
require_once __DIR__ . '/../config/database.php';

session_start_safe();

if (!isset($_SESSION['user_id'])) {
    set_flash('error', 'Please sign in with an administrator account to continue.');
    header('Location: ' . app_url('admin/login.php'), true, 302);
    exit;
}

$userId = (int) $_SESSION['user_id'];

sync_user_role_from_db($conn);
$dbIsAdmin = user_is_admin_in_db($conn, $userId);

if (!$dbIsAdmin) {
    set_flash('error', 'Access denied. Administrator privileges are required to view this page.');
    header('Location: ' . app_url('user/dashboard.php'), true, 302);
    exit;
}

$_SESSION['user_role'] = 'admin';
