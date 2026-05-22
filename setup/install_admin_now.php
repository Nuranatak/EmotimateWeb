<?php

declare(strict_types=1);

/**
 * One-click admin installer — visit once after importing database/emotimate.sql.
 */

require_once __DIR__ . '/../includes/setup_guard.php';
require_once __DIR__ . '/../config/database.php';

const ADMIN_EMAIL = 'admin@emotimate.com';
const ADMIN_PASSWORD = 'admin123';
const ADMIN_NAME = 'EMOTIMATE Administrator';

header('Content-Type: text/html; charset=utf-8');

$tableCheck = $conn->query("SHOW TABLES LIKE 'users'");

if (!$tableCheck || $tableCheck->num_rows === 0) {
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Setup error</title>';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="p-5">';
    echo '<div class="alert alert-danger">The <strong>users</strong> table is missing. Import ';
    echo '<code>database/emotimate.sql</code> into the <strong>emotimate</strong> database in phpMyAdmin first.</div></body></html>';
    exit;
}

$name = ADMIN_NAME;
$email = strtolower(ADMIN_EMAIL);
$hash = password_hash(ADMIN_PASSWORD, PASSWORD_DEFAULT);
$role = 'admin';

$check = $conn->prepare('SELECT id FROM users WHERE LOWER(email) = ? LIMIT 1');
$check->bind_param('s', $email);
$check->execute();
$check->store_result();
$exists = $check->num_rows > 0;
$check->close();

if ($exists) {
    $stmt = $conn->prepare('UPDATE users SET name = ?, password = ?, role = ? WHERE LOWER(email) = ?');
    $stmt->bind_param('ssss', $name, $hash, $role, $email);
    $action = 'updated';
} else {
    $stmt = $conn->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $name, $email, $hash, $role);
    $action = 'created';
}

$ok = $stmt->execute();
$error = $stmt->error;
$stmt->close();

file_put_contents(__DIR__ . '/.admin_seeded.lock', date('c') . "\n");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="admin-body p-5">
<div class="container" style="max-width: 520px;">
    <?php if ($ok): ?>
        <div class="alert alert-success">
            <h1 class="h5">Admin account <?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?></h1>
            <p class="mb-2"><strong>Email:</strong> <?= htmlspecialchars(ADMIN_EMAIL, ENT_QUOTES, 'UTF-8') ?></p>
            <p class="mb-3"><strong>Password:</strong> <?= htmlspecialchars(ADMIN_PASSWORD, ENT_QUOTES, 'UTF-8') ?></p>
            <a href="<?= htmlspecialchars(app_url('admin/login.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-success">
                Go to admin login
            </a>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">
            <h1 class="h5">Error</h1>
            <p><?= htmlspecialchars($error ?: 'Unknown error', ENT_QUOTES, 'UTF-8') ?></p>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
