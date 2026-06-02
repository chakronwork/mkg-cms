<h1><?= e($product['name']) ?></h1>
<div class="row g-3 mb-4">
<?php foreach ($images as $image): ?>
    <div class="col-md-4"><img class="img-fluid rounded" src="<?= e(app_config('upload_url') . '/' . $image['filepath']) ?>" alt="<?= e($image['alt_text'] ?? $product['name']) ?>"></div>
<?php endforeach; ?>
</div>
<div class="mb-3"><?= nl2br(e($product['description'] ?? '')) ?></div>
<?php if (!empty($product['line_url'])): ?><a class="btn btn-success" href="<?= e($product['line_url']) ?>">Contact on LINE</a><?php endif; ?>
<?php if (!empty($product['qr_code_path'])): ?><div class="mt-3"><img style="max-width:180px" src="<?= e(app_config('upload_url') . '/' . $product['qr_code_path']) ?>" alt="LINE QR code"></div><?php endif; ?>
