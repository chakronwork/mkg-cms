<?php
$homeExcerpt = static function (mixed $text): string {
    $text = trim((string) $text);
    if (function_exists('mb_strimwidth')) {
        return mb_strimwidth($text, 0, 110, '...');
    }

    return strlen($text) > 110 ? substr($text, 0, 107) . '...' : $text;
};
?>
<?php if (!empty($heroSlides)): ?>
<section id="homeHeroCarousel" class="carousel slide hero-carousel mb-4" data-bs-ride="carousel" data-bs-interval="4500">
    <div class="carousel-indicators">
        <?php foreach ($heroSlides as $index => $slide): ?>
            <button type="button" data-bs-target="#homeHeroCarousel" data-bs-slide-to="<?= e($index) ?>" class="<?= $index === 0 ? 'active' : '' ?>" aria-label="Slide <?= e($index + 1) ?>"></button>
        <?php endforeach; ?>
    </div>
    <div class="carousel-inner rounded-4 overflow-hidden">
        <?php foreach ($heroSlides as $index => $slide): ?>
            <?php
            $linkUrl = trim((string) ($slide['link_url'] ?? ''));
            $href = $linkUrl === '' ? '' : (preg_match('/^https?:\/\//i', $linkUrl) ? $linkUrl : base_url($linkUrl));
            ?>
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                <img class="d-block w-100 hero-slide-img" src="<?= e(app_config('upload_url') . '/' . $slide['filepath']) ?>" alt="<?= e($slide['alt_text'] ?: ($slide['title'] ?? '')) ?>">
                <div class="carousel-caption text-start">
                    <?php if (trim((string) ($slide['title'] ?? '')) !== ''): ?><h1><?= e($slide['title']) ?></h1><?php endif; ?>
                    <?php if (trim((string) ($slide['subtitle'] ?? '')) !== ''): ?><p><?= e($slide['subtitle']) ?></p><?php endif; ?>
                    <?php if ($href !== ''): ?><a class="btn btn-warning fw-semibold" href="<?= e($href) ?>"><?= e($slide['link_label'] ?: 'ดูรายละเอียด') ?></a><?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#homeHeroCarousel" data-bs-slide="prev" aria-label="Previous slide">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#homeHeroCarousel" data-bs-slide="next" aria-label="Next slide">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </button>
</section>
<?php else: ?>
    <section class="hero mb-4">
        <?php foreach ($sections as $section): ?>
            <div><?= $section['content'] ?></div>
        <?php endforeach; ?>
    </section>
<?php endif; ?>
<section class="home-section">
    <div class="d-flex justify-content-between align-items-end mb-3">
        <div>
            <p class="section-kicker mb-1">Services</p>
            <h2 class="section-title mb-0">บริการของเรา</h2>
        </div>
        <a class="btn btn-outline-dark btn-sm" href="<?= e(base_url('products')) ?>">ดูสินค้า</a>
    </div>
    <div class="row g-3">
        <div class="col-md-4">
            <article class="service-card h-100">
                <div class="service-icon">01</div>
                <h3>ป้ายหน้าร้าน</h3>
                <p>งานป้ายสำหรับหน้าร้าน อีเวนต์ และจุดขาย ออกแบบให้เห็นชัดและเหมาะกับพื้นที่จริง</p>
            </article>
        </div>
        <div class="col-md-4">
            <article class="service-card h-100">
                <div class="service-icon">02</div>
                <h3>สติ๊กเกอร์และไวนิล</h3>
                <p>สติ๊กเกอร์ ฉลากสินค้า ไวนิล แบนเนอร์ และงานตกแต่งกระจกสำหรับธุรกิจท้องถิ่น</p>
            </article>
        </div>
        <div class="col-md-4">
            <article class="service-card h-100">
                <div class="service-icon">03</div>
                <h3>งานพิมพ์และโปรโมชัน</h3>
                <p>งานพิมพ์พร้อมใช้งานสำหรับโปรโมชัน ป้ายโฆษณา และสื่อประชาสัมพันธ์หน้าร้าน</p>
            </article>
        </div>
    </div>
</section>

<?php if (!empty($featuredProducts)): ?>
<section class="home-section">
    <div class="d-flex justify-content-between align-items-end mb-3">
        <div>
            <p class="section-kicker mb-1">Products</p>
            <h2 class="section-title mb-0">สินค้าแนะนำ</h2>
        </div>
        <a class="btn btn-outline-dark btn-sm" href="<?= e(base_url('products')) ?>">ดูทั้งหมด</a>
    </div>
    <div class="row g-3">
        <?php foreach ($featuredProducts as $product): ?>
            <div class="col-md-4">
                <a class="card feature-card h-100 text-decoration-none text-dark" href="<?= e(base_url('products/' . $product['slug'])) ?>">
                    <?php if (!empty($product['thumbnail_path'])): ?>
                        <img class="card-img-top" src="<?= e(app_config('upload_url') . '/' . $product['thumbnail_path']) ?>" alt="<?= e($product['name']) ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h3 class="h5"><?= e($product['name']) ?></h3>
                        <p class="text-muted mb-0"><?= e($homeExcerpt($product['description'] ?? '')) ?></p>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($latestPortfolios)): ?>
<section class="home-section">
    <div class="d-flex justify-content-between align-items-end mb-3">
        <div>
            <p class="section-kicker mb-1">Portfolio</p>
            <h2 class="section-title mb-0">ผลงานล่าสุด</h2>
        </div>
        <a class="btn btn-outline-dark btn-sm" href="<?= e(base_url('portfolio')) ?>">ดูผลงาน</a>
    </div>
    <div class="row g-3">
        <?php foreach ($latestPortfolios as $portfolio): ?>
            <div class="col-md-4">
                <a class="card feature-card h-100 text-decoration-none text-dark" href="<?= e(base_url('portfolio/' . $portfolio['slug'])) ?>">
                    <?php if (!empty($portfolio['thumbnail_path'])): ?>
                        <img class="card-img-top" src="<?= e(app_config('upload_url') . '/' . $portfolio['thumbnail_path']) ?>" alt="<?= e($portfolio['title']) ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h3 class="h5"><?= e($portfolio['title']) ?></h3>
                        <p class="text-muted mb-0"><?= e($homeExcerpt($portfolio['description'] ?? '')) ?></p>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
