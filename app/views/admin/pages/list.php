<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Pages</h1>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Title</th><th>Slug</th><th>Status</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($pages as $page): ?>
                <tr>
                    <td><?= e($page['title']) ?></td>
                    <td><?= e($page['slug']) ?></td>
                    <td><?= e($page['status']) ?></td>
                    <td class="text-end"><a class="btn btn-sm btn-primary" href="<?= e(admin_url('pages/' . $page['id'] . '/edit')) ?>">Edit</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
