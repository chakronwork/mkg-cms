<h1 class="h3 mb-3">Site Settings</h1>
<?php foreach ($errors as $error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endforeach; ?>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
    <div class="card"><div class="card-body">
        <div class="mb-3"><label class="form-label">Site Name</label><input class="form-control" name="site_name" value="<?= e($settings['site_name'] ?? '') ?>" required></div>
        <div class="mb-3"><label class="form-label">Site Description</label><textarea class="form-control" name="site_description"><?= e($settings['site_description'] ?? '') ?></textarea></div>
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Phone</label><input class="form-control" name="phone" value="<?= e($settings['phone'] ?? '') ?>"></div>
            <div class="col-md-6"><label class="form-label">Email</label><input class="form-control" name="email" value="<?= e($settings['email'] ?? '') ?>"></div>
            <div class="col-md-6">
                <label class="form-label">LINE URL</label>
                <input class="form-control" name="line_url" value="<?= e($settings['line_url'] ?? '') ?>">
                <div class="form-text">ใส่ URL เต็ม เช่น https://lin.ee/... หรือ LINE ID เช่น @w864</div>
            </div>
            <div class="col-md-6"><label class="form-label">Facebook URL</label><input class="form-control" name="facebook_url" value="<?= e($settings['facebook_url'] ?? '') ?>"></div>
            <div class="col-md-6"><label class="form-label">Logo Media ID</label><input class="form-control" name="logo_media_id" value="<?= e($settings['logo_media_id'] ?? '') ?>"></div>
            <div class="col-md-6"><label class="form-label">Favicon Media ID</label><input class="form-control" name="favicon_media_id" value="<?= e($settings['favicon_media_id'] ?? '') ?>"></div>
        </div>
        <div class="mb-3 mt-3"><label class="form-label">Address</label><textarea class="form-control" name="address"><?= e($settings['address'] ?? '') ?></textarea></div>
        <div class="mb-3"><label class="form-label">Google Map Embed</label><textarea class="form-control" name="google_map_embed"><?= e($settings['google_map_embed'] ?? '') ?></textarea></div>
        <button class="btn btn-primary" type="submit">Save Settings</button>
    </div></div>
</form>
<?php require BASE_PATH . '/app/views/admin/media/_picker_help.php'; ?>
