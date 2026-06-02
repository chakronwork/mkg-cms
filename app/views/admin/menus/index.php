<h1 class="h3 mb-3">Menus</h1>
<?php foreach ($errors as $error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endforeach; ?>
<div class="card mb-4"><div class="card-body">
    <form method="post" action="<?= e(admin_url('menus')) ?>" class="row g-2">
        <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
        <div class="col-md-3"><input class="form-control" name="title" placeholder="Title" required></div>
        <div class="col-md-3"><input class="form-control" name="url" placeholder="/url" required></div>
        <div class="col-md-2"><select class="form-select" name="target"><option value="_self">Same tab</option><option value="_blank">New tab</option></select></div>
        <div class="col-md-2"><input class="form-control" type="number" name="sort_order" value="0"></div>
        <div class="col-md-1 form-check d-flex align-items-center"><input class="form-check-input" type="checkbox" name="is_active" value="1" checked></div>
        <div class="col-md-1"><button class="btn btn-primary w-100">Add</button></div>
    </form>
</div></div>
<div class="card"><div class="table-responsive"><table class="table mb-0">
<thead><tr><th>Title</th><th>URL</th><th>Target</th><th>Sort</th><th>Active</th><th></th></tr></thead><tbody>
<?php foreach ($menus as $menu): ?>
<tr>
    <form method="post" action="<?= e(admin_url('menus')) ?>">
        <input type="hidden" name="csrf_token" value="<?= e(Csrf::generate()) ?>">
        <input type="hidden" name="id" value="<?= e($menu['id']) ?>">
        <td><input class="form-control form-control-sm" name="title" value="<?= e($menu['title']) ?>"></td>
        <td><input class="form-control form-control-sm" name="url" value="<?= e($menu['url']) ?>"></td>
        <td><select class="form-select form-select-sm" name="target"><option value="_self" <?= $menu['target'] === '_self' ? 'selected' : '' ?>>Same</option><option value="_blank" <?= $menu['target'] === '_blank' ? 'selected' : '' ?>>New</option></select></td>
        <td><input class="form-control form-control-sm" type="number" name="sort_order" value="<?= e($menu['sort_order']) ?>"></td>
        <td><input class="form-check-input" type="checkbox" name="is_active" value="1" <?= (int) $menu['is_active'] === 1 ? 'checked' : '' ?>></td>
        <td class="text-end"><button class="btn btn-sm btn-primary">Save</button>
    </form>
    <form class="d-inline" method="post" action="<?= e(admin_url('menus/' . $menu['id'] . '/delete')) ?>" onsubmit="return confirm('Delete this menu item?')"><input type="hidden" name="csrf_token" value="<?= e(Csrf::generate()) ?>"><button class="btn btn-sm btn-danger">Delete</button></form></td>
</tr>
<?php endforeach; ?>
</tbody></table></div></div>
