<?php

declare(strict_types=1);

/**
 * CSRF protection for state-changing POST requests.
 * Token is stored in session and validated with hash_equals() to prevent timing attacks.
 */

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    $token = htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8');

    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

function csrf_verify(): bool
{
    return isset($_POST['csrf_token'], $_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], (string) $_POST['csrf_token']);
}
