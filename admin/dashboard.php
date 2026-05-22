<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/stats_helpers.php';
require_once __DIR__ . '/../includes/user_helpers.php';
require_once __DIR__ . '/../includes/empty_state.php';

$pageTitle = 'Admin Dashboard';

$stats = [
    'users'     => count_table_rows($conn, 'users'),
    'tests'     => count_table_rows($conn, 'tests'),
    'questions' => count_table_rows($conn, 'questions'),
    'results'   => count_table_rows($conn, 'results'),
];

$recentActivity = fetch_recent_results_admin($conn, 8);
$interpretationChart = fetch_results_by_interpretation($conn);
$activityChart = fetch_activity_last_days($conn, 7);

$includeChartJs = true;

require_once __DIR__ . '/../includes/admin_layout_start.php';
?>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card shadow-sm border-0 h-100 card-hover">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="bi bi-people"></i></div>
                    <div>
                        <p class="text-muted small mb-1">Total Users</p>
                        <p class="h3 mb-0 fw-bold"><?= $stats['users'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card shadow-sm border-0 h-100 card-hover">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="bi bi-journal-text"></i></div>
                    <div>
                        <p class="text-muted small mb-1">Total Tests</p>
                        <p class="h3 mb-0 fw-bold"><?= $stats['tests'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card shadow-sm border-0 h-100 card-hover">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="bi bi-question-circle"></i></div>
                    <div>
                        <p class="text-muted small mb-1">Total Questions</p>
                        <p class="h3 mb-0 fw-bold"><?= $stats['questions'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card shadow-sm border-0 h-100 card-hover">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="bi bi-clipboard-data"></i></div>
                    <div>
                        <p class="text-muted small mb-1">Completed Results</p>
                        <p class="h3 mb-0 fw-bold"><?= $stats['results'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-5">
        <div class="card admin-card shadow-sm chart-card card-hover">
            <div class="card-body p-4">
                <h2 class="h6 fw-bold mb-3"><i class="bi bi-pie-chart me-2"></i>Results by Level</h2>
                <div class="chart-wrap">
                    <canvas id="adminResultsChart" aria-label="Results by interpretation"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card admin-card shadow-sm chart-card card-hover">
            <div class="card-body p-4">
                <h2 class="h6 fw-bold mb-3"><i class="bi bi-bar-chart-line me-2"></i>Activity (Last 7 Days)</h2>
                <div class="chart-wrap">
                    <canvas id="adminActivityChart" aria-label="Daily test completions"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card admin-card shadow-sm h-100 card-hover">
            <div class="card-body p-4">
                <h2 class="h6 fw-bold mb-3"><i class="bi bi-lightning me-2"></i>Quick Navigation</h2>
                <div class="d-grid gap-2">
                    <a href="<?= htmlspecialchars(app_url('admin/tests.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-emotimate">
                        <i class="bi bi-journal-text me-1"></i> Manage Tests
                    </a>
                    <a href="<?= htmlspecialchars(app_url('admin/create_test.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-emotimate">
                        <i class="bi bi-plus-circle me-1"></i> Create Test
                    </a>
                    <a href="<?= htmlspecialchars(app_url('admin/questions.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-emotimate">
                        <i class="bi bi-question-circle me-1"></i> Manage Questions
                    </a>
                    <a href="<?= htmlspecialchars(app_url('user/dashboard.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-emotimate">
                        <i class="bi bi-person me-1"></i> User Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card admin-card shadow-sm h-100 card-hover">
            <div class="card-body p-4">
                <h2 class="h6 fw-bold mb-3"><i class="bi bi-activity me-2"></i>Recent Test Completions</h2>
                <?php if ($recentActivity === []): ?>
                    <?php
                    render_empty_state([
                        'icon'          => 'bi-clipboard-x',
                        'title'         => 'No activity yet',
                        'message'       => 'When users complete tests, their results will appear here.',
                        'action_label'  => 'Manage tests',
                        'action_url'    => app_url('admin/tests.php'),
                    ]);
                    ?>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-emotimate">
                                <tr>
                                    <th>User</th>
                                    <th>Test</th>
                                    <th>Score</th>
                                    <th>Level</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentActivity as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['user_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($row['test_title'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= (int) $row['score'] ?></td>
                                        <td>
                                            <span class="badge interpretation-badge <?= htmlspecialchars(interpretation_badge_class($row['interpretation']), ENT_QUOTES, 'UTF-8') ?>">
                                                <?= htmlspecialchars($row['interpretation'], ENT_QUOTES, 'UTF-8') ?>
                                            </span>
                                        </td>
                                        <td class="text-muted small">
                                            <?= htmlspecialchars(date('M j, Y', strtotime($row['created_at'])), ENT_QUOTES, 'UTF-8') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$interpLabelsJson = json_encode($interpretationChart['labels'], JSON_THROW_ON_ERROR);
$interpValuesJson = json_encode($interpretationChart['values'], JSON_THROW_ON_ERROR);
$activityLabelsJson = json_encode($activityChart['labels'], JSON_THROW_ON_ERROR);
$activityValuesJson = json_encode($activityChart['values'], JSON_THROW_ON_ERROR);

$inlineScripts = <<<HTML
<script>
document.addEventListener('DOMContentLoaded', function () {
    var palette = window.EmotimateCharts.greenPalette;

    new Chart(document.getElementById('adminResultsChart'), {
        type: 'doughnut',
        data: {
            labels: {$interpLabelsJson},
            datasets: [{
                data: {$interpValuesJson},
                backgroundColor: ['#d4eddf', '#fff3cd', '#f8d7da'],
                borderWidth: 2
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    new Chart(document.getElementById('adminActivityChart'), {
        type: 'bar',
        data: {
            labels: {$activityLabelsJson},
            datasets: [{
                label: 'Completions',
                data: {$activityValuesJson},
                backgroundColor: palette[0],
                borderRadius: 8
            }]
        },
        options: Object.assign({}, window.EmotimateCharts.defaults(), {
            plugins: { legend: { display: false } }
        })
    });
});
</script>
HTML;

require_once __DIR__ . '/../includes/admin_layout_end.php';
