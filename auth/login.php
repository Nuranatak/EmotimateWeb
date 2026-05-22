<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/role_helpers.php';

session_start_safe();

if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../includes/role_helpers.php';

    if (user_is_admin_in_db($conn, (int) $_SESSION['user_id'])) {
        header('Location: ' . app_url('admin/dashboard.php'), true, 302);
    } else {
        header('Location: ' . app_url('user/dashboard.php'), true, 302);
    }
    exit;
}

$pageTitle = 'Login — EMOTIMATE';
$navbarMode = 'guest';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/../includes/csrf.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card auth-card shadow-sm border-0 card-hover">
                <div class="auth-card-header">
                    <i class="bi bi-box-arrow-in-right display-6 mb-2"></i>
                    <h1 class="h4 mb-0">Welcome back</h1>
                    <p class="small text-white-50 mb-0 mt-1">Sign in to your member account</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    <?php render_flash(); ?>

                    <form action="<?= htmlspecialchars(app_url('actions/login_action.php'), ENT_QUOTES, 'UTF-8') ?>" method="POST" data-loading-form>
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required autocomplete="email">
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required autocomplete="current-password">
                        </div>
                        <button type="submit" class="btn btn-emotimate w-100" data-loading data-loading-text="Signing in...">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </button>
                    </form>
                    <p class="text-center text-muted small mt-4 mb-2">
                        Don't have an account?
                        <a href="<?= htmlspecialchars(app_url('auth/register.php'), ENT_QUOTES, 'UTF-8') ?>">Register</a>
                    </p>
                    <p class="text-center text-muted small mb-0">
                        Administrator?
                        <a href="<?= htmlspecialchars(app_url('admin/login.php'), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-shield-lock me-1"></i> Admin Login
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
