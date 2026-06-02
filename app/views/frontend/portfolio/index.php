<h1 class="mb-3">Portfolio</h1>
<div class="row g-3">
<?php foreach ($portfolios as $portfolio): ?>
    <div class="col-md-4">
        <a class="card h-100 text-decoration-none text-dark" href="<?= e(base_url('portfolio/' . $portfolio['slug'])) ?>">
            <?php if (!empty($portfolio['thumbnail_path'])): ?><img class="card-img-top" src="<?= e(app_config('upload_url') . '/' . $portfolio['thumbnail_path']) ?>" alt="<?= e($portfolio['title']) ?>"><?php endif; ?>
            <div class="card-body"><h2 class="h5"><?= e($portfolio['title']) ?></h2></div>
        </a>
    </div>
<?php endforeach; ?>
</div>
