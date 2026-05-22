<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/role_helpers.php';
require_once __DIR__ . '/../config/database.php';

session_start_safe();

if (isset($_SESSION['user_id'])) {
    $userId = (int) $_SESSION['user_id'];

    if (user_is_admin_in_db($conn, $userId)) {
        $_SESSION['user_role'] = 'admin';
        header('Location: ' . app_url('admin/dashboard.php'), true, 302);
        exit;
    }

    require_once __DIR__ . '/../includes/flash.php';
    set_flash('info', 'You are signed in as a member. Use member login or sign out to access the admin panel with an admin account.');
    header('Location: ' . app_url('user/dashboard.php'), true, 302);
    exit;
}

$pageTitle = 'Admin Login — EMOTIMATE';
$bodyClass = 'admin-body';
$extraStylesheets = [asset_url('css/admin.css')];
$navbarMode = 'guest';
$hideSiteFooter = true;

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
                    <i class="bi bi-shield-lock display-6 mb-2"></i>
                    <h1 class="h4 mb-0">Administrator Sign In</h1>
                    <p class="small text-white-50 mb-0 mt-1">Restricted access for platform administrators only</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    <?php render_flash(); ?>

                    <div class="alert alert-light border small mb-4" role="note">
                        <i class="bi bi-info-circle me-1"></i>
                        Only accounts with the <strong>admin</strong> role can sign in here.
                        Regular users should use the <a href="<?= htmlspecialchars(app_url('auth/login.php'), ENT_QUOTES, 'UTF-8') ?>">member login</a>.
                    </div>

                    <form action="<?= htmlspecialchars(app_url('actions/admin_login_action.php'), ENT_QUOTES, 'UTF-8') ?>" method="POST" data-loading-form>
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="email" class="form-label">Admin Email</label>
                            <input type="email" name="email" id="email" class="form-control" required autocomplete="email">
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required autocomplete="current-password">
                        </div>
                        <button type="submit" class="btn btn-emotimate w-100" data-loading data-loading-text="Signing in...">
                            <i class="bi bi-shield-lock me-1"></i> Sign In to Admin Panel
                        </button>
                    </form>
                    <p class="text-center text-muted small mt-4 mb-0">
                        <a href="<?= htmlspecialchars(app_url('auth/login.php'), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-arrow-left me-1"></i> Back to member login
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
