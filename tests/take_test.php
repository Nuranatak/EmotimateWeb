<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/../includes/test_helpers.php';
require_once __DIR__ . '/../includes/question_helpers.php';

$testId = filter_input(INPUT_GET, 'test_id', FILTER_VALIDATE_INT) ?: 0;

if ($testId < 1) {
    set_flash('error', 'Please select a valid test.');
    header('Location: ' . app_url('tests/index.php'), true, 302);
    exit;
}

$test = fetch_test_details($conn, $testId);

if ($test === null) {
    set_flash('error', 'Test not found.');
    header('Location: ' . app_url('tests/index.php'), true, 302);
    exit;
}

$questions = fetch_questions_for_test($conn, $testId);

if ($questions === []) {
    set_flash('error', 'This test has no questions yet.');
    header('Location: ' . app_url('tests/index.php'), true, 302);
    exit;
}

$pageTitle = 'Take Test: ' . $test['title'];
$extraStylesheets = [asset_url('css/tests.css')];
$likertLabels = likert_labels();
$totalQuestions = count($questions);

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="container py-4">
    <?php render_flash(); ?>

    <div class="test-hero p-4 mb-4 shadow-sm">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="<?= htmlspecialchars(app_url('tests/index.php'), ENT_QUOTES, 'UTF-8') ?>" class="text-white-50">Tests</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page"><?= htmlspecialchars($test['title'], ENT_QUOTES, 'UTF-8') ?></li>
            </ol>
        </nav>
        <h1 class="h4 fw-bold mb-1"><?= htmlspecialchars($test['title'], ENT_QUOTES, 'UTF-8') ?></h1>
        <?php if (!empty($test['description'])): ?>
            <p class="mb-2 text-white-50 small"><?= htmlspecialchars($test['description'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <span class="badge bg-light text-dark"><?= $totalQuestions ?> questions · Likert scale 1–5</span>
    </div>

    <?php if (!empty($test['instructions'])): ?>
        <div class="card shadow-sm mb-4 test-instructions-card">
            <div class="card-body p-4">
                <h2 class="h6 fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Before you begin</h2>
                <div class="test-instructions small text-muted mb-0">
                    <?= nl2br(htmlspecialchars($test['instructions'], ENT_QUOTES, 'UTF-8')) ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <form
        action="<?= htmlspecialchars(app_url('actions/submit_test_action.php'), ENT_QUOTES, 'UTF-8') ?>"
        method="POST"
        id="testForm"
        data-loading-form
        novalidate>
        <?= csrf_field() ?>
        <input type="hidden" name="test_id" value="<?= (int) $testId ?>">

        <?php foreach ($questions as $index => $question): ?>
            <div class="card question-card shadow-sm">
                <div class="card-body p-4">
                    <p class="fw-semibold mb-3">
                        <span class="text-muted me-2">Q<?= $index + 1 ?>.</span>
                        <?= htmlspecialchars($question['question_text'], ENT_QUOTES, 'UTF-8') ?>
                        <span class="text-danger">*</span>
                    </p>
                    <div class="likert-group" role="radiogroup" aria-label="Answer for question <?= $index + 1 ?>">
                        <?php foreach ($likertLabels as $value => $label): ?>
                            <div class="likert-option form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="answers[<?= (int) $question['id'] ?>]"
                                    id="q<?= (int) $question['id'] ?>_<?= $value ?>"
                                    value="<?= $value ?>"
                                    required>
                                <label class="form-check-label" for="q<?= (int) $question['id'] ?>_<?= $value ?>">
                                    <strong><?= $value ?></strong><br>
                                    <span class="small"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="d-flex flex-wrap gap-2 justify-content-between mt-4">
            <a href="<?= htmlspecialchars(app_url('tests/index.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-emotimate">
                Cancel
            </a>
            <button type="submit" class="btn btn-emotimate btn-lg" data-loading data-loading-text="Submitting...">
                <i class="bi bi-send me-1"></i> Submit Test
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('testForm').addEventListener('submit', function (e) {
    const groups = this.querySelectorAll('.likert-group');
    let valid = true;
    groups.forEach(function (group) {
        if (!group.querySelector('input[type="radio"]:checked')) {
            valid = false;
        }
    });
    if (!valid) {
        e.preventDefault();
        alert('Please answer every question before submitting.');
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
