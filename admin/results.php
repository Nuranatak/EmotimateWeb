<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/stats_helpers.php';
require_once __DIR__ . '/../includes/user_helpers.php';
require_once __DIR__ . '/../includes/empty_state.php';

$pageTitle = 'User Results';
$activeNav = 'admin-results';
$results = fetch_all_results_admin($conn, 200);

require_once __DIR__ . '/../includes/admin_layout_start.php';
?>

<div class="card admin-card shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
            <div>
                <h2 class="h5 mb-1"><i class="bi bi-clipboard-data me-2"></i>Platform Test Results</h2>
                <p class="text-muted small mb-0">All psychological test completions submitted by members.</p>
            </div>
            <span class="badge badge-emotimate"><?= count($results) ?> result<?= count($results) === 1 ? '' : 's' ?></span>
        </div>

        <?php if ($results === []): ?>
            <?php
            render_empty_state([
                'icon'    => 'bi-clipboard-x',
                'title'   => 'No results yet',
                'message' => 'When members complete tests, their scores and interpretations will appear here.',
            ]);
            ?>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-emotimate">
                        <tr>
                            <th>#</th>
                            <th>Member</th>
                            <th>Test</th>
                            <th>Score</th>
                            <th>Level</th>
                            <th>Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $row): ?>
                            <tr>
                                <td class="text-muted"><?= (int) $row['id'] ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($row['user_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($row['test_title'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= (int) $row['score'] ?></td>
                                <td>
                                    <span class="badge interpretation-badge <?= htmlspecialchars(interpretation_badge_class($row['interpretation']), ENT_QUOTES, 'UTF-8') ?>">
                                        <?= htmlspecialchars($row['interpretation'], ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    <?= htmlspecialchars(date('M j, Y g:i A', strtotime($row['created_at'])), ENT_QUOTES, 'UTF-8') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_layout_end.php'; ?>
