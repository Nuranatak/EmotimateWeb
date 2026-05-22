<?php

declare(strict_types=1);

/**
 * Require an authenticated member (non-administrator).
 * Guests → member login. Administrators → admin dashboard.
 */

require_once __DIR__ . '/session.php';
require_once __DIR__ . '/role_helpers.php';
require_once __DIR__ . '/flash.php';

session_start_safe();

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . app_url('auth/login.php'), true, 302);
    exit;
}

require_once __DIR__ . '/../config/database.php';

$userId = (int) $_SESSION['user_id'];
sync_user_role_from_db($conn);

if (user_is_admin_in_db($conn, $userId)) {
    $_SESSION['user_role'] = 'admin';
    set_flash('error', 'Administrator accounts must use the admin panel. Member test features are not available for admins.');
    header('Location: ' . app_url('admin/dashboard.php'), true, 302);
    exit;
}

$_SESSION['user_role'] = 'user';
