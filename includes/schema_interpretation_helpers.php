<?php

declare(strict_types=1);

/**
 * Schema test scoring and result text (shown on the result page, not separate tests).
 * Reference scale: 8 items × Likert 1–5 → 8–40; shorter tests are normalized to this range.
 */
function normalize_schema_score(int $score, int $questionCount): int
{
    if ($questionCount < 1) {
        return $score;
    }

    $min = $questionCount;
    $max = $questionCount * 5;

    if ($max <= $min) {
        return $score;
    }

    return (int) round(8 + (($score - $min) / ($max - $min)) * 32);
}

/**
 * @return 'Low'|'Mild'|'Moderate'|'High'
 */
function calculate_schema_interpretation(int $score, int $questionCount = 8): string
{
    $normalized = $questionCount === 8 ? $score : normalize_schema_score($score, $questionCount);

    if ($normalized <= 16) {
        return 'Low';
    }

    if ($normalized <= 24) {
        return 'Mild';
    }

    if ($normalized <= 32) {
        return 'Moderate';
    }

    return 'High';
}

/**
 * Generic fallback when no schema-specific narrative exists yet.
 *
 * @return array{label: string, range: string, summary: string}
 */
function schema_interpretation_details(string $tier): array
{
    $bands = schema_score_band_reference();

    foreach ($bands as $band) {
        if ($band['tier'] === $tier) {
            return [
                'label'   => $band['label'],
                'range'   => (string) $band['min'] . '–' . (string) $band['max'],
                'summary' => 'Your responses suggest this schema pattern falls in the ' . strtolower($band['label']) . ' range on the 8–40 scale.',
            ];
        }
    }

    return [
        'label'   => $tier,
        'range'   => '',
        'summary' => 'Your score reflects how strongly this pattern appears in your responses.',
    ];
}

/**
 * Full interpretation for a schema test result (title + tier).
 *
 * @return array{label: string, range: string, summary: string}
 */
function schema_interpretation_for_test(string $testTitle, string $tier): array
{
    require_once __DIR__ . '/schema_interpretation_texts.php';

    $slug = schema_slug_from_test_title($testTitle);
    $texts = schema_interpretation_texts();

    if ($slug !== null && isset($texts[$slug][$tier])) {
        return $texts[$slug][$tier];
    }

    return schema_interpretation_details($tier);
}

/**
 * @return list<array{min: int, max: int, tier: string, label: string}>
 */
function schema_score_band_reference(): array
{
    return [
        ['min' => 8,  'max' => 16, 'tier' => 'Low',      'label' => 'Low Schema Activation'],
        ['min' => 17, 'max' => 24, 'tier' => 'Mild',     'label' => 'Mild Schema Activation'],
        ['min' => 25, 'max' => 32, 'tier' => 'Moderate', 'label' => 'Moderate Schema Activation'],
        ['min' => 33, 'max' => 40, 'tier' => 'High',     'label' => 'High Schema Activation'],
    ];
}

function schema_slug_from_test_title(string $title): ?string
{
    $map = [
        'abandonment'          => ['abandonment', 'instability'],
        'mistrust'             => ['mistrust', 'abuse'],
        'emotional_deprivation' => ['emotional deprivation'],
        'defectiveness'        => ['defectiveness', 'shame'],
        'social_isolation'     => ['social isolation', 'alienation'],
        'dependence'           => ['dependence', 'incompetence'],
        'vulnerability'         => ['vulnerability', 'harm or illness', 'harm/illness'],
        'enmeshment'           => ['enmeshment', 'undeveloped self'],
        'failure'              => ['failure schema', 'failure'],
        'entitlement'          => ['entitlement', 'grandiosity'],
        'self_control'          => ['insufficient self-control', 'self-control', 'self-discipline'],
        'subjugation'           => ['subjugation'],
        'self_sacrifice'        => ['self-sacrifice', 'self sacrifice'],
        'approval_seeking'      => ['approval-seeking', 'approval seeking', 'recognition-seeking', 'recognition seeking'],
        'negativity'            => ['negativity', 'pessimism'],
        'emotional_inhibition'  => ['emotional inhibition'],
        'unrelenting_standards' => ['unrelenting standards', 'hypercriticalness', 'hypercritical'],
        'punitiveness'          => ['punitiveness'],
    ];

    $lower = strtolower($title);

    foreach ($map as $slug => $needles) {
        foreach ($needles as $needle) {
            if (str_contains($lower, $needle)) {
                return $slug;
            }
        }
    }

    return null;
}

function is_schema_test(mysqli $conn, int $testId): bool
{
    require_once __DIR__ . '/category_helpers.php';

    $test = fetch_test_details($conn, $testId);

    if ($test === null) {
        return false;
    }

    if ($test['category_id'] === null) {
        return schema_slug_from_test_title($test['title']) !== null;
    }

    $category = fetch_category_by_id($conn, $test['category_id']);

    if ($category === null) {
        return false;
    }

    return in_array($category['name'], ['Schema Tests', 'Şema Testleri'], true);
}
