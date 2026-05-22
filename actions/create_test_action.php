<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_check.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/../includes/validation.php';
require_once __DIR__ . '/../includes/category_helpers.php';
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . app_url('admin/create_test.php'), true, 302);
    exit;
}

if (!csrf_verify()) {
    set_flash('error', 'Invalid security token. Please try again.');
    header('Location: ' . app_url('admin/create_test.php'), true, 302);
    exit;
}

$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$categoryId = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT) ?: 0;

$errors = validate_test_input($title, $description, $categoryId);

if (empty($errors) && !category_exists($conn, $categoryId)) {
    $errors[] = 'Please select a valid category.';
}

if (!empty($errors)) {
    set_flash('error', implode(' ', $errors));
    header('Location: ' . app_url('admin/create_test.php'), true, 302);
    exit;
}

$sql = 'INSERT INTO tests (category_id, title, description) VALUES (?, ?, ?)';
$stmt = $conn->prepare($sql);

if (!$stmt) {
    set_flash('error', 'Could not prepare database query.');
    header('Location: ' . app_url('admin/create_test.php'), true, 302);
    exit;
}

$stmt->bind_param('iss', $categoryId, $title, $description);

if ($stmt->execute()) {
    set_flash('success', 'Test created successfully.');
    header('Location: ' . app_url('admin/tests.php'), true, 302);
    exit;
}

set_flash('error', 'Failed to create test. Please try again.');
header('Location: ' . app_url('admin/create_test.php'), true, 302);
exit;
