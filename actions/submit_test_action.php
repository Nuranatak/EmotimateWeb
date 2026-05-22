<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/../includes/test_helpers.php';
require_once __DIR__ . '/../includes/question_helpers.php';
require_once __DIR__ . '/../includes/schema_interpretation_helpers.php';
require_once __DIR__ . '/../includes/assessment_interpretation_helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . app_url('tests/index.php'), true, 302);
    exit;
}

if (!csrf_verify()) {
    set_flash('error', 'Invalid security token. Please try again.');
    header('Location: ' . app_url('tests/index.php'), true, 302);
    exit;
}

$testId = filter_input(INPUT_POST, 'test_id', FILTER_VALIDATE_INT) ?: 0;
$userId = (int) $_SESSION['user_id'];
$postedAnswers = $_POST['answers'] ?? [];

if ($testId < 1 || !is_array($postedAnswers)) {
    set_flash('error', 'Invalid test submission.');
    header('Location: ' . app_url('tests/index.php'), true, 302);
    exit;
}

$test = fetch_test_details($conn, $testId);

if ($test === null) {
    set_flash('error', 'Test not found.');
    header('Location: ' . app_url('tests/index.php'), true, 302);
    exit;
}

$questionIds = fetch_question_ids_for_test($conn, $testId);

if ($questionIds === []) {
    set_flash('error', 'This test has no questions.');
    header('Location: ' . app_url('tests/index.php'), true, 302);
    exit;
}

$validatedAnswers = [];

foreach ($questionIds as $questionId) {
    if (!array_key_exists($questionId, $postedAnswers) && !array_key_exists((string) $questionId, $postedAnswers)) {
        set_flash('error', 'Please answer every question before submitting.');
        header('Location: ' . app_url('tests/take_test.php?test_id=' . $testId), true, 302);
        exit;
    }

    $rawValue = $postedAnswers[$questionId] ?? $postedAnswers[(string) $questionId] ?? null;
    $value = filter_var($rawValue, FILTER_VALIDATE_INT);

    if ($value === false || $value < 1 || $value > 5) {
        set_flash('error', 'Each answer must be a value between 1 and 5.');
        header('Location: ' . app_url('tests/take_test.php?test_id=' . $testId), true, 302);
        exit;
    }

    $validatedAnswers[$questionId] = $value;
}

// Sum Likert values (1–5 per question) and derive interpretation tier
$totalScore = array_sum($validatedAnswers);
$questionCount = count($questionIds);

$interpretation = resolve_test_interpretation(
    $conn,
    $testId,
    $test['title'],
    $totalScore,
    $questionCount
);

$resultStmt = $conn->prepare(
    'INSERT INTO results (user_id, test_id, score, interpretation) VALUES (?, ?, ?, ?)'
);

if (!$resultStmt) {
    set_flash('error', 'Could not save your result. Please try again.');
    header('Location: ' . app_url('tests/take_test.php?test_id=' . $testId), true, 302);
    exit;
}

$resultStmt->bind_param('iiis', $userId, $testId, $totalScore, $interpretation);

if (!$resultStmt->execute()) {
    $resultStmt->close();
    set_flash('error', 'Could not save your result. Please try again.');
    header('Location: ' . app_url('tests/take_test.php?test_id=' . $testId), true, 302);
    exit;
}

$resultId = (int) $conn->insert_id;
$resultStmt->close();

$answerStmt = $conn->prepare(
    'INSERT INTO answers (user_id, question_id, value) VALUES (?, ?, ?)'
);

if (!$answerStmt) {
    set_flash('error', 'Could not save your answers. Please try again.');
    header('Location: ' . app_url('tests/take_test.php?test_id=' . $testId), true, 302);
    exit;
}

foreach ($validatedAnswers as $questionId => $value) {
    $qId = (int) $questionId;
    $answerStmt->bind_param('iii', $userId, $qId, $value);

    if (!$answerStmt->execute()) {
        $answerStmt->close();
        set_flash('error', 'Could not save all answers. Please try again.');
        header('Location: ' . app_url('tests/take_test.php?test_id=' . $testId), true, 302);
        exit;
    }
}

$answerStmt->close();

header('Location: ' . app_url('tests/result.php?id=' . $resultId), true, 302);
exit;
