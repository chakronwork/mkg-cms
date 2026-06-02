<div class="card mt-3">
    <div class="card-header">Media Picker</div>
    <div class="card-body">
        <div class="row g-2">
            <?php foreach ($mediaItems as $item): ?>
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="border rounded p-2 h-100">
                        <img class="img-fluid mb-1" src="<?= e(app_config('upload_url') . '/' . $item['filepath']) ?>" alt="<?= e($item['alt_text'] ?? '') ?>">
                        <label class="small d-block">
                            <input type="checkbox" name="media_ids[]" value="<?= e($item['id']) ?>" <?= in_array((int) $item['id'], array_map('intval', $selectedMedia ?? []), true) ? 'checked' : '' ?>>
                            ID: <?= e($item['id']) ?>
                        </label>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
