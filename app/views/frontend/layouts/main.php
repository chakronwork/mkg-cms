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
<html lang="th">
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
        .hero-carousel { background: #111; border-radius: 24px; }
        .hero-slide-img { height: clamp(320px, 48vw, 560px); object-fit: cover; filter: brightness(.62); }
        .hero-carousel .carousel-caption { left: 7%; right: 7%; bottom: 12%; max-width: 680px; }
        .hero-carousel h1 { font-size: clamp(2rem, 5vw, 4.5rem); font-weight: 800; text-shadow: 0 3px 24px rgba(0,0,0,.45); }
        .hero-carousel p { font-size: clamp(1rem, 2vw, 1.35rem); text-shadow: 0 2px 18px rgba(0,0,0,.45); }
        .home-section { margin-top: 56px; }
        .section-kicker { color: #b8892f; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
        .section-title { font-weight: 800; color: #1b1b1b; }
        .service-card { background: #fff; border: 1px solid rgba(33,37,41,.08); border-radius: 22px; padding: 28px; box-shadow: 0 18px 50px rgba(33,37,41,.07); }
        .service-icon { display: inline-flex; align-items: center; justify-content: center; width: 52px; height: 52px; border-radius: 18px; background: #1f2937; color: #f7c66b; font-weight: 800; margin-bottom: 20px; }
        .service-card h3 { font-size: 1.25rem; font-weight: 800; }
        .service-card p { color: #6c757d; margin-bottom: 0; }
        .feature-card { border: 0; border-radius: 20px; overflow: hidden; box-shadow: 0 18px 50px rgba(33,37,41,.08); transition: transform .2s ease, box-shadow .2s ease; }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 24px 70px rgba(33,37,41,.14); }
        .card-img-top { height: 220px; object-fit: cover; background: #e9ecef; }
        .site-logo { max-height: 44px; width: auto; }
        .navbar-toggler { border: 0; box-shadow: none !important; }
        @media (max-width: 991.98px) {
            .navbar-nav { padding-top: 14px; gap: 4px; }
            .navbar-nav .nav-link { padding: 10px 0; border-top: 1px solid rgba(33,37,41,.08); }
            .site-logo { max-height: 38px; }
        }
        @media (max-width: 767.98px) {
            main.container { padding-top: 18px !important; }
            .hero { border-radius: 18px; padding: 36px 22px; }
            .hero-slide-img { height: 380px; }
            .hero-carousel { border-radius: 18px; }
            .hero-carousel .carousel-inner { border-radius: 18px !important; }
            .hero-carousel .carousel-caption { left: 24px; right: 24px; bottom: 48px; padding: 0; }
            .hero-carousel .carousel-caption h1 { font-size: 2rem; }
            .hero-carousel .carousel-caption p { font-size: 1rem; }
            .home-section { margin-top: 40px; }
            .home-section > .d-flex { align-items: flex-start !important; flex-direction: column; gap: 12px; }
            .service-card { padding: 22px; border-radius: 18px; }
            .card-img-top { height: 190px; }
        }
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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNavbar" aria-controls="publicNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="publicNavbar" class="collapse navbar-collapse">
            <div class="navbar-nav ms-auto">
                <?php foreach ($menus as $menu): ?>
                    <a class="nav-link" target="<?= e($menu['target']) ?>" href="<?= e(base_url($menu['url'])) ?>"><?= e($menu['title']) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</nav>
<main class="container py-4">
    <?= $content ?>
</main>
<footer class="border-top py-4 mt-5">
    <div class="container small text-muted">&copy; <?= e(date('Y')) ?> <?= e($siteName) ?></div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
