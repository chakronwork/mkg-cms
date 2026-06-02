<h1>ติดต่อเรา</h1>
<?php foreach ($sections as $section): ?><div class="mb-4"><?= $section['content'] ?></div><?php endforeach; ?>
<div class="row g-4">
    <div class="col-md-5">
        <div class="card"><div class="card-body">
            <p><strong>Phone:</strong> <?= e($settings['phone'] ?? '') ?></p>
            <p><strong>Email:</strong> <?= e($settings['email'] ?? '') ?></p>
            <p><strong>Address:</strong><br><?= nl2br(e($settings['address'] ?? '')) ?></p>
            <?php if (!empty($settings['line_url'])): ?><a class="btn btn-success" href="<?= e(normalize_line_url($settings['line_url'])) ?>">Contact on LINE</a><?php endif; ?>
        </div></div>
    </div>
    <div class="col-md-7">
        <?= $settings['google_map_embed'] ?? '' ?>
    </div>
</div>
