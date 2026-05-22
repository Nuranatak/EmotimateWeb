<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/user_helpers.php';
require_once __DIR__ . '/../includes/empty_state.php';

$pageTitle = 'Dashboard';
$userId = (int) $_SESSION['user_id'];
$userName = htmlspecialchars($_SESSION['user_name'] ?? 'User', ENT_QUOTES, 'UTF-8');

$testsTaken = count_user_results($conn, $userId);
$latestResult = fetch_latest_user_result($conn, $userId);
$recentResults = fetch_user_results_history($conn, $userId, 5);
$testsChart = fetch_user_tests_per_test($conn, $userId);
$scoresChart = fetch_user_score_timeline($conn, $userId);

$includeChartJs = true;

require_once __DIR__ . '/../includes/user_layout_start.php';
?>

<div class="page-hero mb-4 animate-in">
  <h1 class="h3 fw-bold mb-2">Welcome back, <?= $userName ?>!</h1>
  <p class="mb-0 text-white-50">Track your progress and continue your psychological assessments.</p>
</div>

<div class="row g-4 mb-4">
  <div class="col-md-4">
    <div class="card stat-card shadow-sm border-0 h-100 card-hover">
      <div class="card-body p-4">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon"><i class="bi bi-clipboard-check"></i></div>
          <div>
            <p class="text-muted small mb-1">Tests Completed</p>
            <p class="h3 mb-0 fw-bold"><?= $testsTaken ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="card stat-card shadow-sm border-0 h-100 card-hover">
      <div class="card-body p-4">
        <p class="text-muted small mb-2"><i class="bi bi-star me-1"></i> Latest Result</p>
        <?php if ($latestResult === null): ?>
          <p class="mb-3 text-muted">You have not completed a test yet.</p>
          <a href="<?= htmlspecialchars(app_url('tests/index.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-emotimate btn-sm">
            <i class="bi bi-play-circle me-1"></i> Start your first test
          </a>
        <?php else: ?>
          <h2 class="h5 mb-1"><?= htmlspecialchars($latestResult['test_title'], ENT_QUOTES, 'UTF-8') ?></h2>
          <p class="mb-2">
            Score: <strong><?= (int) $latestResult['score'] ?></strong>
            <span class="badge interpretation-badge <?= htmlspecialchars(interpretation_badge_class($latestResult['interpretation']), ENT_QUOTES, 'UTF-8') ?> ms-2">
              <?= htmlspecialchars($latestResult['interpretation'], ENT_QUOTES, 'UTF-8') ?>
            </span>
          </p>
          <p class="text-muted small mb-3">
            <?= htmlspecialchars(date('M j, Y g:i A', strtotime($latestResult['created_at'])), ENT_QUOTES, 'UTF-8') ?>
          </p>
          <a href="<?= htmlspecialchars(app_url('tests/result.php?id=' . $latestResult['id']), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-emotimate btn-sm">
            View details
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php if ($testsTaken > 0): ?>
  <div class="row g-4 mb-4">
    <div class="col-lg-6">
      <div class="card shadow-sm border-0 chart-card card-hover">
        <div class="card-body p-4">
          <h2 class="h6 fw-bold mb-3"><i class="bi bi-bar-chart me-2"></i>Tests Taken</h2>
          <div class="chart-wrap">
            <canvas id="userTestsChart" aria-label="Tests taken by assessment"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card shadow-sm border-0 chart-card card-hover">
        <div class="card-body p-4">
          <h2 class="h6 fw-bold mb-3"><i class="bi bi-graph-up me-2"></i>Score History</h2>
          <div class="chart-wrap">
            <canvas id="userScoresChart" aria-label="Score history over time"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<div class="row g-4">
  <div class="col-lg-4">
    <div class="card shadow-sm border-0 h-100 card-hover">
      <div class="card-body p-4">
        <h2 class="h6 fw-bold mb-3"><i class="bi bi-lightning me-2"></i>Quick Actions</h2>
        <div class="d-grid gap-2">
          <a href="<?= htmlspecialchars(app_url('tests/index.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-emotimate">
            <i class="bi bi-play-circle me-1"></i> Take a Test
          </a>
          <a href="<?= htmlspecialchars(app_url('user/results.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-emotimate">
            <i class="bi bi-clock-history me-1"></i> My Results
          </a>
          <a href="<?= htmlspecialchars(app_url('user/profile.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-emotimate">
            <i class="bi bi-person me-1"></i> Edit Profile
          </a>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="card shadow-sm border-0 h-100 card-hover">
      <div class="card-body p-4">
        <h2 class="h6 fw-bold mb-3"><i class="bi bi-activity me-2"></i>Recent Activity</h2>
        <?php if ($recentResults === []): ?>
          <?php
          render_empty_state([
            'icon'    => 'bi-activity',
            'title'   => 'No recent activity',
            'message' => 'Complete a test to see your history and trends here.',
          ]);
          ?>
        <?php else: ?>
          <ul class="list-group list-group-flush">
            <?php foreach ($recentResults as $row): ?>
              <li class="list-group-item px-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                  <span class="fw-semibold"><?= htmlspecialchars($row['test_title'], ENT_QUOTES, 'UTF-8') ?></span>
                  <span class="text-muted small d-block">
                    Score <?= (int) $row['score'] ?> · <?= htmlspecialchars($row['interpretation'], ENT_QUOTES, 'UTF-8') ?>
                  </span>
                </div>
                <span class="text-muted small">
                  <?= htmlspecialchars(date('M j, Y', strtotime($row['created_at'])), ENT_QUOTES, 'UTF-8') ?>
                </span>
              </li>
            <?php endforeach; ?>
          </ul>
          <a href="<?= htmlspecialchars(app_url('user/results.php'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-link px-0 mt-2">View all results</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php
if ($testsTaken > 0) {
  $testsLabelsJson = json_encode($testsChart['labels'], JSON_THROW_ON_ERROR);
  $testsValuesJson = json_encode($testsChart['values'], JSON_THROW_ON_ERROR);
  $scoresLabelsJson = json_encode($scoresChart['labels'], JSON_THROW_ON_ERROR);
  $scoresValuesJson = json_encode($scoresChart['values'], JSON_THROW_ON_ERROR);

  $inlineScripts = <<<HTML
<script>
document.addEventListener('DOMContentLoaded', function () {
    var palette = window.EmotimateCharts.greenPalette;
    var base = window.EmotimateCharts.defaults();

    new Chart(document.getElementById('userTestsChart'), {
        type: 'bar',
        data: {
            labels: {$testsLabelsJson},
            datasets: [{
                label: 'Completions',
                data: {$testsValuesJson},
                backgroundColor: palette[0],
                borderRadius: 8
            }]
        },
        options: Object.assign({}, base, { plugins: { legend: { display: false } } })
    });

    new Chart(document.getElementById('userScoresChart'), {
        type: 'line',
        data: {
            labels: {$scoresLabelsJson},
            datasets: [{
                label: 'Score',
                data: {$scoresValuesJson},
                borderColor: palette[0],
                backgroundColor: 'rgba(74, 143, 111, 0.15)',
                fill: true,
                tension: 0.35,
                pointRadius: 4
            }]
        },
        options: base
    });
});
</script>
HTML;
}

require_once __DIR__ . '/../includes/user_layout_end.php';
