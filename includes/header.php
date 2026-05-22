<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

?>
<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($pageTitle ?? 'EMOTIMATE', ENT_QUOTES, 'UTF-8') ?></title>



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('css/theme.css'), ENT_QUOTES, 'UTF-8') ?>">

    <?php if (!empty($extraStylesheets) && is_array($extraStylesheets)): ?>

        <?php foreach ($extraStylesheets as $stylesheet): ?>

            <link rel="stylesheet" href="<?= htmlspecialchars($stylesheet, ENT_QUOTES, 'UTF-8') ?>">

        <?php endforeach; ?>

    <?php endif; ?>

</head>

<body<?= !empty($bodyClass) ? ' class="' . htmlspecialchars((string) $bodyClass, ENT_QUOTES, 'UTF-8') . '"' : '' ?>>

