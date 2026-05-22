<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/category_helpers.php';
require_once __DIR__ . '/../includes/test_helpers.php';
require_once __DIR__ . '/../includes/flash.php';

ensure_test_categories($conn);

$categoryId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$category = fetch_category_by_id($conn, $categoryId);

if ($category === null || !$category['is_active']) {
    set_flash('error', 'Category not found.');
    header('Location: ' . app_url('tests/index.php'), true, 302);
    exit;
}

$pageTitle = $category['name'];
$extraStylesheets = [asset_url('css/tests.css')];
$tests = fetch_available_tests($conn, $categoryId);

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="container py-4">
    <?php render_flash(); ?>

    <div class="mb-3">
        <a href="<?= htmlspecialchars(app_url('tests/index.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-emotimate btn-sm">
            <i class="bi bi-arrow-left me-1"></i> All Categories
        </a>
    </div>

    <div class="category-header-card mb-4">
        <h1 class="h4 fw-bold mb-2 text-uppercase"><?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?></h1>
        <?php if ($category['description']): ?>
            <p class="mb-0 text-white-50 small"><?= htmlspecialchars($category['description'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
    </div>

    <?php if ($tests === []): ?>
        <?php
        require_once __DIR__ . '/../includes/empty_state.php';
        render_empty_state([
            'icon'    => 'bi-journal-x',
            'title'   => 'No tests in this category yet',
            'message' => 'The administrator has not added tests here yet. Please check back later.',
        ]);
        ?>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($tests as $test): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0 card-hover">
                        <div class="card-body d-flex flex-column p-4">
                            <h2 class="h5 mb-2"><?= htmlspecialchars($test['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                            <p class="text-muted small flex-grow-1">
                                <?= htmlspecialchars($test['description'] ?? 'No description provided.', ENT_QUOTES, 'UTF-8') ?>
                            </p>
                            <p class="small mb-3">
                                <span class="badge badge-emotimate">
                                    <?= (int) $test['question_count'] ?> question<?= (int) $test['question_count'] === 1 ? '' : 's' ?>
                                </span>
                            </p>
                            <?php if ((int) $test['question_count'] > 0): ?>
                                <a href="<?= htmlspecialchars(app_url('tests/take_test.php?test_id=' . $test['id']), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-emotimate w-100">
                                    Start Test
                                </a>
                            <?php else: ?>
                                <button type="button" class="btn btn-outline-secondary w-100" disabled>No questions yet</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
