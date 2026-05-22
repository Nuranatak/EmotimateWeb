<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/question_helpers.php';
require_once __DIR__ . '/../includes/admin_breadcrumb.php';

$pageTitle = 'Manage Questions';
$tests = fetch_tests_with_question_counts($conn);
$selectedTestId = filter_input(INPUT_GET, 'test_id', FILTER_VALIDATE_INT) ?: 0;
$selectedTest = null;
$questions = [];

if ($selectedTestId > 0) {
    $selectedTest = fetch_test_by_id($conn, $selectedTestId);

    if ($selectedTest === null) {
        require_once __DIR__ . '/../includes/flash.php';
        set_flash('error', 'Test not found.');
        header('Location: ' . app_url('admin/questions.php'), true, 302);
        exit;
    }

    $questions = fetch_questions_for_test($conn, $selectedTestId);
}

require_once __DIR__ . '/../includes/admin_layout_start.php';
?>

<?php
render_admin_breadcrumb([
    ['label' => 'Admin', 'url' => app_url('admin/tests.php')],
    ['label' => 'Tests', 'url' => app_url('admin/tests.php')],
    ['label' => 'Questions'],
]);
?>

<div class="card admin-card shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
            <div>
                <h2 class="h5 mb-1">Question Management</h2>
                <p class="text-muted small mb-0">Select a test to view and manage its questions.</p>
            </div>
            <?php if ($selectedTest !== null): ?>
                <a
                    href="<?= htmlspecialchars(app_url('admin/create_question.php?test_id=' . $selectedTestId), ENT_QUOTES, 'UTF-8') ?>"
                    class="btn btn-emotimate">
                    + Add Question
                </a>
            <?php endif; ?>
        </div>

        <form method="GET" action="<?= htmlspecialchars(app_url('admin/questions.php'), ENT_QUOTES, 'UTF-8') ?>" class="row g-3 align-items-end">
            <div class="col-md-8">
                <label for="test_id" class="form-label">Psychological Test</label>
                <select name="test_id" id="test_id" class="form-select" required onchange="this.form.submit()">
                    <option value="">— Select a test —</option>
                    <?php foreach ($tests as $test): ?>
                        <option
                            value="<?= (int) $test['id'] ?>"
                            <?= $selectedTestId === (int) $test['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($test['title'], ENT_QUOTES, 'UTF-8') ?>
                            (<?= (int) $test['question_count'] ?> questions)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-outline-emotimate w-100">View Questions</button>
            </div>
        </form>
    </div>
</div>

<?php if ($selectedTest === null): ?>
    <?php
    require_once __DIR__ . '/../includes/empty_state.php';
    render_empty_state([
        'icon'    => 'bi-funnel',
        'title'   => 'Select a test',
        'message' => 'Choose a test from the dropdown above to view and manage its questions.',
    ]);
    ?>
<?php else: ?>
    <div class="card admin-card shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                <div>
                    <h3 class="h6 mb-1"><?= htmlspecialchars($selectedTest['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <span class="badge badge-emotimate">
                        <?= count($questions) ?> question<?= count($questions) === 1 ? '' : 's' ?>
                    </span>
                </div>
            </div>

            <?php if ($questions === []): ?>
                <?php
                render_empty_state([
                    'icon'          => 'bi-question-circle',
                    'title'         => 'No questions yet',
                    'message'       => 'Add questions to this test so users can complete the assessment.',
                    'action_label'  => 'Add first question',
                    'action_url'    => app_url('admin/create_question.php?test_id=' . $selectedTestId),
                ]);
                ?>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-emotimate">
                            <tr>
                                <th scope="col" style="width: 80px;">Order</th>
                                <th scope="col">Question</th>
                                <th scope="col">Created</th>
                                <th scope="col" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($questions as $index => $question): ?>
                                <tr>
                                    <td class="text-muted fw-semibold">#<?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($question['question_text'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-muted small">
                                        <?= htmlspecialchars(date('M j, Y', strtotime($question['created_at'])), ENT_QUOTES, 'UTF-8') ?>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <a
                                            href="<?= htmlspecialchars(app_url('admin/edit_question.php?id=' . $question['id']), ENT_QUOTES, 'UTF-8') ?>"
                                            class="btn btn-sm btn-outline-emotimate">
                                            Edit
                                        </a>
                                        <form
                                            method="POST"
                                            action="<?= htmlspecialchars(app_url('actions/delete_question_action.php'), ENT_QUOTES, 'UTF-8') ?>"
                                            class="d-inline"
                                            onsubmit="return confirm('Delete this question?');">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="question_id" value="<?= (int) $question['id'] ?>">
                                            <input type="hidden" name="test_id" value="<?= (int) $selectedTestId ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/admin_layout_end.php'; ?>
