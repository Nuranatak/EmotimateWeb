<?php

declare(strict_types=1);

/**
 * Removes all tests, questions, results, and answers. Categories are kept.
 * Run once: http://localhost/emotimate/setup/clear_tests.php
 */

require_once __DIR__ . '/../includes/setup_guard.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/category_helpers.php';

header('Content-Type: text/html; charset=utf-8');

ensure_test_categories($conn);

$counts = [
    'tests'     => 0,
    'questions' => 0,
    'results'   => 0,
    'answers'   => 0,
];

foreach (array_keys($counts) as $table) {
    $result = $conn->query("SELECT COUNT(*) AS c FROM `{$table}`");

    if ($result) {
        $row = $result->fetch_assoc();
        $counts[$table] = (int) ($row['c'] ?? 0);
    }
}

$ok = $conn->query('DELETE FROM tests');

if (!$ok) {
    http_response_code(500);
    die('<p class="alert alert-danger">Could not delete tests: ' . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8') . '</p>');
}

$categoryCount = 0;
$catResult = $conn->query('SELECT COUNT(*) AS c FROM test_categories');

if ($catResult) {
    $row = $catResult->fetch_assoc();
    $categoryCount = (int) ($row['c'] ?? 0);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clear Tests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
    <div class="alert alert-success" style="max-width: 560px;">
        <h1 class="h5">Tests cleared</h1>
        <p class="mb-2">Removed from database:</p>
        <ul>
            <li><?= $counts['tests'] ?> test(s)</li>
            <li><?= $counts['questions'] ?> question(s)</li>
            <li><?= $counts['results'] ?> result(s)</li>
            <li><?= $counts['answers'] ?> answer(s)</li>
        </ul>
        <p class="mb-0"><strong><?= $categoryCount ?></strong> categor<?= $categoryCount === 1 ? 'y' : 'ies' ?> kept. Add tests via Admin or phpMyAdmin.</p>
        <hr>
        <a href="<?= htmlspecialchars(app_url('admin/tests.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-success btn-sm me-2">Admin: Tests</a>
        <a href="<?= htmlspecialchars(app_url('admin/categories.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-success btn-sm">Categories</a>
    </div>
</body>
</html>
