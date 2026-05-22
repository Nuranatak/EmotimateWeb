<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

/**
 * Start the session once with secure cookie defaults aligned to APP_BASE.
 * HttpOnly reduces XSS cookie theft; SameSite=Lax limits CSRF on cross-site requests.
 */
function session_start_safe(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $isSecure = (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443)
    );

    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => APP_BASE . '/',
        'domain'   => '',
        'secure'   => $isSecure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}
