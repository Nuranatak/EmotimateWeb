<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/category_helpers.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/flash.php';

$categoryId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$categoryId || $categoryId < 1) {
    set_flash('error', 'Invalid category.');
    header('Location: ' . app_url('admin/categories.php'), true, 302);
    exit;
}

$category = fetch_category_by_id($conn, $categoryId);

if ($category === null) {
    set_flash('error', 'Category not found.');
    header('Location: ' . app_url('admin/categories.php'), true, 302);
    exit;
}

$pageTitle = 'Edit Category';

require_once __DIR__ . '/../includes/admin_layout_start.php';
?>

<div class="card admin-card shadow-sm">
    <div class="card-body p-4">
        <h2 class="h5 mb-4">Edit Category</h2>

        <form action="<?= htmlspecialchars(app_url('actions/update_category_action.php'), ENT_QUOTES, 'UTF-8') ?>" method="POST" data-loading-form>
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int) $category['id'] ?>">

            <div class="mb-3">
                <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" maxlength="255" required value="<?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" maxlength="2000"><?= htmlspecialchars($category['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>

            <div class="mb-3">
                <label for="sort_order" class="form-label">Display Order</label>
                <input type="number" class="form-control" id="sort_order" name="sort_order" min="0" max="999" value="<?= (int) $category['sort_order'] ?>">
            </div>

            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?= $category['is_active'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_active">Visible to members</label>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <button type="submit" class="btn btn-emotimate" data-loading data-loading-text="Saving...">Save Changes</button>
                <a href="<?= htmlspecialchars(app_url('admin/categories.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-emotimate">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_layout_end.php'; ?>
