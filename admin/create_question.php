<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/question_helpers.php';
require_once __DIR__ . '/../includes/admin_breadcrumb.php';

$pageTitle = 'Add Question';
$tests = fetch_tests_with_question_counts($conn);
$preselectedTestId = filter_input(INPUT_GET, 'test_id', FILTER_VALIDATE_INT) ?: 0;

require_once __DIR__ . '/../includes/admin_layout_start.php';
?>

<?php
render_admin_breadcrumb([
    ['label' => 'Admin', 'url' => app_url('admin/tests.php')],
    ['label' => 'Questions', 'url' => app_url('admin/questions.php')],
    ['label' => 'Add Question'],
]);
?>

<div class="card admin-card shadow-sm">
    <div class="card-body p-4">
        <h2 class="h5 mb-4">New Question</h2>

        <form action="<?= htmlspecialchars(app_url('actions/create_question_action.php'), ENT_QUOTES, 'UTF-8') ?>" method="POST" novalidate>
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="test_id" class="form-label">Test <span class="text-danger">*</span></label>
                <select name="test_id" id="test_id" class="form-select" required>
                    <option value="">— Select a test —</option>
                    <?php foreach ($tests as $test): ?>
                        <option
                            value="<?= (int) $test['id'] ?>"
                            <?= $preselectedTestId === (int) $test['id'] ? 'selected' : '' ?>>
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
                    required
                    placeholder="Enter the psychological test question..."></textarea>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <button type="submit" class="btn btn-dark">Create Question</button>
                <a
                    href="<?= htmlspecialchars(app_url('admin/questions.php' . ($preselectedTestId ? '?test_id=' . $preselectedTestId : '')), ENT_QUOTES, 'UTF-8') ?>"
                    class="btn btn-outline-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_layout_end.php'; ?>
