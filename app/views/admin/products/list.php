<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Products</h1>
    <a class="btn btn-primary" href="<?= e(admin_url('products/create')) ?>">Add Product</a>
</div>
<div class="card"><div class="table-responsive"><table class="table mb-0">
<thead><tr><th>Name</th><th>Slug</th><th>Status</th><th>Created</th><th></th></tr></thead><tbody>
<?php foreach ($products as $product): ?>
<tr>
    <td><?= e($product['name']) ?></td><td><?= e($product['slug']) ?></td><td><?= e($product['status']) ?></td><td><?= e($product['created_at']) ?></td>
    <td class="text-end">
        <a class="btn btn-sm btn-primary" href="<?= e(admin_url('products/' . $product['id'] . '/edit')) ?>">Edit</a>
        <form class="d-inline" method="post" action="<?= e(admin_url('products/' . $product['id'] . '/delete')) ?>" onsubmit="return confirm('Delete this product?')"><input type="hidden" name="csrf_token" value="<?= e(Csrf::generate()) ?>"><button class="btn btn-sm btn-danger">Delete</button></form>
    </td>
</tr>
<?php endforeach; ?>
</tbody></table></div></div>
<?php if (($totalPages ?? 1) > 1): ?>
<nav class="mt-3">
    <ul class="pagination mb-0">
        <?php for ($page = 1; $page <= $totalPages; $page++): ?>
            <li class="page-item <?= $page === $currentPage ? 'active' : '' ?>">
                <a class="page-link" href="<?= e(admin_url('products?page=' . $page)) ?>"><?= e((string) $page) ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>
