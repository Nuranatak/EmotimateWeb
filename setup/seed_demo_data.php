<?php

declare(strict_types=1);

/**
 * Idempotent demo data seeder — safe on existing databases (skips duplicates).
 * http://localhost/emotimate/setup/seed_demo_data.php
 */

require_once __DIR__ . '/../includes/setup_guard.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/category_helpers.php';
require_once __DIR__ . '/../includes/demo_seed_definitions.php';
require_once __DIR__ . '/../includes/test_helpers.php';
require_once __DIR__ . '/../includes/schema_interpretation_helpers.php';
require_once __DIR__ . '/../includes/assessment_interpretation_helpers.php';

header('Content-Type: text/html; charset=utf-8');

ensure_test_categories($conn);
ensure_test_instructions_column($conn);

$messages = [];
$stats = [
    'users_created'     => 0,
    'users_updated'     => 0,
    'tests_created'     => 0,
    'questions_created' => 0,
    'results_created'   => 0,
    'answers_created'   => 0,
];

foreach (demo_users() as $key => $userData) {
    $email = strtolower($userData['email']);
    $name = $userData['name'];
    $role = $userData['role'];
    $hash = password_hash($userData['password_plain'], PASSWORD_DEFAULT);

    $check = $conn->prepare('SELECT id FROM users WHERE LOWER(email) = ? LIMIT 1');
    $check->bind_param('s', $email);
    $check->execute();
    $check->store_result();
    $exists = $check->num_rows > 0;
    $userId = null;
    $check->bind_result($userId);

    if ($exists) {
        $check->fetch();
    }

    $check->close();

    if ($exists && $userId) {
        $upd = $conn->prepare('UPDATE users SET name = ?, password = ?, role = ? WHERE id = ?');
        $upd->bind_param('sssi', $name, $hash, $role, $userId);
        $upd->execute();
        $upd->close();
        $stats['users_updated']++;
        $messages[] = "User updated: {$email}";
    } else {
        $ins = $conn->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
        $ins->bind_param('ssss', $name, $email, $hash, $role);
        $ins->execute();
        $ins->close();
        $stats['users_created']++;
        $messages[] = "User created: {$email}";
    }
}

$categoryIds = [];
$catResult = $conn->query('SELECT id, name FROM test_categories');

if ($catResult) {
    while ($row = $catResult->fetch_assoc()) {
        $categoryIds[$row['name']] = (int) $row['id'];
    }
}

$testIdsByTitle = [];

foreach (demo_tests_catalog() as $testData) {
    $categoryName = $testData['category'];

    if (!isset($categoryIds[$categoryName])) {
        $messages[] = "Skipped test (category missing): {$testData['title']}";
        continue;
    }

    $categoryId = $categoryIds[$categoryName];
    $title = $testData['title'];
    $description = $testData['description'];
    $instructions = $testData['instructions'];

    $find = $conn->prepare('SELECT id FROM tests WHERE title = ? LIMIT 1');
    $find->bind_param('s', $title);
    $find->execute();
    $testId = null;
    $find->bind_result($testId);
    $found = $find->fetch();
    $find->close();

    if ($found && $testId) {
        $testId = (int) $testId;
        $upd = $conn->prepare(
            'UPDATE tests SET category_id = ?, description = ?, instructions = ? WHERE id = ?'
        );
        $upd->bind_param('issi', $categoryId, $description, $instructions, $testId);
        $upd->execute();
        $upd->close();
    } else {
        $ins = $conn->prepare(
            'INSERT INTO tests (category_id, title, description, instructions) VALUES (?, ?, ?, ?)'
        );
        $ins->bind_param('isss', $categoryId, $title, $description, $instructions);
        $ins->execute();
        $testId = (int) $conn->insert_id;
        $ins->close();
        $stats['tests_created']++;
        $messages[] = "Test created: {$title}";
    }

    $testIdsByTitle[$title] = $testId;

    foreach ($testData['questions'] as $questionText) {
        $qFind = $conn->prepare('SELECT id FROM questions WHERE test_id = ? AND question_text = ? LIMIT 1');
        $qFind->bind_param('is', $testId, $questionText);
        $qFind->execute();
        $qFind->store_result();

        if ($qFind->num_rows > 0) {
            $qFind->close();
            continue;
        }

        $qFind->close();

        $qIns = $conn->prepare('INSERT INTO questions (test_id, question_text) VALUES (?, ?)');
        $qIns->bind_param('is', $testId, $questionText);
        $qIns->execute();
        $qIns->close();
        $stats['questions_created']++;
    }
}

