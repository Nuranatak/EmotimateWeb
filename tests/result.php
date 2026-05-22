<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/../includes/test_helpers.php';
require_once __DIR__ . '/../includes/category_helpers.php';
require_once __DIR__ . '/../includes/user_helpers.php';
require_once __DIR__ . '/../includes/schema_interpretation_helpers.php';
require_once __DIR__ . '/../includes/assessment_interpretation_helpers.php';

$resultId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$userId = (int) $_SESSION['user_id'];

if ($resultId < 1) {
    set_flash('error', 'Invalid result.');
    header('Location: ' . app_url('tests/index.php'), true, 302);
    exit;
}

$result = fetch_result_for_user($conn, $resultId, $userId);

if ($result === null) {
    set_flash('error', 'Result not found.');
    header('Location: ' . app_url('tests/index.php'), true, 302);
    exit;
}

$testId = (int) $result['test_id'];
$tier = $result['interpretation'];
$interpretationClass = interpretation_badge_class($tier);

$categoryName = null;
$test = fetch_test_details($conn, $testId);

if ($test !== null && $test['category_id'] !== null) {
    $category = fetch_category_by_id($conn, $test['category_id']);
    $categoryName = $category['name'] ?? null;
}

$isSchemaTest = is_schema_test($conn, $testId);
$resultDetails = null;

if ($isSchemaTest) {
    $resultDetails = schema_interpretation_for_test($result['test_title'], $tier);
} else {
    $resultDetails = assessment_interpretation_for_test($result['test_title'], $tier, $categoryName);
}

$pageTitle = 'Your Result';
$extraStylesheets = [asset_url('css/tests.css')];

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="container py-4">
    <?php render_flash(); ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-md-5 text-center">
            <p class="text-muted text-uppercase small mb-2">Test completed</p>
            <h1 class="h4 mb-1"><?= htmlspecialchars($result['test_title'], ENT_QUOTES, 'UTF-8') ?></h1>
            <p class="text-muted small mb-4">
                <?= htmlspecialchars(date('F j, Y \a\t g:i A', strtotime($result['created_at'])), ENT_QUOTES, 'UTF-8') ?>
            </p>

            <p class="result-score mb-2"><?= (int) $result['score'] ?></p>
            <p class="text-muted mb-3">Total score</p>

            <span class="badge interpretation-badge <?= htmlspecialchars($interpretationClass, ENT_QUOTES, 'UTF-8') ?>">
                <?php if ($resultDetails !== null && $resultDetails['range'] !== ''): ?>
                    <?= htmlspecialchars($resultDetails['range'], ENT_QUOTES, 'UTF-8') ?> → <?= htmlspecialchars($resultDetails['label'], ENT_QUOTES, 'UTF-8') ?>
                <?php elseif ($resultDetails !== null): ?>
                    <?= htmlspecialchars($resultDetails['label'], ENT_QUOTES, 'UTF-8') ?>
                <?php else: ?>
                    <?= htmlspecialchars($tier, ENT_QUOTES, 'UTF-8') ?> level
                <?php endif; ?>
            </span>

            <?php if ($resultDetails !== null): ?>
                <div class="alert alert-light border mt-4 mb-0 text-start schema-result-narrative">
                    <p class="mb-2 fw-semibold">Your interpretation</p>
                    <p class="mb-0"><?= nl2br(htmlspecialchars($resultDetails['summary'], ENT_QUOTES, 'UTF-8')) ?></p>
                </div>
            <?php else: ?>
                <div class="alert alert-light border mt-4 mb-0 text-start">
                    <p class="mb-2 fw-semibold">What does this mean?</p>
                    <ul class="mb-0 small text-muted">
                        <li><strong>Low (0–10):</strong> Lower indication on this scale.</li>
                        <li><strong>Medium (11–20):</strong> Moderate indication on this scale.</li>
                        <li><strong>High (21+):</strong> Higher indication on this scale.</li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2 justify-content-center mt-4">
        <a href="<?= htmlspecialchars(app_url('tests/index.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-emotimate">
            Browse More Tests
        </a>
        <a href="<?= htmlspecialchars(app_url('user/dashboard.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-emotimate">
            Back to Dashboard
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
