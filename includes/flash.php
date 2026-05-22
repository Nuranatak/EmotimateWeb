<?php

declare(strict_types=1);

/**
 * Store a one-time message in session (success or error).
 */
function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type'    => $type,
        'message' => $message,
    ];
}

function render_flash(): void
{
    if (empty($_SESSION['flash'])) {
        return;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    $flashType = $flash['type'] ?? 'error';
    $type = match ($flashType) {
        'success' => 'success',
        'info'    => 'info',
        default   => 'danger',
    };
    $icon = match ($flashType) {
        'success' => 'bi-check-circle-fill',
        'info'    => 'bi-info-circle-fill',
        default   => 'bi-exclamation-triangle-fill',
    };
    $message = htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8');

    echo '<div class="alert alert-' . $type . ' alert-flash alert-dismissible fade show mb-4" role="alert" data-auto-dismiss="true">';
    echo '<i class="bi ' . $icon . ' flash-icon"></i>';
    echo '<div class="flex-grow-1">' . $message . '</div>';
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
}
