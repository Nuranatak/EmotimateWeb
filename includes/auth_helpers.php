<?php

declare(strict_types=1);

/**
 * Shared credential verification for login flows.
 * Returns user row on success, null when email/password is invalid.
 *
 * @return array{id: int, name: string, role: string}|null
 */
function authenticate_user(mysqli $conn, string $email, string $password): ?array
{
    $email = strtolower(trim($email));

    $stmt = $conn->prepare('SELECT id, name, password, role FROM users WHERE LOWER(email) = ? LIMIT 1');

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param('s', $email);
    $stmt->execute();

    $id = $name = $hash = $role = null;
    $stmt->bind_result($id, $name, $hash, $role);

    if (!$stmt->fetch()) {
        $stmt->close();
        return null;
    }

    $stmt->close();

    if (!password_verify($password, $hash)) {
        return null;
    }

    return [
        'id'   => (int) $id,
        'name' => (string) $name,
        'role' => normalize_role($role),
    ];
}

/**
 * Establish session after successful authentication.
 */
function login_user_session(array $user, mysqli $conn): void
{
    session_regenerate_id(true);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = user_is_admin_in_db($conn, $user['id']) ? 'admin' : 'user';
}
