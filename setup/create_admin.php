<?php

declare(strict_types=1);

/**
 * Administrator seed / repair script.
 * Creates or resets: admin@emotimate.com / admin123
 */

require_once __DIR__ . '/../includes/setup_guard.php';
require_once __DIR__ . '/../config/database.php';

const ADMIN_SEED_EMAIL = 'admin@emotimate.com';
const ADMIN_SEED_PASSWORD = 'admin123';
const ADMIN_SEED_NAME = 'EMOTIMATE Administrator';
const ADMIN_LOCK_FILE = __DIR__ . '/.admin_seeded.lock';

$isLocked = is_file(ADMIN_LOCK_FILE);
$message = '';
$messageType = 'info';

/**
 * Create or update the default admin (insert, or fix role + password).
 */
function seed_or_repair_admin(mysqli $conn): array
{
    $name = ADMIN_SEED_NAME;
    $email = strtolower(trim(ADMIN_SEED_EMAIL));
    $hash = password_hash(ADMIN_SEED_PASSWORD, PASSWORD_DEFAULT);
    $role = 'admin';

    $checkStmt = $conn->prepare('SELECT id, role FROM users WHERE LOWER(email) = ? LIMIT 1');

    if (!$checkStmt) {
        return ['ok' => false, 'message' => 'Database error: ' . $conn->error];
    }

    $checkStmt->bind_param('s', $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $existingId = $existingRole = null;
        $checkStmt->bind_result($existingId, $existingRole);
        $checkStmt->fetch();
        $checkStmt->close();

        $updateStmt = $conn->prepare(
            'UPDATE users SET name = ?, password = ?, role = ? WHERE id = ?'
        );

        if (!$updateStmt) {
            return ['ok' => false, 'message' => 'Database error: could not update account.'];
        }

        $updateStmt->bind_param('sssi', $name, $hash, $role, $existingId);

        if (!$updateStmt->execute()) {
            $updateStmt->close();
            return ['ok' => false, 'message' => 'Failed to update administrator account.'];
        }

        $updateStmt->close();

        $wasUser = strtolower((string) $existingRole) !== 'admin';

        return [
            'ok'      => true,
            'message' => $wasUser
                ? 'Existing account upgraded to admin and password reset to admin123.'
                : 'Administrator password reset to admin123 and role confirmed.',
        ];
    }

    $checkStmt->close();

    $insertStmt = $conn->prepare(
        'INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)'
    );

    if (!$insertStmt) {
        return ['ok' => false, 'message' => 'Database error: could not create account.'];
    }

    $insertStmt->bind_param('ssss', $name, $email, $hash, $role);

    if (!$insertStmt->execute()) {
        $insertStmt->close();
        return ['ok' => false, 'message' => 'Failed to create administrator: ' . $conn->error];
    }

    $insertStmt->close();

    return ['ok' => true, 'message' => 'Administrator account created successfully.'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';

    if ($action === 'create' && $isLocked) {
        $message = 'Setup is locked. Use "Reset / repair admin" below if you cannot sign in.';
        $messageType = 'error';
    } elseif ($action === 'create' && ($_POST['confirm'] ?? '') !== 'yes') {
        $message = 'Please confirm account creation.';
        $messageType = 'error';
    } else {
        $result = seed_or_repair_admin($conn);

        if ($result['ok']) {
            file_put_contents(
                ADMIN_LOCK_FILE,
                'seeded_at=' . date('c') . "\nemail=" . ADMIN_SEED_EMAIL . "\n"
            );
            $isLocked = true;
            $message = $result['message'] . ' Sign in at Admin Login with admin@emotimate.com / admin123';
            $messageType = 'success';
        } else {
            $message = $result['message'];
            $messageType = 'error';
        }
    }
}

// Show current DB status
$dbStatus = 'Could not read database.';
$statusStmt = $conn->prepare(
    'SELECT id, email, role, CHAR_LENGTH(password) AS pass_len FROM users WHERE LOWER(email) = ? LIMIT 1'
);

if ($statusStmt) {
    $lookup = strtolower(ADMIN_SEED_EMAIL);
    $statusStmt->bind_param('s', $lookup);
    $statusStmt->execute();
    $statusStmt->bind_result($sid, $semail, $srole, $passLen);

    if ($statusStmt->fetch()) {
        $dbStatus = sprintf(
            'Found user #%d — email: %s — role: %s — password hash length: %d',
            (int) $sid,
            (string) $semail,
            (string) $srole,
            (int) $passLen
        );
    } else {
        $dbStatus = 'No user with email ' . ADMIN_SEED_EMAIL . ' in database "' . $dbname . '".';
    }

    $statusStmt->close();
}

$pageTitle = 'EMOTIMATE — Admin Setup';
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars(app_url('assets/css/theme.css'), ENT_QUOTES, 'UTF-8') ?>">
</head>

<body class="admin-body">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="h4 fw-bold mb-2">
                            <i class="bi bi-shield-lock text-success me-2"></i>Administrator Setup
                        </h1>
                        <p class="text-muted small mb-4">
                            Veritabanı: <strong><?= htmlspecialchars($dbname, ENT_QUOTES, 'UTF-8') ?></strong>
                        </p>

                        <?php if ($message !== ''): ?>
                            <div class="alert alert-<?= $messageType === 'success' ? 'success' : ($messageType === 'error' ? 'danger' : 'info') ?>">
                                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>

                        <?php $adminMissing = str_contains($dbStatus, 'No user with email'); ?>
                        <div class="alert alert-<?= $adminMissing ? 'danger' : 'secondary' ?> small">
                            <strong>Durum:</strong> <?= htmlspecialchars($dbStatus, ENT_QUOTES, 'UTF-8') ?>
                        </div>

                        <?php if ($adminMissing): ?>
                            <div class="alert alert-warning">
                                <strong>Admin hesabı henüz yok.</strong> Önce oluşturmanız gerekir; şu an giriş yapamazsınız.
                            </div>
                            <a href="<?= htmlspecialchars(app_url('setup/install_admin_now.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-success btn-lg w-100 mb-4">
                                Tek tıkla admin hesabı oluştur
                            </a>
                        <?php endif; ?>

                        <table class="table table-sm table-bordered mb-4">
                            <tbody>
                                <tr>
                                    <th>Email</th>
                                    <td><code><?= htmlspecialchars(ADMIN_SEED_EMAIL, ENT_QUOTES, 'UTF-8') ?></code></td>
                                </tr>
                                <tr>
                                    <th>Şifre</th>
                                    <td><code><?= htmlspecialchars(ADMIN_SEED_PASSWORD, ENT_QUOTES, 'UTF-8') ?></code></td>
                                </tr>
                                <tr>
                                    <th>Giriş sayfası</th>
                                    <td><a href="<?= htmlspecialchars(app_url('admin/login.php'), ENT_QUOTES, 'UTF-8') ?>">/admin/login.php</a> (üye girişi değil)</td>
                                </tr>
                            </tbody>
                        </table>

                        <?php if ($isLocked): ?>
                            <div class="alert alert-warning small">
                                Kurulum kilidi aktif (<code>.admin_seeded.lock</code>).
                            </div>
                        <?php endif; ?>

                        <div class="row g-3">
                            <?php if (!$isLocked): ?>
                                <div class="col-md-6">
                                    <form method="POST">
                                        <input type="hidden" name="action" value="create">
                                        <input type="hidden" name="confirm" value="yes">
                                        <button type="submit" class="btn btn-emotimate w-100">
                                            <i class="bi bi-person-plus me-1"></i> Admin hesabı oluştur
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-6">
                                <form method="POST">
                                    <input type="hidden" name="action" value="repair">
                                    <button type="submit" class="btn btn-outline-emotimate w-100">
                                        <i class="bi bi-arrow-repeat me-1"></i> Sıfırla / onar (role + şifre)
                                    </button>
                                </form>
                            </div>
                        </div>

                        <p class="text-muted small mt-4 mb-0">
                            Giriş yapamıyorsanız önce <strong>Sıfırla / onar</strong> düğmesine basın, sonra
                            <a href="<?= htmlspecialchars(app_url('admin/login.php'), ENT_QUOTES, 'UTF-8') ?>">Admin Login</a>
                            ile deneyin.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>