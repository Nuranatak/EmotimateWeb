<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/category_helpers.php';
require_once __DIR__ . '/../includes/csrf.php';

$pageTitle = 'Create Test';
$categories = fetch_all_categories($conn, false);
$preselectCategory = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT) ?: 0;

require_once __DIR__ . '/../includes/admin_layout_start.php';
?>

<div class="card admin-card shadow-sm">
    <div class="card-body p-4">
        <h2 class="h5 mb-4">New Psychological Test</h2>

        <form action="<?= htmlspecialchars(app_url('actions/create_test_action.php'), ENT_QUOTES, 'UTF-8') ?>" method="POST" novalidate data-loading-form>
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="">— Select category —</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= (int) $cat['id'] ?>" <?= $preselectCategory === (int) $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Test Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" maxlength="255" required placeholder="e.g. Young Schema Questionnaire">
            </div>

            <div class="mb-4">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="5" maxlength="5000" placeholder="Brief description of this test..."></textarea>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <button type="submit" class="btn btn-emotimate" data-loading data-loading-text="Creating...">Create Test</button>
                <a href="<?= htmlspecialchars(app_url('admin/tests.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-emotimate">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_layout_end.php'; ?>
