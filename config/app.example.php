<?php

declare(strict_types=1);

/**
 * Application configuration template.
 * Copy to config/app.local.php to override (gitignored).
 */

/** URL path prefix where the app is installed (no trailing slash). Root install: '' or '/'. */
if (!defined('APP_BASE')) {
    define('APP_BASE', '/emotimate');
}

/** When false, PHP errors are logged and not shown to visitors. */
if (!defined('APP_DEBUG')) {
    define('APP_DEBUG', false);
}

/** Environment label: local | production */
if (!defined('APP_ENV')) {
    define('APP_ENV', 'production');
}

/**
 * Allow /setup/*.php scripts. Set to false on shared hosting after installation.
 * You can also block the setup/ folder via .htaccess or remove it from the server.
 */
if (!defined('SETUP_ENABLED')) {
    define('SETUP_ENABLED', true);
}
