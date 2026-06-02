<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Portfolios</h1>
    <a class="btn btn-primary" href="<?= e(admin_url('portfolios/create')) ?>">Add Portfolio</a>
</div>
<div class="card"><div class="table-responsive"><table class="table mb-0">
<thead><tr><th>Title</th><th>Slug</th><th>Status</th><th>Created</th><th></th></tr></thead><tbody>
<?php foreach ($portfolios as $portfolio): ?>
<tr>
    <td><?= e($portfolio['title']) ?></td><td><?= e($portfolio['slug']) ?></td><td><?= e($portfolio['status']) ?></td><td><?= e($portfolio['created_at']) ?></td>
    <td class="text-end">
        <a class="btn btn-sm btn-primary" href="<?= e(admin_url('portfolios/' . $portfolio['id'] . '/edit')) ?>">Edit</a>
        <form class="d-inline" method="post" action="<?= e(admin_url('portfolios/' . $portfolio['id'] . '/delete')) ?>" onsubmit="return confirm('Delete this portfolio item?')"><input type="hidden" name="csrf_token" value="<?= e(Csrf::generate()) ?>"><button class="btn btn-sm btn-danger">Delete</button></form>
    </td>
</tr>
<?php endforeach; ?>
</tbody></table></div></div>
