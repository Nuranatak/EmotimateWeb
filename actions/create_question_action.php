<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_check.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/../includes/validation.php';
require_once __DIR__ . '/../includes/question_helpers.php';
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . app_url('admin/create_question.php'), true, 302);
    exit;
}

if (!csrf_verify()) {
    set_flash('error', 'Invalid security token. Please try again.');
    header('Location: ' . app_url('admin/create_question.php'), true, 302);
    exit;
}

$testId = filter_input(INPUT_POST, 'test_id', FILTER_VALIDATE_INT) ?: 0;
$questionText = trim($_POST['question_text'] ?? '');

$errors = validate_question_input($testId, $questionText);

if (!empty($errors)) {
    set_flash('error', implode(' ', $errors));
    header('Location: ' . app_url('admin/create_question.php?test_id=' . $testId), true, 302);
    exit;
}

if (!test_exists($conn, $testId)) {
    set_flash('error', 'Selected test does not exist.');
    header('Location: ' . app_url('admin/create_question.php'), true, 302);
    exit;
}

$stmt = $conn->prepare('INSERT INTO questions (test_id, question_text) VALUES (?, ?)');

if (!$stmt) {
    set_flash('error', 'Could not prepare database query.');
    header('Location: ' . app_url('admin/create_question.php?test_id=' . $testId), true, 302);
    exit;
}

$stmt->bind_param('is', $testId, $questionText);

if ($stmt->execute()) {
    set_flash('success', 'Question created successfully.');
    header('Location: ' . app_url('admin/questions.php?test_id=' . $testId), true, 302);
    exit;
}

set_flash('error', 'Failed to create question. Please try again.');
header('Location: ' . app_url('admin/create_question.php?test_id=' . $testId), true, 302);
exit;
