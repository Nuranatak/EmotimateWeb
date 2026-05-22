<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_check.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . app_url('admin/tests.php'), true, 302);
    exit;
}

$testId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$testId || $testId < 1) {
    set_flash('error', 'Invalid test selected.');
    header('Location: ' . app_url('admin/tests.php'), true, 302);
    exit;
}

if (!csrf_verify()) {
    set_flash('error', 'Invalid security token. Please try again.');
    header('Location: ' . app_url('admin/tests.php'), true, 302);
    exit;
}

$sql = 'DELETE FROM tests WHERE id = ?';
$stmt = $conn->prepare($sql);

if (!$stmt) {
    set_flash('error', 'Could not prepare database query.');
    header('Location: ' . app_url('admin/tests.php'), true, 302);
    exit;
}

$stmt->bind_param('i', $testId);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    $stmt->close();
    set_flash('success', 'Test deleted successfully.');
    header('Location: ' . app_url('admin/tests.php'), true, 302);
    exit;
}

$stmt->close();
set_flash('error', 'Test not found or already deleted.');
header('Location: ' . app_url('admin/tests.php'), true, 302);
exit;
