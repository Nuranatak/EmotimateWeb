<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/user_helpers.php';

$pageTitle = 'My Results';
$userId = (int) $_SESSION['user_id'];
$results = fetch_user_results_history($conn, $userId);

require_once __DIR__ . '/../includes/user_layout_start.php';
?>

<div class="page-hero mb-4">
    <h1 class="h3 fw-bold mb-2"><i class="bi bi-clock-history me-2"></i>Result History</h1>
    <p class="mb-0 text-white-50">All psychological tests you have completed on EMOTIMATE.</p>
</div>

<?php if ($results === []): ?>
    <?php
    require_once __DIR__ . '/../includes/empty_state.php';
    render_empty_state([
        'icon'          => 'bi-clipboard-x',
        'title'         => 'No results yet',
        'message'       => 'Complete your first test to see your scores and interpretations here.',
        'action_label'  => 'Browse Tests',
        'action_url'    => app_url('tests/index.php'),
    ]);
    ?>
<?php else: ?>
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-emotimate">
                        <tr>
                            <th>Test</th>
                            <th>Score</th>
                            <th>Interpretation</th>
                            <th>Date</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $row): ?>
                            <tr>
                                <td class="fw-semibold"><?= htmlspecialchars($row['test_title'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= (int) $row['score'] ?></td>
                                <td>
                                    <span class="badge interpretation-badge <?= htmlspecialchars(interpretation_badge_class($row['interpretation']), ENT_QUOTES, 'UTF-8') ?>">
                                        <?= htmlspecialchars($row['interpretation'], ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    <?= htmlspecialchars(date('M j, Y g:i A', strtotime($row['created_at'])), ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td class="text-end">
                                    <a
                                        href="<?= htmlspecialchars(app_url('tests/result.php?id=' . $row['id']), ENT_QUOTES, 'UTF-8') ?>"
                                        class="btn btn-sm btn-outline-emotimate">
                                        <i class="bi bi-eye me-1"></i> View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/user_layout_end.php'; ?>
