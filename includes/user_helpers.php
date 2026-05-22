<?php

declare(strict_types=1);

/**
 * @return list<array{
 *   id: int,
 *   test_id: int,
 *   test_title: string,
 *   score: int,
 *   interpretation: string,
 *   created_at: string
 * }>
 */
function fetch_user_results_history(mysqli $conn, int $userId, int $limit = 50): array
{
    $stmt = $conn->prepare(
        'SELECT r.id, r.test_id, r.score, r.interpretation, r.created_at, t.title
         FROM results r
         INNER JOIN tests t ON t.id = r.test_id
         WHERE r.user_id = ?
         ORDER BY r.created_at DESC
         LIMIT ?'
    );

    if (!$stmt) {
        return [];
    }

    $stmt->bind_param('ii', $userId, $limit);
    $stmt->execute();

    $results = [];
    $id = $testId = $score = $interpretation = $createdAt = $title = null;
    $stmt->bind_result($id, $testId, $score, $interpretation, $createdAt, $title);

    while ($stmt->fetch()) {
        $results[] = [
            'id'             => (int) $id,
            'test_id'        => (int) $testId,
            'test_title'     => (string) $title,
            'score'          => (int) $score,
            'interpretation' => (string) $interpretation,
            'created_at'     => (string) $createdAt,
        ];
    }

    $stmt->close();

    return $results;
}

function count_user_results(mysqli $conn, int $userId): int
{
    $stmt = $conn->prepare('SELECT COUNT(*) FROM results WHERE user_id = ?');

    if (!$stmt) {
        return 0;
    }

    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return (int) $count;
}

/**
 * @return array{id: int, test_title: string, score: int, interpretation: string, created_at: string}|null
 */
function fetch_latest_user_result(mysqli $conn, int $userId): ?array
{
    $stmt = $conn->prepare(
        'SELECT r.id, t.title, r.score, r.interpretation, r.created_at
         FROM results r
         INNER JOIN tests t ON t.id = r.test_id
         WHERE r.user_id = ?
         ORDER BY r.created_at DESC
         LIMIT 1'
    );

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param('i', $userId);
    $stmt->execute();

    $id = $title = $score = $interpretation = $createdAt = null;
    $stmt->bind_result($id, $title, $score, $interpretation, $createdAt);

    if (!$stmt->fetch()) {
        $stmt->close();
        return null;
    }

    $stmt->close();

    return [
        'id'             => (int) $id,
        'test_title'     => (string) $title,
        'score'          => (int) $score,
        'interpretation' => (string) $interpretation,
        'created_at'     => (string) $createdAt,
    ];
}

function interpretation_badge_class(string $interpretation): string
{
    return match ($interpretation) {
        'Low', 'Minimal', 'Strong' => 'interpretation-low',
        'Mild' => 'interpretation-mild',
        'Moderate', 'ModeratelySevere', 'Medium' => 'interpretation-moderate',
        'High', 'Severe', 'Significant' => 'interpretation-high',
        default => 'interpretation-medium',
    };
}

/**
 * Tests completed per test title (for bar chart).
 *
 * @return array{labels: list<string>, values: list<int>}
 */
function fetch_user_tests_per_test(mysqli $conn, int $userId): array
{
    $stmt = $conn->prepare(
        'SELECT t.title, COUNT(*) AS total
         FROM results r
         INNER JOIN tests t ON t.id = r.test_id
         WHERE r.user_id = ?
         GROUP BY r.test_id, t.title
         ORDER BY total DESC
         LIMIT 8'
    );

    $labels = [];
    $values = [];

    if (!$stmt) {
        return ['labels' => $labels, 'values' => $values];
    }

    $stmt->bind_param('i', $userId);
    $stmt->execute();

    $title = $total = null;
    $stmt->bind_result($title, $total);

    while ($stmt->fetch()) {
        $labels[] = (string) $title;
        $values[] = (int) $total;
    }

    $stmt->close();

    return ['labels' => $labels, 'values' => $values];
}

/**
 * Recent score timeline (oldest first for line chart).
 *
 * @return array{labels: list<string>, values: list<int>}
 */
function fetch_user_score_timeline(mysqli $conn, int $userId, int $limit = 10): array
{
    $stmt = $conn->prepare(
        'SELECT r.score, r.created_at, t.title
         FROM results r
         INNER JOIN tests t ON t.id = r.test_id
         WHERE r.user_id = ?
         ORDER BY r.created_at DESC
         LIMIT ?'
    );

    if (!$stmt) {
        return ['labels' => [], 'values' => []];
    }

    $stmt->bind_param('ii', $userId, $limit);
    $stmt->execute();

    $rows = [];
    $score = $createdAt = $title = null;
    $stmt->bind_result($score, $createdAt, $title);

    while ($stmt->fetch()) {
        $rows[] = [
            'score'      => (int) $score,
            'created_at' => (string) $createdAt,
            'title'      => (string) $title,
        ];
    }

    $stmt->close();
    $rows = array_reverse($rows);

    $labels = [];
    $values = [];

    foreach ($rows as $index => $row) {
        $labels[] = date('M j', strtotime($row['created_at']));
        $values[] = $row['score'];
    }

    return ['labels' => $labels, 'values' => $values];
}
