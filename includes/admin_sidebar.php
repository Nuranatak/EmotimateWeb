<?php

$currentPage = basename($_SERVER['PHP_SELF'] ?? '');
?>

<aside id="adminSidebar" class="col-lg-3 col-xl-2 mb-4 collapse admin-sidebar-collapse">
    <div class="card admin-sidebar shadow-sm border-0">
        <div class="card-body p-4">
            <p class="text-uppercase text-muted small fw-semibold mb-3">Admin Panel</p>
            <nav class="nav flex-column gap-1">
                <a
                    href="<?= htmlspecialchars(app_url('admin/dashboard.php'), ENT_QUOTES, 'UTF-8') ?>"
                    class="nav-link admin-nav-link<?= $currentPage === 'dashboard.php' ? ' active' : '' ?>">
                    <i class="bi bi-grid me-2"></i> Dashboard
                </a>
                <a
                    href="<?= htmlspecialchars(app_url('admin/categories.php'), ENT_QUOTES, 'UTF-8') ?>"
                    class="nav-link admin-nav-link<?= in_array($currentPage, ['categories.php', 'create_category.php', 'edit_category.php'], true) ? ' active' : '' ?>">
                    <i class="bi bi-folder2-open me-2"></i> Categories
                </a>
                <a
                    href="<?= htmlspecialchars(app_url('admin/tests.php'), ENT_QUOTES, 'UTF-8') ?>"
                    class="nav-link admin-nav-link<?= $currentPage === 'tests.php' ? ' active' : '' ?>">
                    <i class="bi bi-journal-text me-2"></i> All Tests
                </a>
                <a
                    href="<?= htmlspecialchars(app_url('admin/create_test.php'), ENT_QUOTES, 'UTF-8') ?>"
                    class="nav-link admin-nav-link<?= $currentPage === 'create_test.php' ? ' active' : '' ?>">
                    <i class="bi bi-plus-circle me-2"></i> Create Test
                </a>
                <a
                    href="<?= htmlspecialchars(app_url('admin/questions.php'), ENT_QUOTES, 'UTF-8') ?>"
                    class="nav-link admin-nav-link<?= in_array($currentPage, ['questions.php', 'create_question.php', 'edit_question.php'], true) ? ' active' : '' ?>">
                    <i class="bi bi-question-circle me-2"></i> Questions
                </a>
                <a
                    href="<?= htmlspecialchars(app_url('admin/results.php'), ENT_QUOTES, 'UTF-8') ?>"
                    class="nav-link admin-nav-link<?= $currentPage === 'results.php' ? ' active' : '' ?>">
                    <i class="bi bi-clipboard-data me-2"></i> User Results
                </a>
            </nav>
        </div>
    </div>
</aside>
