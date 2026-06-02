<h1 class="h3 mb-3">Media Library</h1>
<?php foreach ($errors as $error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endforeach; ?>
<div class="card mb-4">
    <div class="card-body">
        <form method="post" action="<?= e(admin_url('media/upload')) ?>" enctype="multipart/form-data" class="row g-3">
            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
            <div class="col-md-5"><label class="form-label">Image</label><input class="form-control" type="file" name="file" accept="image/*" required></div>
            <div class="col-md-5"><label class="form-label">Alt Text</label><input class="form-control" name="alt_text"></div>
            <div class="col-md-2 d-flex align-items-end"><button class="btn btn-primary w-100" type="submit">Upload</button></div>
        </form>
    </div>
</div>
<div class="row g-3">
<?php foreach ($mediaItems as $item): ?>
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100">
            <img class="card-img-top" src="<?= e(app_config('upload_url') . '/' . $item['filepath']) ?>" alt="<?= e($item['alt_text'] ?? '') ?>">
            <div class="card-body">
                <div class="small text-muted">ID <?= e($item['id']) ?> · <?= e(number_format((int) $item['file_size'] / 1024, 1)) ?> KB</div>
                <div class="small text-truncate"><?= e($item['filename']) ?></div>
                <form method="post" action="<?= e(admin_url('media/' . $item['id'] . '/alt')) ?>" class="mt-2">
                    <input type="hidden" name="csrf_token" value="<?= e(Csrf::generate()) ?>">
                    <input class="form-control form-control-sm" name="alt_text" value="<?= e($item['alt_text'] ?? '') ?>" placeholder="Alt text">
                </form>
                <form method="post" action="<?= e(admin_url('media/' . $item['id'] . '/delete')) ?>" onsubmit="return confirm('Delete this media item?')" class="mt-2">
                    <input type="hidden" name="csrf_token" value="<?= e(Csrf::generate()) ?>">
                    <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
