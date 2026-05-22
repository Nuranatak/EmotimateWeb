<?php

declare(strict_types=1);

/**
 * Application bootstrap: configuration, error handling, URL helpers.
 */

if (defined('EMOTIMATE_BOOTSTRAP_LOADED')) {
    return;
}

define('EMOTIMATE_BOOTSTRAP_LOADED', true);

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/app.php';

if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
} else {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    ini_set('log_errors', '1');
}

set_exception_handler(static function (Throwable $e): void {
    error_log('EMOTIMATE uncaught exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

    if (!headers_sent()) {
        http_response_code(500);
    }

    if (defined('APP_DEBUG') && APP_DEBUG) {
        echo '<h1>Application error</h1><pre>';
        echo htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        echo "\n" . htmlspecialchars($e->getFile(), ENT_QUOTES, 'UTF-8') . ':' . (int) $e->getLine();
        echo '</pre>';
    } else {
        echo 'Something went wrong. Please try again later.';
    }

    exit(1);
});
