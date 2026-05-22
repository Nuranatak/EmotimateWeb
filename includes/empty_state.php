<?php

declare(strict_types=1);

/**
 * Render a consistent empty-state card.
 *
 * @param array{
 *   icon?: string,
 *   title: string,
 *   message: string,
 *   action_label?: string,
 *   action_url?: string,
 *   action_class?: string
 * } $options
 */
function render_empty_state(array $options): void
{
    $icon = $options['icon'] ?? 'bi-inbox';
    $title = htmlspecialchars($options['title'], ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($options['message'], ENT_QUOTES, 'UTF-8');
    $actionLabel = $options['action_label'] ?? null;
    $actionUrl = $options['action_url'] ?? null;
    $actionClass = $options['action_class'] ?? 'btn-emotimate';

    echo '<div class="card shadow-sm border-0 empty-state card-hover">';
    echo '<div class="card-body p-5 text-center">';
    echo '<div class="empty-state-icon mb-3"><i class="bi ' . htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') . '"></i></div>';
    echo '<h2 class="h5 fw-semibold mb-2">' . $title . '</h2>';
    echo '<p class="text-muted mb-4 mx-auto empty-state-message">' . $message . '</p>';

    if ($actionLabel !== null && $actionUrl !== null) {
        echo '<a href="' . htmlspecialchars($actionUrl, ENT_QUOTES, 'UTF-8') . '" class="btn ' . htmlspecialchars($actionClass, ENT_QUOTES, 'UTF-8') . '">';
        echo htmlspecialchars($actionLabel, ENT_QUOTES, 'UTF-8');
        echo '</a>';
    }

    echo '</div></div>';
}
