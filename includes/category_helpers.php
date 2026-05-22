<?php

declare(strict_types=1);

/**
 * Default test categories (Schema Tests first).
 */
function default_test_categories(): array
{
    return [
        ['name' => 'Schema Tests', 'description' => 'Assessments for early maladaptive schemas and related thinking patterns.', 'sort_order' => 1],
        ['name' => 'Depression and Anxiety Scales', 'description' => 'Scales measuring depression and anxiety symptoms.', 'sort_order' => 2],
        ['name' => 'Emotion and Mood Scales', 'description' => 'Scales for emotion regulation and mood evaluation.', 'sort_order' => 3],
        ['name' => 'Attention and Impulsivity Tests', 'description' => 'Scales for attention, hyperactivity, and impulsivity.', 'sort_order' => 4],
        ['name' => 'Post-Traumatic Stress Disorder', 'description' => 'Scales related to PTSD and post-trauma symptoms.', 'sort_order' => 5],
    ];
}

/**
 * Ensure categories table exists and default rows are seeded.
 */
function ensure_test_categories(mysqli $conn): void
{
    $conn->query(
        'CREATE TABLE IF NOT EXISTS test_categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            sort_order INT NOT NULL DEFAULT 0,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )'
    );

    $colCheck = $conn->query("SHOW COLUMNS FROM tests LIKE 'category_id'");

    if ($colCheck && $colCheck->num_rows === 0) {
        $conn->query('ALTER TABLE tests ADD COLUMN category_id INT NULL AFTER description');
    }

    $countResult = $conn->query('SELECT COUNT(*) AS c FROM test_categories');
    $count = 0;

    if ($countResult) {
        $row = $countResult->fetch_assoc();
        $count = (int) ($row['c'] ?? 0);
    }

    if ($count > 0) {
        sync_legacy_category_names_to_english($conn);
        return;
    }

    $stmt = $conn->prepare(
        'INSERT INTO test_categories (name, description, sort_order) VALUES (?, ?, ?)'
    );

    if (!$stmt) {
        return;
    }

    foreach (default_test_categories() as $cat) {
        $name = $cat['name'];
        $desc = $cat['description'];
        $order = $cat['sort_order'];
        $stmt->bind_param('ssi', $name, $desc, $order);
        $stmt->execute();
    }

    $stmt->close();
}

/**
 * @return list<array{id: int, name: string, description: string|null, sort_order: int, test_count: int}>
 */
function fetch_all_categories(mysqli $conn, bool $activeOnly = false): array
{
    ensure_test_categories($conn);

    $sql = 'SELECT c.id, c.name, c.description, c.sort_order, c.is_active,
                   COUNT(t.id) AS test_count
            FROM test_categories c
            LEFT JOIN tests t ON t.category_id = c.id
            ' . ($activeOnly ? 'WHERE c.is_active = 1 ' : '') .
            'GROUP BY c.id, c.name, c.description, c.sort_order, c.is_active
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
            'is_active'   => (int) $row['is_active'],
            'test_count'  => (int) $row['test_count'],
        ];
    }

    return $rows;
}

/**
 * @return array{id: int, name: string, description: string|null, sort_order: int, is_active: int}|null
 */
function fetch_category_by_id(mysqli $conn, int $categoryId): ?array
{
    ensure_test_categories($conn);

    $stmt = $conn->prepare(
        'SELECT id, name, description, sort_order, is_active FROM test_categories WHERE id = ? LIMIT 1'
    );

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param('i', $categoryId);
    $stmt->execute();

    $id = $name = $description = $sortOrder = $isActive = null;
    $stmt->bind_result($id, $name, $description, $sortOrder, $isActive);

    if (!$stmt->fetch()) {
        $stmt->close();
        return null;
    }

    $stmt->close();

    return [
        'id'          => (int) $id,
        'name'        => (string) $name,
        'description' => $description !== null ? (string) $description : null,
        'sort_order'  => (int) $sortOrder,
        'is_active'   => (int) $isActive,
    ];
}

function category_exists(mysqli $conn, int $categoryId): bool
{
    return fetch_category_by_id($conn, $categoryId) !== null;
}

/**
 * One-time rename of seeded Turkish category labels to English.
 */
function sync_legacy_category_names_to_english(mysqli $conn): void
{
    $legacyMap = [
        'Şema Testleri'                    => 0,
        'Depresyon ve Anksiyete Ölçekleri' => 1,
        'Duygu ve Ruh Hali Ölçekleri'      => 2,
        'Dikkat ve Dürtüsellik Testleri'   => 3,
        'Travma Sonrası Stres Bozukluğu'   => 4,
    ];

    $defaults = default_test_categories();
    $stmt = $conn->prepare(
        'UPDATE test_categories SET name = ?, description = ? WHERE name = ? LIMIT 1'
    );

    if (!$stmt) {
        return;
    }

    foreach ($legacyMap as $oldName => $index) {
        if (!isset($defaults[$index])) {
            continue;
        }

        $newName = $defaults[$index]['name'];
        $newDesc = $defaults[$index]['description'];
        $stmt->bind_param('sss', $newName, $newDesc, $oldName);
        $stmt->execute();
    }

    $stmt->close();
}
