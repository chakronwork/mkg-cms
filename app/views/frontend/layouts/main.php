<?php
$settings = $settings ?? ['site_name' => 'Mae Klong Graphic'];
$menus = $menus ?? [];
$titleText = trim((string) ($title ?? ''));
$siteName = (string) ($settings['site_name'] ?? 'Mae Klong Graphic');
$uploadUrl = rtrim((string) app_config('upload_url'), '/');
$logoPath = trim((string) ($settings['logo_path'] ?? ''));
$faviconPath = trim((string) ($settings['favicon_path'] ?? ''));
$logoUrl = $logoPath !== '' ? $uploadUrl . '/' . ltrim($logoPath, '/') : '';
$faviconUrl = $faviconPath !== '' ? $uploadUrl . '/' . ltrim($faviconPath, '/') : '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titleText !== '' ? $titleText . ' | ' . $siteName : $siteName) ?></title>
    <meta name="description" content="<?= e($metaDescription ?? ($settings['site_description'] ?? '')) ?>">
    <?php if ($faviconUrl !== ''): ?><link rel="icon" href="<?= e($faviconUrl) ?>"><?php endif; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fbfaf6; color: #212529; }
        .hero { background: linear-gradient(135deg, #202020, #b8892f); color: #fff; border-radius: 24px; padding: 64px 32px; }
        .card-img-top { height: 220px; object-fit: cover; background: #e9ecef; }
        .site-logo { max-height: 44px; width: auto; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?= e(base_url()) ?>">
            <?php if ($logoUrl !== ''): ?>
                <img class="site-logo" src="<?= e($logoUrl) ?>" alt="<?= e($siteName) ?>">
            <?php else: ?>
                <?= e($siteName) ?>
            <?php endif; ?>
        </a>
        <div class="navbar-nav ms-auto">
            <?php foreach ($menus as $menu): ?>
                <a class="nav-link" target="<?= e($menu['target']) ?>" href="<?= e(base_url($menu['url'])) ?>"><?= e($menu['title']) ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</nav>
<main class="container py-4">
    <?= $content ?>
</main>
<footer class="border-top py-4 mt-5">
    <div class="container small text-muted">&copy; <?= e(date('Y')) ?> <?= e($siteName) ?></div>
</footer>
</body>
</html>
