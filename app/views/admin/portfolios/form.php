<?php $portfolio = $portfolio ?? []; ?>
<h1 class="h3 mb-3"><?= e($title) ?></h1>
<?php foreach ($errors as $error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endforeach; ?>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
    <div class="mb-3"><label class="form-label">Title</label><input class="form-control" name="title" value="<?= e($portfolio['title'] ?? '') ?>" required></div>
    <div class="mb-3"><label class="form-label">Slug</label><input class="form-control" name="slug" value="<?= e($portfolio['slug'] ?? '') ?>" required></div>
    <div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="6"><?= e($portfolio['description'] ?? '') ?></textarea></div>
    <div class="mb-3"><label class="form-label">Status</label><select class="form-select" name="status"><option value="published" <?= ($portfolio['status'] ?? 'published') === 'published' ? 'selected' : '' ?>>Published</option><option value="draft" <?= ($portfolio['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option></select></div>
    <div class="form-text mb-3">Select portfolio images from the media picker below. Selected order follows media ID order.</div>
    <div class="mb-3"><label class="form-label">SEO Title</label><input class="form-control" name="seo_title" value="<?= e($portfolio['seo_title'] ?? '') ?>"></div>
    <div class="mb-3"><label class="form-label">SEO Description</label><textarea class="form-control" name="seo_description"><?= e($portfolio['seo_description'] ?? '') ?></textarea></div>
    <?php require BASE_PATH . '/app/views/admin/media/_picker_help.php'; ?>
    <button class="btn btn-primary mt-3" type="submit">Save</button> <a class="btn btn-secondary mt-3" href="<?= e(admin_url('portfolios')) ?>">Cancel</a>
</form>
