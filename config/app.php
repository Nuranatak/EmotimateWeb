<?php

declare(strict_types=1);

/**
 * Loads application settings from app.local.php or app.example.php.
 */

$configFile = __DIR__ . '/app.local.php';

if (!is_file($configFile)) {
    $configFile = __DIR__ . '/app.example.php';
}

require $configFile;
