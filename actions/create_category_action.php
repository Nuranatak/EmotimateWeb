<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_check.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/../includes/validation.php';
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . app_url('admin/categories.php'), true, 302);
    exit;
}

if (!csrf_verify()) {
    set_flash('error', 'Invalid security token. Please try again.');
    header('Location: ' . app_url('admin/create_category.php'), true, 302);
    exit;
}

$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$sortOrder = filter_input(INPUT_POST, 'sort_order', FILTER_VALIDATE_INT);
$sortOrder = $sortOrder !== false && $sortOrder !== null ? (int) $sortOrder : 0;

$errors = validate_category_input($name, $description, $sortOrder);

if (!empty($errors)) {
    set_flash('error', implode(' ', $errors));
    header('Location: ' . app_url('admin/create_category.php'), true, 302);
    exit;
}

$stmt = $conn->prepare('INSERT INTO test_categories (name, description, sort_order) VALUES (?, ?, ?)');

if (!$stmt) {
    set_flash('error', 'Could not create category.');
    header('Location: ' . app_url('admin/create_category.php'), true, 302);
    exit;
}

$stmt->bind_param('ssi', $name, $description, $sortOrder);

if ($stmt->execute()) {
    $stmt->close();
    set_flash('success', 'Category created successfully.');
    header('Location: ' . app_url('admin/categories.php'), true, 302);
    exit;
}

$stmt->close();
set_flash('error', 'Failed to create category.');
header('Location: ' . app_url('admin/create_category.php'), true, 302);
exit;
