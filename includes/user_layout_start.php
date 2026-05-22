<?php

declare(strict_types=1);

$pageTitle = $pageTitle ?? 'EMOTIMATE';

require_once __DIR__ . '/header.php';
require_once __DIR__ . '/navbar.php';
require_once __DIR__ . '/flash.php';
?>

<div class="container py-4 py-lg-5">
    <?php render_flash(); ?>
