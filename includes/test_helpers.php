<?php

declare(strict_types=1);

/**
 * @return list<array{
 *   id: int,
 *   title: string,
 *   description: string|null,
 *   category_id: int|null,
 *   category_name: string|null,
 *   question_count: int
 * }>
 */
function fetch_available_tests(mysqli $conn, ?int $categoryId = null): array
{
    require_once __DIR__ . '/category_helpers.php';
    ensure_test_categories($conn);

    $sql = 'SELECT t.id, t.title, t.description, t.category_id, c.name AS category_name,
                   COUNT(q.id) AS question_count
            FROM tests t
            LEFT JOIN test_categories c ON c.id = t.category_id
            LEFT JOIN questions q ON q.test_id = t.id
            WHERE (c.is_active = 1 OR c.id IS NULL)';

    if ($categoryId !== null && $categoryId > 0) {
        $sql .= ' AND t.category_id = ' . (int) $categoryId;
    }

    $sql .= ' GROUP BY t.id, t.title, t.description, t.category_id, c.name
              ORDER BY t.title ASC';

    $result = $conn->query($sql);

    if (!$result) {
        return [];
    }

    $tests = [];

    while ($row = $result->fetch_assoc()) {
        $tests[] = [
            'id'              => (int) $row['id'],
            'title'           => (string) $row['title'],
            'description'     => $row['description'] !== null ? (string) $row['description'] : null,
            'category_id'     => $row['category_id'] !== null ? (int) $row['category_id'] : null,
            'category_name'   => $row['category_name'] !== null ? (string) $row['category_name'] : null,
            'question_count'  => (int) $row['question_count'],
        ];
    }

    return $tests;
}

/**
 * Active categories that have at least one test (for member UI).
 *
 * @return list<array{id: int, name: string, description: string|null, sort_order: int, test_count: int}>
 */
function fetch_categories_with_tests(mysqli $conn): array
{
    require_once __DIR__ . '/category_helpers.php';
    ensure_test_categories($conn);

    $sql = 'SELECT c.id, c.name, c.description, c.sort_order, COUNT(t.id) AS test_count
            FROM test_categories c
            LEFT JOIN tests t ON t.category_id = c.id
            WHERE c.is_active = 1
            GROUP BY c.id, c.name, c.description, c.sort_order
            ORDER BY c.sort_order ASC, c.name ASC';

    $result = $conn->query($sql);

    if (!$result) {
        return [];
    }

    $rows = [];

    while ($row = $result->fetch_assoc()) {
        $rows[] = [
            'id'          => (int) $row['id'],
            'name'        => (string) $row['name'],
            'description' => $row['description'] !== null ? (string) $row['description'] : null,
            'sort_order'  => (int) $row['sort_order'],
            'test_count'  => (int) $row['test_count'],
        ];
    }

    return $rows;
}

/**
 * Add instructions column to tests when missing.
 */
function ensure_test_instructions_column(mysqli $conn): void
{
    $colCheck = $conn->query("SHOW COLUMNS FROM tests LIKE 'instructions'");

    if ($colCheck && $colCheck->num_rows === 0) {
        $conn->query('ALTER TABLE tests ADD COLUMN instructions TEXT NULL AFTER description');
    }
}

/**
 * @return array{id: int, title: string, description: string|null, instructions: string|null, category_id: int|null}|null
 */
function fetch_test_details(mysqli $conn, int $testId): ?array
{
    ensure_test_instructions_column($conn);

    $stmt = $conn->prepare('SELECT id, title, description, instructions, category_id FROM tests WHERE id = ? LIMIT 1');

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param('i', $testId);
    $stmt->execute();

    $id = $title = $description = $instructions = $categoryId = null;
    $stmt->bind_result($id, $title, $description, $instructions, $categoryId);

    if (!$stmt->fetch()) {
        $stmt->close();
        return null;
    }

    $stmt->close();

    return [
        'id'           => (int) $id,
        'title'        => (string) $title,
        'description'  => $description !== null ? (string) $description : null,
        'instructions' => $instructions !== null ? (string) $instructions : null,
        'category_id'  => $categoryId !== null ? (int) $categoryId : null,
    ];
}

/**
 * @return list<int>
 */
function fetch_question_ids_for_test(mysqli $conn, int $testId): array
{
    $stmt = $conn->prepare('SELECT id FROM questions WHERE test_id = ? ORDER BY id ASC');

    if (!$stmt) {
        return [];
    }

    $stmt->bind_param('i', $testId);
    $stmt->execute();

    $ids = [];
    $id = null;
    $stmt->bind_result($id);

    while ($stmt->fetch()) {
        $ids[] = (int) $id;
    }

    $stmt->close();

    return $ids;
}

/**
 * Map total Likert sum to a wellness interpretation tier.
 * Scoring: each question is 1–5; total = sum of all answers.
 *   0–10  → Low    |  11–20 → Medium  |  21+ → High
 */
function calculate_interpretation(int $score): string
{
    if ($score <= 10) {
        return 'Low';
    }

    if ($score <= 20) {
        return 'Medium';
    }

    return 'High';
}

/**
 * @return array{
 *   id: int,
 *   user_id: int,
 *   test_id: int,
 *   score: int,
 *   interpretation: string,
 *   created_at: string,
 *   test_title: string
 * }|null
 */
function fetch_result_for_user(mysqli $conn, int $resultId, int $userId): ?array
{
    $stmt = $conn->prepare(
        'SELECT r.id, r.user_id, r.test_id, r.score, r.interpretation, r.created_at, t.title
         FROM results r
         INNER JOIN tests t ON t.id = r.test_id
         WHERE r.id = ? AND r.user_id = ?
         LIMIT 1'
    );

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param('ii', $resultId, $userId);
    $stmt->execute();

    $id = $user = $testId = $score = $interpretation = $createdAt = $testTitle = null;
    $stmt->bind_result($id, $user, $testId, $score, $interpretation, $createdAt, $testTitle);

    if (!$stmt->fetch()) {
        $stmt->close();
        return null;
    }

    $stmt->close();

    return [
        'id'             => (int) $id,
        'user_id'        => (int) $user,
        'test_id'        => (int) $testId,
        'score'          => (int) $score,
        'interpretation' => (string) $interpretation,
        'created_at'     => (string) $createdAt,
        'test_title'     => (string) $testTitle,
    ];
}

/** Likert scale labels (value => label). */
function likert_labels(): array
{
    return [
        1 => 'Completely disagree',
        2 => 'Mostly disagree',
        3 => 'Neutral / Sometimes true',
        4 => 'Mostly agree',
        5 => 'Completely agree',
    ];
}
