<?php

declare(strict_types=1);

$pageTitle = $pageTitle ?? 'Admin';
$bodyClass = 'admin-body';
$extraStylesheets = [asset_url('css/admin.css')];
$navbarContainerFluid = true;
$navbarMode = 'admin';

require_once __DIR__ . '/header.php';
require_once __DIR__ . '/navbar.php';
?>

<div class="container-fluid py-4 px-3 px-lg-4">
    <div class="admin-hero p-4 p-md-5 mb-4 shadow-sm">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <p class="text-white-50 small text-uppercase mb-1">EMOTIMATE Admin</p>
                <h1 class="h3 mb-0 fw-bold"><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></h1>
            </div>
            <div class="text-white-50 small">
                Signed in as <strong class="text-white"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin', ENT_QUOTES, 'UTF-8') ?></strong>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/flash.php'; render_flash(); ?>

    <button
        class="btn btn-emotimate d-lg-none w-100 mb-3"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#adminSidebar"
        aria-controls="adminSidebar"
        aria-expanded="false"
        id="adminSidebarToggle">
        <i class="bi bi-list me-2"></i> Toggle Admin Menu
    </button>

    <div class="row">
        <?php require_once __DIR__ . '/admin_sidebar.php'; ?>
        <main class="col-lg-9 col-xl-10">
