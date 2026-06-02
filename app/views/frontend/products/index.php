<h1 class="mb-3">Products</h1>
<div class="row g-3">
<?php foreach ($products as $product): ?>
    <div class="col-md-4">
        <a class="card h-100 text-decoration-none text-dark" href="<?= e(base_url('products/' . $product['slug'])) ?>">
            <?php if (!empty($product['thumbnail_path'])): ?><img class="card-img-top" src="<?= e(app_config('upload_url') . '/' . $product['thumbnail_path']) ?>" alt="<?= e($product['name']) ?>"><?php endif; ?>
            <div class="card-body"><h2 class="h5"><?= e($product['name']) ?></h2></div>
        </a>
    </div>
<?php endforeach; ?>
</div>
