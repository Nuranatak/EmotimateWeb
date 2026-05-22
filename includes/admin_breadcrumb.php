<?php

declare(strict_types=1);

/**
 * @param list<array{label: string, url?: string}> $items
 */
function render_admin_breadcrumb(array $items): void
{
    if ($items === []) {
        return;
    }

    echo '<nav aria-label="breadcrumb" class="mb-3">';
    echo '<ol class="breadcrumb mb-0">';

    $last = count($items) - 1;

    foreach ($items as $index => $item) {
        $label = htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8');
        $isActive = $index === $last || empty($item['url']);

        echo '<li class="breadcrumb-item' . ($isActive ? ' active' : '') . '"';
        if ($isActive) {
            echo ' aria-current="page"';
        }
        echo '>';

        if (!$isActive && !empty($item['url'])) {
            echo '<a href="' . htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8') . '">' . $label . '</a>';
        } else {
            echo $label;
        }

        echo '</li>';
    }

    echo '</ol>';
    echo '</nav>';
}
