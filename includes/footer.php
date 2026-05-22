<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

if (empty($hideSiteFooter)) {
    require_once __DIR__ . '/site_footer.php';
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php if (!empty($includeChartJs)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<?php endif; ?>
<script src="<?= htmlspecialchars(app_url('assets/js/emotimate.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
<?php if (!empty($pageScripts) && is_array($pageScripts)): ?>
    <?php foreach ($pageScripts as $script): ?>
<script src="<?= htmlspecialchars($script, ENT_QUOTES, 'UTF-8') ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>
<?= $inlineScripts ?? '' ?>
</body>
</html>
