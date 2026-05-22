<?php

declare(strict_types=1);

/**
 * Blocks setup utilities when SETUP_ENABLED is false (production deployments).
 */

require_once __DIR__ . '/bootstrap.php';

if (!defined('SETUP_ENABLED') || SETUP_ENABLED !== true) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Setup scripts are disabled. Set SETUP_ENABLED to true in config/app.local.php for local use, or run setup before deployment.';
    exit;
}
