<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/category_helpers.php';
require_once __DIR__ . '/../includes/flash.php';

$pageTitle = 'Test Categories';
$extraStylesheets = [asset_url('css/tests.css')];
ensure_test_categories($conn);
$categories = fetch_all_categories($conn, true);

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="container py-4 py-lg-5">
    <?php render_flash(); ?>

    <div class="test-hero p-4 p-md-5 mb-4 shadow-sm text-center">
        <h1 class="h3 fw-bold mb-2">Psychological Tests</h1>
        <p class="mb-0 text-white-50">Select a category to view available assessments.</p>
    </div>

    <?php if ($categories === []): ?>
        <?php
        require_once __DIR__ . '/../includes/empty_state.php';
        render_empty_state([
            'icon'    => 'bi-folder-x',
            'title'   => 'No categories yet',
            'message' => 'Test categories will appear here when the administrator adds them.',
        ]);
        ?>
    <?php else: ?>
        <div class="category-list">
            <?php foreach ($categories as $cat): ?>
                <a
                    href="<?= htmlspecialchars(app_url('tests/category.php?id=' . $cat['id']), ENT_QUOTES, 'UTF-8') ?>"
                    class="category-btn">
                    <span class="category-btn-text"><?= htmlspecialchars(mb_strtoupper($cat['name'], 'UTF-8'), ENT_QUOTES, 'UTF-8') ?></span>
                    <?php if ((int) $cat['test_count'] > 0): ?>
                        <span class="category-btn-meta"><?= (int) $cat['test_count'] ?> test</span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
