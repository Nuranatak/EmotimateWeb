<?php

declare(strict_types=1);

require_once __DIR__ . '/session.php';
require_once __DIR__ . '/role_helpers.php';

session_start_safe();

$isLoggedIn = isset($_SESSION['user_id']);
$displayName = htmlspecialchars($_SESSION['user_name'] ?? 'User', ENT_QUOTES, 'UTF-8');
$isAdminUser = $isLoggedIn && is_admin();
$navContainerClass = !empty($navbarContainerFluid) ? 'container-fluid' : 'container';

/**
 * Navbar modes:
 * - guest: Login / Register / Admin Login
 * - user:  Member area only (non-admin accounts)
 * - admin: Admin panel only (admin accounts)
 */
$navbarMode = $navbarMode ?? 'auto';

if ($navbarMode === 'auto') {
    if (!$isLoggedIn) {
        $navbarMode = 'guest';
    } elseif ($isAdminUser) {
        $navbarMode = 'admin';
    } else {
        $navbarMode = 'user';
    }
}
?>

<nav class="navbar navbar-expand-lg navbar-dark navbar-emotimate shadow-sm">
    <div class="<?= htmlspecialchars($navContainerClass, ENT_QUOTES, 'UTF-8') ?>">
        <a class="navbar-brand fw-bold" href="<?= htmlspecialchars(app_url(), ENT_QUOTES, 'UTF-8') ?>">
            <i class="bi bi-heart-pulse me-1"></i> EMOTIMATE
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <?php if ($navbarMode === 'admin' && $isLoggedIn && $isAdminUser): ?>
                    <li class="nav-item d-lg-none">
                        <span class="nav-link text-white-50">Admin: <?= $displayName ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?= ($activeNav ?? '') === 'admin-dashboard' ? ' active fw-semibold' : '' ?>" href="<?= htmlspecialchars(app_url('admin/dashboard.php'), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-grid me-1"></i> Admin Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?= ($activeNav ?? '') === 'admin-categories' ? ' active fw-semibold' : '' ?>" href="<?= htmlspecialchars(app_url('admin/categories.php'), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-folder2-open me-1"></i> Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?= ($activeNav ?? '') === 'admin-tests' ? ' active fw-semibold' : '' ?>" href="<?= htmlspecialchars(app_url('admin/tests.php'), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-journal-text me-1"></i> Test Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?= ($activeNav ?? '') === 'admin-questions' ? ' active fw-semibold' : '' ?>" href="<?= htmlspecialchars(app_url('admin/questions.php'), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-question-circle me-1"></i> Question Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?= ($activeNav ?? '') === 'admin-results' ? ' active fw-semibold' : '' ?>" href="<?= htmlspecialchars(app_url('admin/results.php'), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-clipboard-data me-1"></i> User Results
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="<?= htmlspecialchars(app_url('auth/logout.php'), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </a>
                    </li>

                <?php elseif ($navbarMode === 'user' && $isLoggedIn && !$isAdminUser): ?>
                    <li class="nav-item d-lg-none">
                        <span class="nav-link text-white-50">Hi, <?= $displayName ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= htmlspecialchars(app_url('tests/index.php'), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-play-circle me-1"></i> Tests
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= htmlspecialchars(app_url('user/dashboard.php'), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-grid me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= htmlspecialchars(app_url('user/results.php'), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-clock-history me-1"></i> Results
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= htmlspecialchars(app_url('user/profile.php'), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-person me-1"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="<?= htmlspecialchars(app_url('auth/logout.php'), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </a>
                    </li>

                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= htmlspecialchars(app_url('auth/login.php'), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= htmlspecialchars(app_url('auth/register.php'), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-person-plus me-1"></i> Register
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= htmlspecialchars(app_url('admin/login.php'), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-shield-lock me-1"></i> Admin Login
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
