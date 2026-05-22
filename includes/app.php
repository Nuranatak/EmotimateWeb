<?php

declare(strict_types=1);

/**
 * URL helpers — constants come from config/app.php via bootstrap.php.
 */

/**
 * Build an absolute path URL under the application base (e.g. /emotimate/auth/login.php).
 */
function app_url(string $path = ''): string
{
    $base = defined('APP_BASE') ? APP_BASE : '';
    $path = ltrim($path, '/');

    if ($base === '' || $base === '/') {
        return $path === '' ? '/' : '/' . $path;
    }

    return $path === '' ? $base : $base . '/' . $path;
}

/**
 * Public asset path under /assets/ (respects APP_BASE).
 */
function asset_url(string $path): string
{
    return app_url('assets/' . ltrim($path, '/'));
}
