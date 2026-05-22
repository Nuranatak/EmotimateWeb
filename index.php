<?php

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/stats_helpers.php';
require_once __DIR__ . '/includes/session.php';

session_start_safe();

$pageTitle = 'EMOTIMATE — Psychological Wellness Platform';
$bodyClass = 'landing-body';
$extraStylesheets = [asset_url('css/landing.css')];

$platformStats = [
    'users'   => count_table_rows($conn, 'users'),
    'tests'   => count_table_rows($conn, 'tests'),
    'results' => count_table_rows($conn, 'results'),
];

$isLoggedIn = isset($_SESSION['user_id']);
$ctaPrimary = $isLoggedIn ? app_url('tests/index.php') : app_url('auth/register.php');
$ctaPrimaryLabel = $isLoggedIn ? 'Take a Test' : 'Create Free Account';
$ctaSecondary = $isLoggedIn ? app_url('user/dashboard.php') : app_url('auth/login.php');
$ctaSecondaryLabel = $isLoggedIn ? 'My Dashboard' : 'Sign In';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="landing-hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 animate-in">
                <span class="landing-badge">
                    <i class="bi bi-heart-pulse"></i> Psychological wellness platform
                </span>
                <h1 class="landing-headline mb-3">
                    Understand your mind with <span class="text-white-50">clarity</span> and confidence
                </h1>
                <p class="landing-subhead mb-4">
                    EMOTIMATE delivers structured psychological assessments, instant scoring,
                    and meaningful interpretations — designed for students, researchers, and wellness seekers.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="<?= htmlspecialchars($ctaPrimary, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-light btn-lg px-4">
                        <i class="bi bi-rocket-takeoff me-2"></i><?= htmlspecialchars($ctaPrimaryLabel, ENT_QUOTES, 'UTF-8') ?>
                    </a>
                    <a href="<?= htmlspecialchars($ctaSecondary, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-light btn-lg px-4">
                        <?= htmlspecialchars($ctaSecondaryLabel, ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </div>
                <p class="small text-white-50 mb-0">
                    <i class="bi bi-shield-check me-1"></i> Secure authentication · Private results · Research-ready
                </p>
            </div>
            <div class="col-lg-6">
                <div class="landing-hero-visual">
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="landing-stat-pill">
                                <strong><?= $platformStats['users'] ?></strong>
                                <span class="small text-muted">Users</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="landing-stat-pill">
                                <strong><?= $platformStats['tests'] ?></strong>
                                <span class="small text-muted">Tests</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="landing-stat-pill">
                                <strong><?= $platformStats['results'] ?></strong>
                                <span class="small text-muted">Results</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 p-3 rounded-3" style="background: rgba(255,255,255,0.08);">
                        <p class="small mb-2 text-white-50 text-uppercase fw-semibold">Sample insight</p>
                        <p class="mb-2 fw-semibold">Stress Assessment — Medium level</p>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-light" style="width: 58%"></div>
                        </div>
                        <p class="small text-white-50 mt-2 mb-0">Likert-based scoring with instant interpretation</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="about" class="section-padding">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="section-title h2 mb-3">About EMOTIMATE</h2>
                <p class="section-lead mb-4">
                    EMOTIMATE is a web-based psychological testing platform built for university coursework and
                    real-world wellness education. It combines validated-style Likert questionnaires with
                    automated scoring and clear, actionable feedback.
                </p>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Structured assessments with consistent scoring</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Personal result history and progress tracking</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Admin tools for test and question management</li>
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm card-hover">
                    <div class="card-body p-4 p-lg-5">
                        <h3 class="h5 fw-bold mb-3"><i class="bi bi-brain me-2 text-success"></i>Our mission</h3>
                        <p class="text-muted mb-0">
                            Make psychological self-assessment accessible, transparent, and educational —
                            helping users reflect on their wellbeing while giving administrators
                            powerful tools to manage content at scale.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="features" class="section-padding bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title h2 mb-2">Platform Features</h2>
            <p class="section-lead mx-auto">Everything you need for a professional psychological testing experience.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="feature-card p-4">
                    <div class="feature-icon-wrap"><i class="bi bi-clipboard2-pulse"></i></div>
                    <h3 class="h5 fw-bold">Validated-style Tests</h3>
                    <p class="text-muted small mb-0">Likert-scale questionnaires with automated sum scoring and tiered interpretations.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card p-4">
                    <div class="feature-icon-wrap"><i class="bi bi-graph-up-arrow"></i></div>
                    <h3 class="h5 fw-bold">Visual Analytics</h3>
                    <p class="text-muted small mb-0">Dashboard charts track your test history, scores, and platform-wide activity.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card p-4">
                    <div class="feature-icon-wrap"><i class="bi bi-shield-lock"></i></div>
                    <h3 class="h5 fw-bold">Secure Accounts</h3>
                    <p class="text-muted small mb-0">Password hashing, CSRF protection, and role-based admin access.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card p-4">
                    <div class="feature-icon-wrap"><i class="bi bi-clock-history"></i></div>
                    <h3 class="h5 fw-bold">Result History</h3>
                    <p class="text-muted small mb-0">Review past assessments with scores, levels, and completion dates.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card p-4">
                    <div class="feature-icon-wrap"><i class="bi bi-sliders"></i></div>
                    <h3 class="h5 fw-bold">Admin CMS</h3>
                    <p class="text-muted small mb-0">Create tests, manage questions, and monitor completions from one panel.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card p-4">
                    <div class="feature-icon-wrap"><i class="bi bi-phone"></i></div>
                    <h3 class="h5 fw-bold">Fully Responsive</h3>
                    <p class="text-muted small mb-0">Optimized for mobile, tablet, and desktop — perfect for presentations.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="how-it-works" class="section-padding">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5">
                <h2 class="section-title h2 mb-3">How It Works</h2>
                <p class="section-lead">Three simple steps from registration to meaningful insight.</p>
            </div>
            <div class="col-lg-7">
                <div class="how-step mb-4">
                    <span class="how-step-num">1</span>
                    <h3 class="h5 fw-bold">Create your account</h3>
                    <p class="text-muted mb-0">Register securely and access your personal wellness dashboard.</p>
                </div>
                <div class="how-step mb-4">
                    <span class="how-step-num">2</span>
                    <h3 class="h5 fw-bold">Take an assessment</h3>
                    <p class="text-muted mb-0">Answer each question on a 1–5 Likert scale at your own pace.</p>
                </div>
                <div class="how-step">
                    <span class="how-step-num">3</span>
                    <h3 class="h5 fw-bold">Review your results</h3>
                    <p class="text-muted mb-0">Receive your score, interpretation level, and track progress over time.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding pt-0">
    <div class="container">
        <div class="stats-band p-4 p-md-5 text-center">
            <div class="row g-4">
                <div class="col-md-4">
                    <p class="display-5 fw-bold mb-1"><?= $platformStats['users'] ?>+</p>
                    <p class="mb-0 text-white-50">Registered users</p>
                </div>
                <div class="col-md-4">
                    <p class="display-5 fw-bold mb-1"><?= $platformStats['tests'] ?></p>
                    <p class="mb-0 text-white-50">Available assessments</p>
                </div>
                <div class="col-md-4">
                    <p class="display-5 fw-bold mb-1"><?= $platformStats['results'] ?></p>
                    <p class="mb-0 text-white-50">Completed evaluations</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title h2 mb-2">What Users Say</h2>
            <p class="section-lead mx-auto">Feedback from students and wellness advocates using the platform.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="testimonial-card p-4">
                    <i class="bi bi-quote quote-icon"></i>
                    <p class="mb-3">"The dashboard charts made it easy to see how my stress scores changed week over week."</p>
                    <p class="small fw-semibold mb-0">— Aria M., Psychology Student</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card p-4">
                    <i class="bi bi-quote quote-icon"></i>
                    <p class="mb-3">"Clean interface and instant results — perfect for our final project presentation."</p>
                    <p class="small fw-semibold mb-0">— James K., Web Programming</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card p-4">
                    <i class="bi bi-quote quote-icon"></i>
                    <p class="mb-3">"Admin tools let us add tests and questions without touching the database directly."</p>
                    <p class="small fw-semibold mb-0">— Dr. Lee, Course Instructor</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding pt-0 pb-5">
    <div class="container">
        <div class="cta-section text-center">
            <h2 class="h3 fw-bold mb-3">Ready to begin your wellness journey?</h2>
            <p class="text-muted mb-4 mx-auto" style="max-width: 32rem;">
                Join EMOTIMATE today and take your first psychological assessment in minutes.
            </p>
            <a href="<?= htmlspecialchars($ctaPrimary, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-emotimate btn-lg px-5">
                <?= htmlspecialchars($ctaPrimaryLabel, ENT_QUOTES, 'UTF-8') ?>
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
