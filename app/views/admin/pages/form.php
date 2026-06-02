<h1 class="h3 mb-3">Edit Page</h1>
<?php foreach ($errors as $error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endforeach; ?>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
    <div class="card mb-3"><div class="card-body">
        <div class="mb-3"><label class="form-label">Title</label><input class="form-control" name="title" value="<?= e($page['title'] ?? '') ?>" required></div>
        <div class="mb-3"><label class="form-label">SEO Title</label><input class="form-control" name="seo_title" value="<?= e($page['seo_title'] ?? '') ?>"></div>
        <div class="mb-3"><label class="form-label">SEO Description</label><textarea class="form-control" name="seo_description"><?= e($page['seo_description'] ?? '') ?></textarea></div>
        <div class="mb-3"><label class="form-label">Status</label><select class="form-select" name="status"><option value="published" <?= ($page['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option><option value="draft" <?= ($page['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option></select></div>
    </div></div>
    <?php foreach ($sections as $section): ?>
        <div class="card mb-3"><div class="card-body">
            <h2 class="h5"><?= e($section['section_key']) ?></h2>
            <input class="form-control mb-2" name="sections[<?= e($section['id']) ?>][section_name]" value="<?= e($section['section_name']) ?>">
            <input class="form-control mb-2" type="number" name="sections[<?= e($section['id']) ?>][sort_order]" value="<?= e($section['sort_order']) ?>">
            <textarea class="form-control tinymce" name="sections[<?= e($section['id']) ?>][content]"><?= e($section['content'] ?? '') ?></textarea>
        </div></div>
    <?php endforeach; ?>
    <button class="btn btn-primary" type="submit">Save</button>
    <a class="btn btn-secondary" href="<?= e(admin_url('pages')) ?>">Cancel</a>
</form>
