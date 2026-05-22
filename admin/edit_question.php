<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/../includes/question_helpers.php';
require_once __DIR__ . '/../includes/admin_breadcrumb.php';

$questionId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$questionId || $questionId < 1) {
    set_flash('error', 'Invalid question selected.');
    header('Location: ' . app_url('admin/questions.php'), true, 302);
    exit;
}

$question = fetch_question_by_id($conn, $questionId);

if ($question === null) {
    set_flash('error', 'Question not found.');
    header('Location: ' . app_url('admin/questions.php'), true, 302);
    exit;
}

$tests = fetch_tests_with_question_counts($conn);
$pageTitle = 'Edit Question';

require_once __DIR__ . '/../includes/admin_layout_start.php';
?>

<?php
render_admin_breadcrumb([
    ['label' => 'Admin', 'url' => app_url('admin/tests.php')],
    ['label' => 'Questions', 'url' => app_url('admin/questions.php?test_id=' . $question['test_id'])],
    ['label' => 'Edit Question'],
]);
?>

<div class="card admin-card shadow-sm">
    <div class="card-body p-4">
        <h2 class="h5 mb-4">Edit Question #<?= (int) $question['id'] ?></h2>

        <form action="<?= htmlspecialchars(app_url('actions/update_question_action.php'), ENT_QUOTES, 'UTF-8') ?>" method="POST" novalidate>
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int) $question['id'] ?>">

            <div class="mb-3">
                <label for="test_id" class="form-label">Test <span class="text-danger">*</span></label>
                <select name="test_id" id="test_id" class="form-select" required>
                    <?php foreach ($tests as $test): ?>
                        <option
                            value="<?= (int) $test['id'] ?>"
                            <?= (int) $question['test_id'] === (int) $test['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($test['title'], ENT_QUOTES, 'UTF-8') ?>
                            (<?= (int) $test['question_count'] ?> questions)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="question_text" class="form-label">Question Text <span class="text-danger">*</span></label>
                <textarea
                    class="form-control"
                    id="question_text"
                    name="question_text"
                    rows="4"
                    maxlength="5000"
                    required><?= htmlspecialchars($question['question_text'], ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <button type="submit" class="btn btn-dark">Save Changes</button>
                <a
                    href="<?= htmlspecialchars(app_url('admin/questions.php?test_id=' . $question['test_id']), ENT_QUOTES, 'UTF-8') ?>"
                    class="btn btn-outline-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_layout_end.php'; ?>
