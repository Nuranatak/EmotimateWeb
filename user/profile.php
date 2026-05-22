<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/role_helpers.php';

$pageTitle = 'My Profile';
$userId = (int) $_SESSION['user_id'];
$user = fetch_user_by_id($conn, $userId);

if ($user === null) {
    require_once __DIR__ . '/../includes/flash.php';
    set_flash('error', 'User profile not found.');
    header('Location: ' . app_url('user/dashboard.php'), true, 302);
    exit;
}

require_once __DIR__ . '/../includes/user_layout_start.php';
?>

<div class="page-hero mb-4">
    <h1 class="h3 fw-bold mb-2"><i class="bi bi-person-circle me-2"></i>My Profile</h1>
    <p class="mb-0 text-white-50">Update your name or email. Change your password only if you want to.</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 card-hover">
            <div class="card-body p-4 p-md-5">
                <form action="<?= htmlspecialchars(app_url('actions/update_profile_action.php'), ENT_QUOTES, 'UTF-8') ?>" method="POST" novalidate data-loading-form>
                    <?= csrf_field() ?>

                    <h2 class="h6 fw-semibold mb-3">Account details</h2>

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control"
                            id="name"
                            name="name"
                            maxlength="100"
                            required
                            value="<?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?>">
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            maxlength="150"
                            required
                            value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>">
                    </div>

                    <hr class="my-4">

                    <h2 class="h6 fw-semibold mb-2">Security</h2>
                    <p class="text-muted small mb-3">
                        Enter your <strong>current password</strong> to save any changes to your profile.
                    </p>

                    <div class="mb-4">
                        <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                        <input
                            type="password"
                            class="form-control"
                            id="current_password"
                            name="current_password"
                            required
                            autocomplete="current-password">
                    </div>

                    <h2 class="h6 fw-semibold mb-2">Change password <span class="text-muted fw-normal">(optional)</span></h2>
                    <p class="text-muted small mb-3">Leave both fields empty to keep your current password.</p>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input
                            type="password"
                            class="form-control"
                            id="new_password"
                            name="new_password"
                            autocomplete="new-password"
                            minlength="6">
                    </div>

                    <div class="mb-4">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input
                            type="password"
                            class="form-control"
                            id="confirm_password"
                            name="confirm_password"
                            autocomplete="new-password"
                            minlength="6">
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-emotimate" data-loading data-loading-text="Saving...">
                            <i class="bi bi-check-lg me-1"></i> Save Changes
                        </button>
                        <a href="<?= htmlspecialchars(app_url('user/dashboard.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-emotimate">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/user_layout_end.php'; ?>

