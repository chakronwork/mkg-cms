<?php
$title = $title ?? 'ผู้ดูแลระบบ';
$currentPath = (string) parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
$adminBase = rtrim((string) parse_url(admin_url(), PHP_URL_PATH), '/');
$isAdminSection = static function (string $segment) use ($currentPath, $adminBase): bool {
    return str_starts_with($currentPath, $adminBase . '/' . trim($segment, '/'));
};
$tinymceScriptUrl = (string) app_config('tinymce_script_url', 'https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js');
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <meta name="color-scheme" content="light">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="<?= e($tinymceScriptUrl) ?>" referrerpolicy="origin"></script>
    <style>
        body { background: #f4f6f9; }
        .admin-shell { min-height: 100vh; }
        .admin-sidebar {
            width: 260px;
            background: #111827;
            color: #e5e7eb;
        }
        .admin-sidebar .nav-link { color: #cbd5e1; }
        .admin-sidebar .nav-link.active,
        .admin-sidebar .nav-link:hover { color: #fff; background: rgba(255,255,255,.08); }
        .admin-main { flex: 1; min-width: 0; }
        .admin-topbar { background: #fff; border-bottom: 1px solid #e5e7eb; }
        @media (max-width: 991.98px) {
            .admin-shell { display: block !important; }
            .admin-sidebar { width: 100%; min-height: auto; position: sticky; top: 0; z-index: 1020; }
            .admin-sidebar .mb-4 { margin-bottom: 1rem !important; }
            .admin-sidebar nav { flex-direction: row !important; overflow-x: auto; padding-bottom: .25rem; scrollbar-width: thin; }
            .admin-sidebar .nav-link { white-space: nowrap; }
            .admin-sidebar form { margin-top: 1rem !important; padding-top: 0 !important; }
            .admin-topbar { padding: 1rem !important; }
            main.container-fluid { padding: 1rem !important; }
        }
        @media (max-width: 575.98px) {
            .admin-sidebar { padding: 1rem !important; }
            .admin-topbar { align-items: flex-start !important; gap: .75rem; flex-direction: column; }
            .table-responsive { font-size: .92rem; }
        }
    </style>
</head>
<body>
<div class="admin-shell d-flex">
    <aside class="admin-sidebar p-3 d-flex flex-column">
        <div class="mb-4">
            <div class="small text-uppercase text-secondary">MKG CMS</div>
            <div class="h5 mb-0 text-white">แผงผู้ดูแล</div>
        </div>
        <nav class="nav nav-pills flex-column gap-1">
            <a class="nav-link <?= $currentPath === $adminBase || $currentPath === $adminBase . '/' || $isAdminSection('dashboard') ? 'active' : '' ?>" href="<?= e(admin_url()) ?>">แดชบอร์ด</a>
            <a class="nav-link <?= $isAdminSection('pages') ? 'active' : '' ?>" href="<?= e(admin_url('pages')) ?>">หน้าเว็บ</a>
            <a class="nav-link <?= $isAdminSection('hero-slides') ? 'active' : '' ?>" href="<?= e(admin_url('hero-slides')) ?>">สไลด์หน้าแรก</a>
            <a class="nav-link <?= $isAdminSection('products') ? 'active' : '' ?>" href="<?= e(admin_url('products')) ?>">สินค้า</a>
            <a class="nav-link <?= $isAdminSection('portfolios') ? 'active' : '' ?>" href="<?= e(admin_url('portfolios')) ?>">ผลงาน</a>
            <a class="nav-link <?= $isAdminSection('media') ? 'active' : '' ?>" href="<?= e(admin_url('media')) ?>">คลังสื่อ</a>
            <a class="nav-link <?= $isAdminSection('menus') ? 'active' : '' ?>" href="<?= e(admin_url('menus')) ?>">เมนู</a>
            <a class="nav-link <?= $isAdminSection('settings') ? 'active' : '' ?>" href="<?= e(admin_url('settings')) ?>">ตั้งค่าเว็บไซต์</a>
        </nav>
        <form method="post" action="<?= e(admin_url('logout')) ?>" class="mt-auto pt-3">
            <input type="hidden" name="csrf_token" value="<?= e(Csrf::generate()) ?>">
            <button class="btn btn-outline-light w-100" type="submit">ออกจากระบบ</button>
        </form>
    </aside>
    <div class="admin-main">
        <header class="admin-topbar px-4 py-3 d-flex justify-content-between align-items-center">
            <div>
                <div class="fw-semibold"><?= e($title) ?></div>
                <div class="text-muted small">หน้าจัดการระบบ</div>
            </div>
            <span class="badge text-bg-dark">MKG CMS</span>
        </header>
        <main class="container-fluid py-4">
            <?= $content ?>
        </main>
    </div>
</div>
<script>
if (window.tinymce) {
    tinymce.init({ selector: '.tinymce', menubar: false, height: 260 });
}
</script>
</body>
</html>
