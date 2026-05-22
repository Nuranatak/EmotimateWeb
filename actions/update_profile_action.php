<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/../includes/validation.php';
require_once __DIR__ . '/../includes/role_helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . app_url('user/profile.php'), true, 302);
    exit;
}

if (!csrf_verify()) {
    set_flash('error', 'Invalid security token. Please try again.');
    header('Location: ' . app_url('user/profile.php'), true, 302);
    exit;
}

$userId = (int) $_SESSION['user_id'];
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$currentPassword = $_POST['current_password'] ?? '';
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

$errors = validate_profile_input($name, $email, $currentPassword, $newPassword, $confirmPassword);

if (!empty($errors)) {
    set_flash('error', implode(' ', $errors));
    header('Location: ' . app_url('user/profile.php'), true, 302);
    exit;
}

$user = fetch_user_with_password($conn, $userId);

if ($user === null) {
    set_flash('error', 'User profile not found.');
    header('Location: ' . app_url('user/profile.php'), true, 302);
    exit;
}

if (!password_verify($currentPassword, $user['password'])) {
    set_flash('error', 'Current password is incorrect.');
    header('Location: ' . app_url('user/profile.php'), true, 302);
    exit;
}

$checkStmt = $conn->prepare('SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1');
$checkStmt->bind_param('si', $email, $userId);
$checkStmt->execute();
$checkStmt->store_result();
$emailTaken = $checkStmt->num_rows > 0;
$checkStmt->close();

if ($emailTaken) {
    set_flash('error', 'This email is already registered to another account.');
    header('Location: ' . app_url('user/profile.php'), true, 302);
    exit;
}

if ($newPassword !== '') {
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?');
    $stmt->bind_param('sssi', $name, $email, $hash, $userId);
} else {
    $stmt = $conn->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?');
    $stmt->bind_param('ssi', $name, $email, $userId);
}

if (!$stmt) {
    set_flash('error', 'Could not update profile.');
    header('Location: ' . app_url('user/profile.php'), true, 302);
    exit;
}

if ($stmt->execute()) {
    $_SESSION['user_name'] = $name;
    $message = $newPassword !== ''
        ? 'Profile and password updated successfully.'
        : 'Profile updated successfully.';
    set_flash('success', $message);
    header('Location: ' . app_url('user/profile.php'), true, 302);
    exit;
}

set_flash('error', 'Could not update profile. Please try again.');
header('Location: ' . app_url('user/profile.php'), true, 302);
exit;
