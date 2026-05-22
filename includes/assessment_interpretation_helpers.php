<?php

declare(strict_types=1);

require_once __DIR__ . '/assessment_interpretation_texts.php';
require_once __DIR__ . '/schema_interpretation_helpers.php';
require_once __DIR__ . '/test_helpers.php';

/**
 * Detect standardized assessment type from test title and category.
 */
function assessment_slug_from_test(string $title, ?string $categoryName = null): ?string
{
    $lower = strtolower($title);
    $cat = strtolower($categoryName ?? '');

    if (str_contains($cat, 'trauma') || str_contains($cat, 'ptsd') || str_contains($cat, 'post-traumatic')) {
        if (str_contains($lower, 'hypervigilance') || str_contains($lower, 'emotional safety')) {
            return 'hypervigilance';
        }

        if (str_contains($lower, 'intrusive') || str_contains($lower, 're-experiencing')) {
            return 'trauma_intrusive';
        }
    }

    if (str_contains($cat, 'depression') || str_contains($cat, 'anxiety')) {
        if (str_contains($lower, 'gad') || (str_contains($lower, 'anxiety') && !str_contains($lower, 'depression'))) {
            return 'gad7';
        }

        if (str_contains($lower, 'phq') || str_contains($lower, 'depression')) {
            return 'phq9';
        }
    }

    if (str_contains($cat, 'emotion') || str_contains($cat, 'mood')) {
        if (str_contains($lower, 'mood stability') || str_contains($lower, 'emotional balance')) {
            return 'mood_stability';
        }

        if (str_contains($lower, 'emotional awareness') || str_contains($lower, 'emotional regulation')) {
            return 'emotion_regulation';
        }
    }

    if (str_contains($cat, 'attention') || str_contains($cat, 'impulsivity')) {
        if (str_contains($lower, 'impulsivity') || str_contains($lower, 'impulse')) {
            return 'impulsivity_scale';
        }

        if (str_contains($lower, 'attention') || str_contains($lower, 'focus')) {
            return 'attention_focus';
        }
    }

    if (str_contains($lower, 'phq-9') || str_contains($lower, 'phq9') || str_contains($lower, 'depression test')
        || (str_contains($lower, 'depression') && !str_contains($lower, 'anxiety'))) {
        return 'phq9';
    }

    if (str_contains($lower, 'gad-7') || str_contains($lower, 'gad7') || str_contains($lower, 'anxiety test')) {
        return 'gad7';
    }

    if (str_contains($lower, 'mood stability') || str_contains($lower, 'emotional balance scale')) {
        return 'mood_stability';
    }

    if (str_contains($lower, 'emotional awareness') || str_contains($lower, 'regulation scale')) {
        return 'emotion_regulation';
    }

    if (str_contains($lower, 'attention') && str_contains($lower, 'focus')) {
        return 'attention_focus';
    }

    if (str_contains($lower, 'impulsivity') && str_contains($lower, 'self-control scale')) {
        return 'impulsivity_scale';
    }

    if (str_contains($lower, 'trauma re-experiencing') || str_contains($lower, 'intrusive thoughts')) {
        return 'trauma_intrusive';
    }

    if (str_contains($lower, 'hypervigilance')) {
        return 'hypervigilance';
    }

    return null;
}

/**
 * @return list<string>
 */
function assessment_scale8_slugs(): array
{
    return [
        'emotion_regulation',
        'mood_stability',
        'attention_focus',
        'impulsivity_scale',
        'trauma_intrusive',
        'hypervigilance',
    ];
}

/**
 * @return string|null Tier key stored in results.interpretation
 */
function calculate_assessment_interpretation(string $slug, int $score, int $questionCount = 8): ?string
{
    if ($slug === 'phq9') {
        if ($score <= 4) {
            return 'Minimal';
        }

        if ($score <= 9) {
            return 'Mild';
        }

        if ($score <= 14) {
            return 'Moderate';
        }

        if ($score <= 19) {
            return 'ModeratelySevere';
        }

        return 'Severe';
    }

    if ($slug === 'gad7') {
        if ($score <= 4) {
            return 'Minimal';
        }

        if ($score <= 9) {
            return 'Mild';
        }

        if ($score <= 14) {
            return 'Moderate';
        }

        return 'Severe';
    }

    if (in_array($slug, assessment_scale8_slugs(), true)) {
        return calculate_schema_interpretation($score, $questionCount);
    }

    return null;
}

/**
 * @return array{label: string, range: string, summary: string}|null
 */
function assessment_interpretation_for_test(string $testTitle, string $tier, ?string $categoryName = null): ?array
{
    $slug = assessment_slug_from_test($testTitle, $categoryName);

    if ($slug === null) {
        return null;
    }

    $texts = assessment_interpretation_texts();

    if (!isset($texts[$slug][$tier])) {
        return null;
    }

    return $texts[$slug][$tier];
}

/**
 * Resolve interpretation tier for a test submission.
 */
function resolve_test_interpretation(
    mysqli $conn,
    int $testId,
    string $testTitle,
    int $score,
    int $questionCount
): string {
    require_once __DIR__ . '/category_helpers.php';

    if (is_schema_test($conn, $testId)) {
        return calculate_schema_interpretation($score, $questionCount);
    }

    $categoryName = null;
    $test = fetch_test_details($conn, $testId);

    if ($test !== null && $test['category_id'] !== null) {
        $category = fetch_category_by_id($conn, $test['category_id']);
        $categoryName = $category['name'] ?? null;
    }

    $slug = assessment_slug_from_test($testTitle, $categoryName);

    if ($slug !== null) {
        $tier = calculate_assessment_interpretation($slug, $score, $questionCount);

        if ($tier !== null) {
            return $tier;
        }
    }

    return calculate_interpretation($score);
}
