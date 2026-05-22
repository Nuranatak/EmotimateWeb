<?php

declare(strict_types=1);

$footerYear = (int) date('Y');
?>

<footer class="site-footer mt-auto">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-4">
                <a href="<?= htmlspecialchars(app_url(), ENT_QUOTES, 'UTF-8') ?>" class="footer-brand d-inline-flex align-items-center gap-2 text-decoration-none mb-3">
                    <i class="bi bi-heart-pulse"></i>
                    <span class="fw-bold">EMOTIMATE</span>
                </a>
                <p class="footer-text small mb-3">
                    A web-based psychological wellness platform for structured self-assessments,
                    clear scoring, and meaningful insights — built for education and research.
                </p>
                <div class="footer-social d-flex gap-2">
                    <a href="#" class="footer-social-link" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="footer-social-link" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="footer-social-link" aria-label="GitHub"><i class="bi bi-github"></i></a>
                    <a href="mailto:support@emotimate.local" class="footer-social-link" aria-label="Email"><i class="bi bi-envelope"></i></a>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <h3 class="footer-heading h6">Platform</h3>
                <ul class="list-unstyled footer-links small">
                    <li><a href="<?= htmlspecialchars(app_url(), ENT_QUOTES, 'UTF-8') ?>">Home</a></li>
                    <li><a href="<?= htmlspecialchars(app_url('tests/index.php'), ENT_QUOTES, 'UTF-8') ?>">Tests</a></li>
                    <li><a href="<?= htmlspecialchars(app_url('auth/login.php'), ENT_QUOTES, 'UTF-8') ?>">Login</a></li>
                    <li><a href="<?= htmlspecialchars(app_url('auth/register.php'), ENT_QUOTES, 'UTF-8') ?>">Register</a></li>
                </ul>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <h3 class="footer-heading h6">Account</h3>
                <ul class="list-unstyled footer-links small">
                    <li><a href="<?= htmlspecialchars(app_url('user/dashboard.php'), ENT_QUOTES, 'UTF-8') ?>">Dashboard</a></li>
                    <li><a href="<?= htmlspecialchars(app_url('user/results.php'), ENT_QUOTES, 'UTF-8') ?>">My Results</a></li>
                    <li><a href="<?= htmlspecialchars(app_url('user/profile.php'), ENT_QUOTES, 'UTF-8') ?>">Profile</a></li>
                </ul>
            </div>
            <div class="col-md-4 col-lg-4">
                <h3 class="footer-heading h6">Contact</h3>
                <ul class="list-unstyled footer-contact small mb-0">
                    <li><i class="bi bi-envelope me-2"></i> support@emotimate.local</li>
                    <li><i class="bi bi-geo-alt me-2"></i> University Web Programming Project</li>
                    <li><i class="bi bi-shield-check me-2"></i> Secure sessions &amp; hashed passwords</li>
                </ul>
            </div>
        </div>
        <hr class="footer-divider my-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 small footer-bottom">
            <span>&copy; <?= $footerYear ?> EMOTIMATE. All rights reserved.</span>
            <span class="text-white-50">Psychological wellness · Built with Bootstrap 5</span>
        </div>
    </div>
</footer>