$memberEmail = strtolower(demo_users()['member']['email']);
$memberStmt = $conn->prepare('SELECT id FROM users WHERE LOWER(email) = ? LIMIT 1');
$memberStmt->bind_param('s', $memberEmail);
$memberStmt->execute();
$memberId = null;
$memberStmt->bind_result($memberId);
$memberStmt->fetch();
$memberStmt->close();

if ($memberId) {
    $memberId = (int) $memberId;

    foreach (demo_member_sample_results() as $sample) {
        $title = $sample['test_title'];

        if (!isset($testIdsByTitle[$title])) {
            continue;
        }

        $testId = $testIdsByTitle[$title];

        $existsResult = $conn->prepare(
            'SELECT id FROM results WHERE user_id = ? AND test_id = ? LIMIT 1'
        );
        $existsResult->bind_param('ii', $memberId, $testId);
        $existsResult->execute();
        $existsResult->store_result();

        if ($existsResult->num_rows > 0) {
            $existsResult->close();
            $messages[] = "Result already exists for: {$title}";
            continue;
        }

        $existsResult->close();

        $qStmt = $conn->prepare('SELECT id FROM questions WHERE test_id = ? ORDER BY id ASC');
        $qStmt->bind_param('i', $testId);
        $qStmt->execute();
        $questionIds = [];
        $qid = null;
        $qStmt->bind_result($qid);

        while ($qStmt->fetch()) {
            $questionIds[] = (int) $qid;
        }

        $qStmt->close();

        if (count($questionIds) !== count($sample['answers'])) {
            $messages[] = "Answer count mismatch for {$title}; skipped result seed.";
            continue;
        }

        $totalScore = array_sum($sample['answers']);
        $interpretation = resolve_test_interpretation($conn, $testId, $title, $totalScore, count($questionIds));

        $rIns = $conn->prepare(
            'INSERT INTO results (user_id, test_id, score, interpretation) VALUES (?, ?, ?, ?)'
        );
        $rIns->bind_param('iiis', $memberId, $testId, $totalScore, $interpretation);
        $rIns->execute();
        $resultId = (int) $conn->insert_id;
        $rIns->close();
        $stats['results_created']++;

        $aIns = $conn->prepare('INSERT INTO answers (user_id, question_id, value) VALUES (?, ?, ?)');

        foreach ($questionIds as $index => $questionId) {
            $value = (int) $sample['answers'][$index];
            $aIns->bind_param('iii', $memberId, $questionId, $value);
            $aIns->execute();
            $stats['answers_created']++;
        }

        $aIns->close();
        $messages[] = "Sample result seeded: {$title} (score {$totalScore}, {$interpretation})";
    }
}

$counts = $conn->query(
    'SELECT
        (SELECT COUNT(*) FROM users) AS users,
        (SELECT COUNT(*) FROM tests) AS tests,
        (SELECT COUNT(*) FROM questions) AS questions,
        (SELECT COUNT(*) FROM results) AS results,
        (SELECT COUNT(*) FROM answers) AS answers'
)->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Demo Data Seed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
    <div class="alert alert-success" style="max-width: 720px;">
        <h1 class="h5">Demo data ready</h1>
        <p class="mb-2"><strong>Accounts</strong></p>
        <ul>
            <li>Admin: <code>admin@emotimate.com</code> / <code>admin123</code></li>
            <li>Member: <code>sarah.demo@emotimate.com</code> / <code>user123</code></li>
        </ul>
        <p class="mb-2"><strong>This run</strong></p>
        <ul class="small mb-3">
            <?php foreach ($stats as $label => $count): ?>
                <li><?= htmlspecialchars(str_replace('_', ' ', $label), ENT_QUOTES, 'UTF-8') ?>: <?= (int) $count ?></li>
            <?php endforeach; ?>
        </ul>
        <p class="mb-2"><strong>Database totals</strong></p>
        <ul class="small mb-3">
            <li>Users: <?= (int) ($counts['users'] ?? 0) ?></li>
            <li>Tests: <?= (int) ($counts['tests'] ?? 0) ?></li>
            <li>Questions: <?= (int) ($counts['questions'] ?? 0) ?></li>
            <li>Results: <?= (int) ($counts['results'] ?? 0) ?></li>
            <li>Answers: <?= (int) ($counts['answers'] ?? 0) ?></li>
        </ul>
        <details class="mb-3">
            <summary class="fw-semibold">Log</summary>
            <ul class="small mb-0 mt-2">
                <?php foreach ($messages as $msg): ?>
                    <li><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </details>
        <a href="<?= htmlspecialchars(app_url('index.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-success btn-sm me-2">Home</a>
        <a href="<?= htmlspecialchars(app_url('auth/login.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-success btn-sm me-2">Member login</a>
        <a href="<?= htmlspecialchars(app_url('admin/login.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-success btn-sm">Admin login</a>
    </div>
</body>
</html>
