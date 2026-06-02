<?php
$settings = $settings ?? ['site_name' => 'Mae Klong Graphic'];
$menus = $menus ?? [];
$titleText = trim((string) ($title ?? ''));
$siteName = (string) ($settings['site_name'] ?? 'Mae Klong Graphic');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titleText !== '' ? $titleText . ' | ' . $siteName : $siteName) ?></title>
    <meta name="description" content="<?= e($metaDescription ?? ($settings['site_description'] ?? '')) ?>">
    <?php if (!empty($settings['favicon_path'])): ?><link rel="icon" href="<?= e(app_config('upload_url') . '/' . $settings['favicon_path']) ?>"><?php endif; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fbfaf6; color: #212529; }
        .hero { background: linear-gradient(135deg, #202020, #b8892f); color: #fff; border-radius: 24px; padding: 64px 32px; }
        .card-img-top { height: 220px; object-fit: cover; background: #e9ecef; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= e(base_url()) ?>"><?= e($siteName) ?></a>
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
