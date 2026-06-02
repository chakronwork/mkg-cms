<h1 class="h3 mb-3">สไลด์หน้าแรก</h1>
<?php foreach ($errors as $error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endforeach; ?>

<div class="card mb-4">
    <div class="card-header">เพิ่มสไลด์โปรโมชัน</div>
    <div class="card-body">
        <form method="post" action="<?= e(admin_url('hero-slides')) ?>" class="row g-3">
            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
            <div class="col-md-4">
                <label class="form-label">รูปภาพจาก Media Library</label>
                <select class="form-select" name="media_id" required>
                    <option value="">เลือกรูป</option>
                    <?php foreach ($mediaItems as $item): ?>
                        <option value="<?= e($item['id']) ?>" <?= (int) ($values['media_id'] ?? 0) === (int) $item['id'] ? 'selected' : '' ?>>
                            ID <?= e($item['id']) ?> - <?= e($item['filename']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">อัปโหลดรูปได้ที่เมนูคลังสื่อก่อน แล้วกลับมาเลือกที่นี่</div>
            </div>
            <div class="col-md-4"><label class="form-label">หัวข้อ</label><input class="form-control" name="title" value="<?= e($values['title'] ?? '') ?>"></div>
            <div class="col-md-4"><label class="form-label">ข้อความรอง</label><input class="form-control" name="subtitle" value="<?= e($values['subtitle'] ?? '') ?>"></div>
            <div class="col-md-4"><label class="form-label">URL ปุ่ม</label><input class="form-control" name="link_url" value="<?= e($values['link_url'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label">ข้อความบนปุ่ม</label><input class="form-control" name="link_label" value="<?= e($values['link_label'] ?? '') ?>"></div>
            <div class="col-md-2"><label class="form-label">ลำดับ</label><input class="form-control" type="number" name="sort_order" value="<?= e($values['sort_order'] ?? '10') ?>"></div>
            <div class="col-md-1 form-check d-flex align-items-end pb-2">
                <label><input class="form-check-input" type="checkbox" name="is_active" value="1" <?= array_key_exists('is_active', $values) || $values === [] ? 'checked' : '' ?>> เปิดใช้</label>
            </div>
            <div class="col-md-2 d-flex align-items-end"><button class="btn btn-primary w-100" type="submit">เพิ่มสไลด์</button></div>
        </form>
    </div>
</div>

<div class="row g-3">
<?php foreach ($slides as $slide): ?>
    <div class="col-lg-6">
        <div class="card h-100">
            <img class="card-img-top" src="<?= e(app_config('upload_url') . '/' . $slide['filepath']) ?>" alt="<?= e($slide['alt_text'] ?? '') ?>">
            <div class="card-body">
                <form method="post" action="<?= e(admin_url('hero-slides')) ?>" class="row g-2">
                    <input type="hidden" name="csrf_token" value="<?= e(Csrf::generate()) ?>">
                    <input type="hidden" name="id" value="<?= e($slide['id']) ?>">
                    <div class="col-md-5">
                        <label class="form-label small">รูปภาพ</label>
                        <select class="form-select form-select-sm" name="media_id">
                            <?php foreach ($mediaItems as $item): ?>
                                <option value="<?= e($item['id']) ?>" <?= (int) $slide['media_id'] === (int) $item['id'] ? 'selected' : '' ?>>
                                    ID <?= e($item['id']) ?> - <?= e($item['filename']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-7"><label class="form-label small">หัวข้อ</label><input class="form-control form-control-sm" name="title" value="<?= e($slide['title']) ?>"></div>
                    <div class="col-md-12"><label class="form-label small">ข้อความรอง</label><input class="form-control form-control-sm" name="subtitle" value="<?= e($slide['subtitle']) ?>"></div>
                    <div class="col-md-7"><label class="form-label small">URL ปุ่ม</label><input class="form-control form-control-sm" name="link_url" value="<?= e($slide['link_url']) ?>"></div>
                    <div class="col-md-3"><label class="form-label small">ข้อความปุ่ม</label><input class="form-control form-control-sm" name="link_label" value="<?= e($slide['link_label']) ?>"></div>
                    <div class="col-md-2"><label class="form-label small">ลำดับ</label><input class="form-control form-control-sm" type="number" name="sort_order" value="<?= e($slide['sort_order']) ?>"></div>
                    <div class="col-md-6"><label class="form-check-label"><input class="form-check-input" type="checkbox" name="is_active" value="1" <?= (int) $slide['is_active'] === 1 ? 'checked' : '' ?>> เปิดใช้</label></div>
                    <div class="col-md-6 text-end"><button class="btn btn-sm btn-primary" type="submit">บันทึก</button></div>
                </form>
                <form method="post" action="<?= e(admin_url('hero-slides/' . $slide['id'] . '/delete')) ?>" onsubmit="return confirm('ลบสไลด์นี้?')" class="mt-2 text-end">
                    <input type="hidden" name="csrf_token" value="<?= e(Csrf::generate()) ?>">
                    <button class="btn btn-sm btn-outline-danger" type="submit">ลบ</button>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
