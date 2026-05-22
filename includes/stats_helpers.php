<?php

declare(strict_types=1);

/**
 * Count rows in a whitelisted table (prevents SQL injection via table name).
 */
function count_table_rows(mysqli $conn, string $table): int
{
    $allowed = ['users', 'tests', 'questions', 'results'];

    if (!in_array($table, $allowed, true)) {
        return 0;
    }

    $result = $conn->query('SELECT COUNT(*) AS total FROM `' . $table . '`');

    if (!$result) {
        return 0;
    }

    $row = $result->fetch_assoc();

    return (int) ($row['total'] ?? 0);
}

/**
 * @return list<array{
 *   id: int,
 *   user_name: string,
 *   test_title: string,
 *   score: int,
 *   interpretation: string,
 *   created_at: string
 * }>
 */
function fetch_recent_results_admin(mysqli $conn, int $limit = 8): array
{
    $stmt = $conn->prepare(
        'SELECT r.id, u.name, t.title, r.score, r.interpretation, r.created_at
         FROM results r
         INNER JOIN users u ON u.id = r.user_id
         INNER JOIN tests t ON t.id = r.test_id
         ORDER BY r.created_at DESC
         LIMIT ?'
    );

    if (!$stmt) {
        return [];
    }

    $stmt->bind_param('i', $limit);
    $stmt->execute();

    $rows = [];
    $id = $name = $title = $score = $interpretation = $createdAt = null;
    $stmt->bind_result($id, $name, $title, $score, $interpretation, $createdAt);

    while ($stmt->fetch()) {
        $rows[] = [
            'id'             => (int) $id,
            'user_name'      => (string) $name,
            'test_title'     => (string) $title,
            'score'          => (int) $score,
            'interpretation' => (string) $interpretation,
            'created_at'     => (string) $createdAt,
        ];
    }

    $stmt->close();

    return $rows;
}

/**
 * All platform results for admin review (newest first).
 *
 * @return list<array{
 *   id: int,
 *   user_name: string,
 *   test_title: string,
 *   score: int,
 *   interpretation: string,
 *   created_at: string
 * }>
 */
function fetch_all_results_admin(mysqli $conn, int $limit = 100): array
{
    $stmt = $conn->prepare(
        'SELECT r.id, u.name, t.title, r.score, r.interpretation, r.created_at
         FROM results r
         INNER JOIN users u ON u.id = r.user_id
         INNER JOIN tests t ON t.id = r.test_id
         ORDER BY r.created_at DESC
         LIMIT ?'
    );

    if (!$stmt) {
        return [];
    }

    $stmt->bind_param('i', $limit);
    $stmt->execute();

    $rows = [];
    $id = $name = $title = $score = $interpretation = $createdAt = null;
    $stmt->bind_result($id, $name, $title, $score, $interpretation, $createdAt);

    while ($stmt->fetch()) {
        $rows[] = [
            'id'             => (int) $id,
            'user_name'      => (string) $name,
            'test_title'     => (string) $title,
            'score'          => (int) $score,
            'interpretation' => (string) $interpretation,
            'created_at'     => (string) $createdAt,
        ];
    }

    $stmt->close();

    return $rows;
}

/**
 * Results grouped by interpretation for chart display.
 *
 * @return array{labels: list<string>, values: list<int>}
 */
function fetch_results_by_interpretation(mysqli $conn): array
{
    $result = $conn->query(
        "SELECT interpretation, COUNT(*) AS total
         FROM results
         GROUP BY interpretation
         ORDER BY FIELD(interpretation, 'Low', 'Mild', 'Moderate', 'Medium', 'High')"
    );

    $labels = [];
    $values = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $labels[] = (string) $row['interpretation'];
            $values[] = (int) $row['total'];
        }
    }

    if ($labels === []) {
        return ['labels' => ['Low', 'Medium', 'High'], 'values' => [0, 0, 0]];
    }

    return ['labels' => $labels, 'values' => $values];
}

/**
 * Daily completion counts for the last N days.
 *
 * @return array{labels: list<string>, values: list<int>}
 */
function fetch_activity_last_days(mysqli $conn, int $days = 7): array
{
    $days = max(1, min($days, 30));
    $labels = [];
    $values = [];

    for ($i = $days - 1; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime('-' . $i . ' days'));
        $labels[] = date('M j', strtotime($date));
        $values[] = 0;
    }

    $stmt = $conn->prepare(
        'SELECT DATE(created_at) AS day, COUNT(*) AS total
         FROM results
         WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
         GROUP BY DATE(created_at)'
    );

    if (!$stmt) {
        return ['labels' => $labels, 'values' => $values];
    }

    $stmt->bind_param('i', $days);
    $stmt->execute();

    $day = $total = null;
    $stmt->bind_result($day, $total);

    $map = [];
    while ($stmt->fetch()) {
        $map[(string) $day] = (int) $total;
    }
    $stmt->close();

    for ($i = $days - 1; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime('-' . $i . ' days'));
        $values[$days - 1 - $i] = $map[$date] ?? 0;
    }

    return ['labels' => $labels, 'values' => $values];
}
