<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/category_helpers.php';
require_once __DIR__ . '/../includes/csrf.php';

$pageTitle = 'Manage Tests';
$activeNav = 'admin-tests';
$filterCategoryId = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT) ?: 0;

ensure_test_categories($conn);
$categories = fetch_all_categories($conn, false);

$tests = [];
$sql = 'SELECT t.id, t.title, t.description, t.category_id, t.created_at, c.name AS category_name
        FROM tests t
        LEFT JOIN test_categories c ON c.id = t.category_id';
$params = [];
$types = '';

if ($filterCategoryId > 0) {
    $sql .= ' WHERE t.category_id = ?';
    $types = 'i';
    $params[] = $filterCategoryId;
}

$sql .= ' ORDER BY c.sort_order ASC, t.title ASC';

$stmt = $conn->prepare($sql);

if ($stmt) {
    if ($types !== '') {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $tests = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

require_once __DIR__ . '/../includes/admin_layout_start.php';
?>

<div class="card admin-card shadow-sm mb-4">
    <div class="card-body p-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-8">
                <label for="category_id" class="form-label">Filter by category</label>
                <select name="category_id" id="category_id" class="form-select" onchange="this.form.submit()">
                    <option value="">All categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= (int) $cat['id'] ?>" <?= $filterCategoryId === (int) $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?> (<?= (int) $cat['test_count'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <a href="<?= htmlspecialchars(app_url('admin/categories.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-emotimate w-100">Manage Categories</a>
            </div>
        </form>
    </div>
</div>

<div class="card admin-card shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
            <div>
                <h2 class="h5 mb-1">Psychological Tests</h2>
                <p class="text-muted small mb-0">Add tests inside a category (Schema, Anxiety, etc.).</p>
            </div>
            <a href="<?= htmlspecialchars(app_url('admin/create_test.php' . ($filterCategoryId ? '?category_id=' . $filterCategoryId : '')), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-emotimate">
                + New Test
            </a>
        </div>

        <?php if (empty($tests)): ?>
            <?php
            require_once __DIR__ . '/../includes/empty_state.php';
            render_empty_state([
                'icon'          => 'bi-journal-x',
                'title'         => 'No tests in this category',
                'message'       => 'Create a test and assign it to the selected category.',
                'action_label'  => 'Create test',
                'action_url'    => app_url('admin/create_test.php' . ($filterCategoryId ? '?category_id=' . $filterCategoryId : '')),
            ]);
            ?>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle table-tests mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tests as $test): ?>
                            <tr>
                                <td><?= (int) $test['id'] ?></td>
                                <td><span class="badge badge-emotimate"><?= htmlspecialchars($test['category_name'] ?? '—', ENT_QUOTES, 'UTF-8') ?></span></td>
                                <td class="fw-semibold"><?= htmlspecialchars($test['title'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <span class="description-preview d-inline-block text-muted small">
                                        <?= htmlspecialchars($test['description'] ?? '—', ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    <?= htmlspecialchars(date('M j, Y', strtotime($test['created_at'])), ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td class="text-end">
                                    <a href="<?= htmlspecialchars(app_url('admin/questions.php?test_id=' . $test['id']), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-sm btn-outline-emotimate">Questions</a>
                                    <a href="<?= htmlspecialchars(app_url('admin/edit_test.php?id=' . $test['id']), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= (int) $test['id'] ?>">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php foreach ($tests as $test): ?>
    <div class="modal fade" id="deleteModal<?= (int) $test['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Test</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Delete <strong><?= htmlspecialchars($test['title'], ENT_QUOTES, 'UTF-8') ?></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="<?= htmlspecialchars(app_url('actions/delete_test_action.php'), ENT_QUOTES, 'UTF-8') ?>" method="POST" class="d-inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $test['id'] ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php require_once __DIR__ . '/../includes/admin_layout_end.php'; ?>
