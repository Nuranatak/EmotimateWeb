<?php

declare(strict_types=1);

/**
 * @return list<array{id: int, title: string, question_count: int}>
 */
function fetch_tests_with_question_counts(mysqli $conn): array
{
    $sql = 'SELECT t.id, t.title, COUNT(q.id) AS question_count
            FROM tests t
            LEFT JOIN questions q ON q.test_id = t.id
            GROUP BY t.id, t.title
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
            'question_count'  => (int) $row['question_count'],
        ];
    }

    return $tests;
}

function test_exists(mysqli $conn, int $testId): bool
{
    $stmt = $conn->prepare('SELECT id FROM tests WHERE id = ? LIMIT 1');

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param('i', $testId);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();

    return $exists;
}

/**
 * @return array{id: int, title: string}|null
 */
function fetch_test_by_id(mysqli $conn, int $testId): ?array
{
    $stmt = $conn->prepare('SELECT id, title FROM tests WHERE id = ? LIMIT 1');

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param('i', $testId);
    $stmt->execute();

    $id = $title = null;
    $stmt->bind_result($id, $title);

    if (!$stmt->fetch()) {
        $stmt->close();
        return null;
    }

    $stmt->close();

    return [
        'id'    => (int) $id,
        'title' => (string) $title,
    ];
}

/**
 * @return list<array{id: int, test_id: int, question_text: string, created_at: string}>
 */
function fetch_questions_for_test(mysqli $conn, int $testId): array
{
    $stmt = $conn->prepare(
        'SELECT id, test_id, question_text, created_at
         FROM questions
         WHERE test_id = ?
         ORDER BY id ASC'
    );

    if (!$stmt) {
        return [];
    }

    $stmt->bind_param('i', $testId);
    $stmt->execute();

    $questions = [];
    $id = $qTestId = $text = $createdAt = null;
    $stmt->bind_result($id, $qTestId, $text, $createdAt);

    while ($stmt->fetch()) {
        $questions[] = [
            'id'             => (int) $id,
            'test_id'        => (int) $qTestId,
            'question_text'  => (string) $text,
            'created_at'     => (string) $createdAt,
        ];
    }

    $stmt->close();

    return $questions;
}

/**
 * @return array{id: int, test_id: int, question_text: string}|null
 */
function fetch_question_by_id(mysqli $conn, int $questionId): ?array
{
    $stmt = $conn->prepare(
        'SELECT id, test_id, question_text FROM questions WHERE id = ? LIMIT 1'
    );

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param('i', $questionId);
    $stmt->execute();

    $id = $testId = $text = null;
    $stmt->bind_result($id, $testId, $text);

    if (!$stmt->fetch()) {
        $stmt->close();
        return null;
    }

    $stmt->close();

    return [
        'id'            => (int) $id,
        'test_id'       => (int) $testId,
        'question_text' => (string) $text,
    ];
}
