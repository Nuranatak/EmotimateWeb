<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/session.php';
session_start_safe();

if (isset($_SESSION['user_id'])) {
    header('Location: ' . app_url('user/dashboard.php'), true, 302);
    exit;
}

$pageTitle = 'Register — EMOTIMATE';
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
                    <i class="bi bi-person-plus display-6 mb-2"></i>
                    <h1 class="h4 mb-0">Create your account</h1>
                    <p class="small text-white-50 mb-0 mt-1">Join EMOTIMATE and start your first assessment</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    <?php render_flash(); ?>

                    <form action="<?= htmlspecialchars(app_url('actions/register_action.php'), ENT_QUOTES, 'UTF-8') ?>" method="POST" data-loading-form>
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" name="name" id="name" class="form-control" required autocomplete="name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required autocomplete="email">
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required minlength="6" autocomplete="new-password">
                            <div class="form-text">At least 6 characters.</div>
                        </div>
                        <button type="submit" class="btn btn-emotimate w-100" data-loading data-loading-text="Creating account...">
                            <i class="bi bi-person-plus me-1"></i> Register
                        </button>
                    </form>
                    <p class="text-center text-muted small mt-4 mb-0">
                        Already have an account?
                        <a href="<?= htmlspecialchars(app_url('auth/login.php'), ENT_QUOTES, 'UTF-8') ?>">Sign in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
