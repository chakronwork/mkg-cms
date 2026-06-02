<?php $title = $title ?? 'Admin'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= e(admin_url()) ?>">MKG CMS</a>
        <div class="navbar-nav me-auto">
            <a class="nav-link" href="<?= e(admin_url('pages')) ?>">Pages</a>
            <a class="nav-link" href="<?= e(admin_url('products')) ?>">Products</a>
            <a class="nav-link" href="<?= e(admin_url('portfolios')) ?>">Portfolio</a>
            <a class="nav-link" href="<?= e(admin_url('media')) ?>">Media</a>
            <a class="nav-link" href="<?= e(admin_url('menus')) ?>">Menus</a>
            <a class="nav-link" href="<?= e(admin_url('settings')) ?>">Settings</a>
        </div>
        <form method="post" action="<?= e(admin_url('logout')) ?>">
            <input type="hidden" name="csrf_token" value="<?= e(Csrf::generate()) ?>">
            <button class="btn btn-outline-light btn-sm" type="submit">Logout</button>
        </form>
    </div>
</nav>
<main class="container py-4">
    <?= $content ?>
</main>
<script>
tinymce.init({ selector: '.tinymce', menubar: false, height: 260 });
</script>
</body>
</html>
