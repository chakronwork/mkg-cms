<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark">
<main class="container py-5">
    <div class="card mx-auto" style="max-width: 420px;">
        <div class="card-body">
            <h1 class="h4 mb-3">เข้าสู่ระบบผู้ดูแล</h1>
            <?php foreach ($errors as $error): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endforeach; ?>
            <form method="post" action="<?= e(admin_url('login')) ?>">
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                <div class="mb-3">
                    <label class="form-label">ชื่อผู้ใช้</label>
                    <input class="form-control" name="username" value="<?= e($username) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">รหัสผ่าน</label>
                    <input class="form-control" type="password" name="password" required>
                </div>
                <button class="btn btn-primary w-100" type="submit">เข้าสู่ระบบ</button>
            </form>
        </div>
    </div>
</main>
</body>
</html>
