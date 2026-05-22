<?php

declare(strict_types=1);

/**
 * Normalize role string for safe comparison.
 */
function normalize_role(mixed $role): string
{
    return strtolower(trim((string) $role));
}

/**
 * Whether the current session belongs to an administrator.
 */
function is_admin(): bool
{
    if (!isset($_SESSION['user_role'])) {
        return false;
    }

    return normalize_role($_SESSION['user_role']) === 'admin';
}

/**
 * Fetch a user row by id (works with and without mysqlnd).
 *
 * @return array{name: string, email: string, role: string}|null
 */
function fetch_user_by_id(mysqli $conn, int $userId): ?array
{
    $stmt = $conn->prepare('SELECT name, email, role FROM users WHERE id = ? LIMIT 1');

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param('i', $userId);

    if (!$stmt->execute()) {
        $stmt->close();
        return null;
    }

    $name = $email = $role = null;
    $stmt->bind_result($name, $email, $role);

    if (!$stmt->fetch()) {
        $stmt->close();
        return null;
    }

    $stmt->close();

    return [
        'name'  => (string) $name,
        'email' => (string) $email,
        'role'  => (string) $role,
    ];
}

/**
 * Fetch user credentials including password hash for verification.
 *
 * @return array{name: string, email: string, password: string, role: string}|null
 */
function fetch_user_with_password(mysqli $conn, int $userId): ?array
{
    $stmt = $conn->prepare('SELECT name, email, password, role FROM users WHERE id = ? LIMIT 1');

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param('i', $userId);

    if (!$stmt->execute()) {
        $stmt->close();
        return null;
    }

    $name = $email = $password = $role = null;
    $stmt->bind_result($name, $email, $password, $role);

    if (!$stmt->fetch()) {
        $stmt->close();
        return null;
    }

    $stmt->close();

    return [
        'name'     => (string) $name,
        'email'    => (string) $email,
        'password' => (string) $password,
        'role'     => (string) $role,
    ];
}

/**
 * Authoritative admin check against the database (not session alone).
 * Prevents privilege escalation if session role is stale or tampered with.
 */
function user_is_admin_in_db(mysqli $conn, int $userId): bool
{
    $user = fetch_user_by_id($conn, $userId);

    if ($user === null) {
        return false;
    }

    return normalize_role($user['role']) === 'admin';
}

/**
 * Load role (and name) from database into the session for the logged-in user.
 * Returns the normalized role, or null if the user record was not found.
 */
function sync_user_role_from_db(mysqli $conn): ?string
{
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    $userId = (int) $_SESSION['user_id'];
    $user = fetch_user_by_id($conn, $userId);

    if ($user === null) {
        return null;
    }

    $role = normalize_role($user['role']);

    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $role;

    return $role;
}
