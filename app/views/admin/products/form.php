<?php $product = $product ?? []; ?>
<h1 class="h3 mb-3"><?= e($title) ?></h1>
<?php foreach ($errors as $error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endforeach; ?>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card"><div class="card-body">
                <div class="mb-3"><label class="form-label">Name</label><input class="form-control" name="name" value="<?= e($product['name'] ?? '') ?>" required></div>
                <div class="mb-3"><label class="form-label">Slug</label><input class="form-control" name="slug" value="<?= e($product['slug'] ?? '') ?>" required></div>
                <div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="6"><?= e($product['description'] ?? '') ?></textarea></div>
                <div class="mb-3"><label class="form-label">LINE URL</label><input class="form-control" name="line_url" value="<?= e($product['line_url'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label">SEO Title</label><input class="form-control" name="seo_title" value="<?= e($product['seo_title'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label">SEO Description</label><textarea class="form-control" name="seo_description"><?= e($product['seo_description'] ?? '') ?></textarea></div>
            </div></div>
        </div>
        <div class="col-lg-4">
            <div class="card"><div class="card-body">
                <div class="mb-3"><label class="form-label">Status</label><select class="form-select" name="status"><option value="published" <?= ($product['status'] ?? 'published') === 'published' ? 'selected' : '' ?>>Published</option><option value="draft" <?= ($product['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option></select></div>
                <div class="form-text mb-3">Select product images from the media picker below. Selected order follows media ID order.</div>
                <div class="mb-3"><label class="form-label">QR Code Media ID</label><input class="form-control" name="qr_code_media_id" value="<?= e($product['qr_code_media_id'] ?? '') ?>"></div>
                <button class="btn btn-primary" type="submit">Save</button> <a class="btn btn-secondary" href="<?= e(admin_url('products')) ?>">Cancel</a>
            </div></div>
        </div>
    </div>
<?php require BASE_PATH . '/app/views/admin/media/_picker_help.php'; ?>
    <button class="btn btn-primary mt-3" type="submit">Save</button>
</form>
