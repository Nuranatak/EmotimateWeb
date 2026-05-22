<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_check.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/flash.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . app_url('admin/questions.php'), true, 302);
    exit;
}

$questionId = (int) ($_POST['question_id'] ?? $_POST['id'] ?? 0);
$testId = (int) ($_POST['test_id'] ?? 0);

if ($questionId < 1) {
    set_flash('error', 'Invalid question selected.');
    header('Location: ' . app_url('admin/questions.php'), true, 302);
    exit;
}

if (!csrf_verify()) {
    set_flash('error', 'Invalid security token. Please try again.');
    header('Location: ' . app_url('admin/questions.php?test_id=' . $testId), true, 302);
    exit;
}

// $conn is already loaded by admin_check.php
$checkStmt = $conn->prepare('SELECT test_id FROM questions WHERE id = ? LIMIT 1');

if (!$checkStmt) {
    set_flash('error', 'Database error: ' . $conn->error);
    header('Location: ' . app_url('admin/questions.php'), true, 302);
    exit;
}

$checkStmt->bind_param('i', $questionId);
$checkStmt->execute();
$checkStmt->bind_result($dbTestId);

if (!$checkStmt->fetch()) {
    $checkStmt->close();
    set_flash('error', 'Question not found.');
    header('Location: ' . app_url('admin/questions.php?test_id=' . $testId), true, 302);
    exit;
}

$checkStmt->close();

if ($testId < 1) {
    $testId = (int) $dbTestId;
}

$redirectUrl = app_url('admin/questions.php?test_id=' . $testId);

// Delete related answers first (if table exists)
$answersStmt = $conn->prepare('DELETE FROM answers WHERE question_id = ?');

if ($answersStmt) {
    $answersStmt->bind_param('i', $questionId);
    $answersStmt->execute();
    $answersStmt->close();
}

$deleteStmt = $conn->prepare('DELETE FROM questions WHERE id = ? LIMIT 1');

if (!$deleteStmt) {
    set_flash('error', 'Database error: ' . $conn->error);
    header('Location: ' . $redirectUrl, true, 302);
    exit;
}

$deleteStmt->bind_param('i', $questionId);

if ($deleteStmt->execute() && $deleteStmt->affected_rows > 0) {
    $deleteStmt->close();
    set_flash('success', 'Question deleted successfully.');
    header('Location: ' . $redirectUrl, true, 302);
    exit;
}

$error = $deleteStmt->error ?: $conn->error;
$deleteStmt->close();

set_flash('error', $error !== '' ? 'Could not delete question: ' . $error : 'Question could not be deleted.');
header('Location: ' . $redirectUrl, true, 302);
exit;
