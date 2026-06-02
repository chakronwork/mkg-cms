<h1><?= e($portfolio['title']) ?></h1>
<div class="mb-3"><?= $portfolio['description'] ?></div>
<div class="row g-3">
<?php foreach ($images as $image): ?>
    <div class="col-md-4"><img class="img-fluid rounded" src="<?= e(app_config('upload_url') . '/' . $image['filepath']) ?>" alt="<?= e($image['alt_text'] ?? $portfolio['title']) ?>"></div>
<?php endforeach; ?>
</div>
