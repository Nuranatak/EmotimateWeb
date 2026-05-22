<?php

declare(strict_types=1);

/**
 * Database bootstrap — loads credentials from local or example config, then opens mysqli.
 */

require_once __DIR__ . '/../includes/bootstrap.php';

$configFile = __DIR__ . '/database.local.php';

if (!is_file($configFile)) {
    $configFile = __DIR__ . '/database.example.php';
}

$dbConfig = require $configFile;

if (!is_array($dbConfig)) {
    http_response_code(503);
    exit('Invalid database configuration. Use config/database.example.php as a template.');
}

$host = (string) ($dbConfig['host'] ?? 'localhost');
$dbname = (string) ($dbConfig['dbname'] ?? 'emotimate');
$username = (string) ($dbConfig['username'] ?? 'root');
$password = (string) ($dbConfig['password'] ?? '');
$charset = (string) ($dbConfig['charset'] ?? 'utf8mb4');

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    error_log('EMOTIMATE DB connection failed: ' . $conn->connect_error);
    http_response_code(503);

    if (defined('APP_DEBUG') && APP_DEBUG) {
        exit('Database connection failed: ' . $conn->connect_error);
    }

    exit('Unable to connect to the database. Please check your configuration and try again.');
}

if (!$conn->set_charset($charset)) {
    error_log('EMOTIMATE DB charset failed: ' . $conn->error);
}
