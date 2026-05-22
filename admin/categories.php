<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/category_helpers.php';
require_once __DIR__ . '/../includes/csrf.php';

$pageTitle = 'Test Categories';
$activeNav = 'admin-categories';
$categories = fetch_all_categories($conn);

require_once __DIR__ . '/../includes/admin_layout_start.php';
?>

<div class="card admin-card shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
            <div>
                <h2 class="h5 mb-1"><i class="bi bi-folder2-open me-2"></i>Test Categories</h2>
                <p class="text-muted small mb-0">Members choose a category first, then see tests inside it.</p>
            </div>
            <a href="<?= htmlspecialchars(app_url('admin/create_category.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-emotimate">
                + New Category
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-emotimate">
                    <tr>
                        <th>Order</th>
                        <th>Category</th>
                        <th>Tests</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td class="text-muted"><?= (int) $cat['sort_order'] ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><span class="badge badge-emotimate"><?= (int) $cat['test_count'] ?></span></td>
                            <td>
                                <?php if ($cat['is_active']): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Hidden</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="<?= htmlspecialchars(app_url('admin/tests.php?category_id=' . $cat['id']), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-sm btn-outline-emotimate">Tests</a>
                                <a href="<?= htmlspecialchars(app_url('admin/edit_category.php?id=' . $cat['id']), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_layout_end.php'; ?>
