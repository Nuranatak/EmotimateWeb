<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_check.php';
require_once __DIR__ . '/../includes/csrf.php';

$pageTitle = 'Create Category';

require_once __DIR__ . '/../includes/admin_layout_start.php';
?>

<div class="card admin-card shadow-sm">
    <div class="card-body p-4">
        <h2 class="h5 mb-4">New Test Category</h2>

        <form action="<?= htmlspecialchars(app_url('actions/create_category_action.php'), ENT_QUOTES, 'UTF-8') ?>" method="POST" data-loading-form>
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" maxlength="255" required placeholder="e.g. Schema Tests">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" maxlength="2000"></textarea>
            </div>

            <div class="mb-4">
                <label for="sort_order" class="form-label">Display Order</label>
                <input type="number" class="form-control" id="sort_order" name="sort_order" min="0" max="999" value="0">
                <div class="form-text">Lower numbers appear first. Schema Tests should be 1.</div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <button type="submit" class="btn btn-emotimate" data-loading data-loading-text="Saving...">Create Category</button>
                <a href="<?= htmlspecialchars(app_url('admin/categories.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-emotimate">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_layout_end.php'; ?>
