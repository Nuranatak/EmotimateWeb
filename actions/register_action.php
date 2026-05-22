<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../config/database.php';

session_start_safe();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . app_url('auth/register.php'), true, 302);
    exit;
}

if (!csrf_verify()) {
    set_flash('error', 'Invalid security token. Please try again.');
    header('Location: ' . app_url('auth/register.php'), true, 302);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($name === '' || $email === '' || $password === '') {
    set_flash('error', 'Please fill in all fields.');
    header('Location: ' . app_url('auth/register.php'), true, 302);
    exit;
}

if (mb_strlen($name) < 2) {
    set_flash('error', 'Name must be at least 2 characters.');
    header('Location: ' . app_url('auth/register.php'), true, 302);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_flash('error', 'Please enter a valid email address.');
    header('Location: ' . app_url('auth/register.php'), true, 302);
    exit;
}

if (mb_strlen($password) < 6) {
    set_flash('error', 'Password must be at least 6 characters.');
    header('Location: ' . app_url('auth/register.php'), true, 302);
    exit;
}

$checkStmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');

if (!$checkStmt) {
    set_flash('error', 'Registration is unavailable right now. Please try again later.');
    header('Location: ' . app_url('auth/register.php'), true, 302);
    exit;
}

$checkStmt->bind_param('s', $email);
$checkStmt->execute();
$checkStmt->store_result();
$emailExists = $checkStmt->num_rows > 0;
$checkStmt->close();

if ($emailExists) {
    $message = strtolower($email) === 'admin@emotimate.com'
        ? 'This email is reserved for the platform administrator. Use the admin login instead.'
        : 'This email is already registered. Please sign in or use another email.';
    set_flash('error', $message);
    header('Location: ' . app_url('auth/register.php'), true, 302);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');

if (!$stmt) {
    set_flash('error', 'Registration failed. Please try again.');
    header('Location: ' . app_url('auth/register.php'), true, 302);
    exit;
}

$stmt->bind_param('sss', $name, $email, $hashedPassword);

if ($stmt->execute()) {
    $stmt->close();
    set_flash('success', 'Account created successfully. Please sign in.');
    header('Location: ' . app_url('auth/login.php'), true, 302);
    exit;
}

$stmt->close();
set_flash('error', 'Registration failed. Please try again.');
header('Location: ' . app_url('auth/register.php'), true, 302);
exit;
