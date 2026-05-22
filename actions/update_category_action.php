<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_check.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/../includes/validation.php';
require_once __DIR__ . '/../includes/category_helpers.php';
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . app_url('admin/categories.php'), true, 302);
    exit;
}

$categoryId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$categoryId || $categoryId < 1) {
    set_flash('error', 'Invalid category.');
    header('Location: ' . app_url('admin/categories.php'), true, 302);
    exit;
}

if (!csrf_verify()) {
    set_flash('error', 'Invalid security token. Please try again.');
    header('Location: ' . app_url('admin/edit_category.php?id=' . $categoryId), true, 302);
    exit;
}

$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$sortOrder = filter_input(INPUT_POST, 'sort_order', FILTER_VALIDATE_INT);
$sortOrder = $sortOrder !== false && $sortOrder !== null ? (int) $sortOrder : 0;
$isActive = isset($_POST['is_active']) ? 1 : 0;

$errors = validate_category_input($name, $description, $sortOrder);

if (!empty($errors)) {
    set_flash('error', implode(' ', $errors));
    header('Location: ' . app_url('admin/edit_category.php?id=' . $categoryId), true, 302);
    exit;
}

$stmt = $conn->prepare(
    'UPDATE test_categories SET name = ?, description = ?, sort_order = ?, is_active = ? WHERE id = ?'
);

if (!$stmt) {
    set_flash('error', 'Could not update category.');
    header('Location: ' . app_url('admin/edit_category.php?id=' . $categoryId), true, 302);
    exit;
}

$stmt->bind_param('ssiii', $name, $description, $sortOrder, $isActive, $categoryId);

if ($stmt->execute()) {
    $stmt->close();
    set_flash('success', 'Category updated successfully.');
    header('Location: ' . app_url('admin/categories.php'), true, 302);
    exit;
}

$stmt->close();
set_flash('error', 'Failed to update category.');
header('Location: ' . app_url('admin/edit_category.php?id=' . $categoryId), true, 302);
exit;
