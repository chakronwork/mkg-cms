<?php

declare(strict_types=1);

final class AdminProductController extends Controller
{
    private ProductModel $products;
    private MediaModel $media;

    public function __construct()
    {
        parent::__construct();
        Auth::requireAdmin();
        $this->products = new ProductModel();
        $this->media = new MediaModel();
    }

    public function index(): void
    {
        $this->view('admin/products/list', ['title' => 'Products', 'products' => $this->products->all()]);
    }

    public function create(): void
    {
        $this->form();
    }

    public function edit(string $id): void
    {
        $product = $this->products->find((int) $id);
        if (!$product) {
            $this->notFound();
            return;
        }
        $this->form($product);
    }

    public function delete(string $id): void
    {
        $this->verifyCsrf();
        $this->products->softDelete((int) $id);
        $this->redirect(admin_url('products'));
    }

    private function form(?array $product = null): void
    {
        $errors = [];
        $selectedMedia = $product ? array_column($this->products->images((int) $product['id']), 'id') : [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();
            $errors = $this->validate($_POST, $product ? (int) $product['id'] : null);
            $selectedMedia = $this->parseMediaIds($_POST['media_ids'] ?? []);

            if ($errors === []) {
                $this->products->save($_POST, $selectedMedia, $product ? (int) $product['id'] : null);
                $this->redirect(admin_url('products'));
            }

            $product = array_merge($product ?? [], $_POST);
        }

        $this->view('admin/products/form', [
            'title' => $product ? 'Edit Product' : 'Create Product',
            'product' => $product,
            'mediaItems' => $this->media->all(),
            'selectedMedia' => $selectedMedia,
            'errors' => $errors,
            'csrfToken' => Csrf::generate(),
        ]);
    }

    private function validate(array $data, ?int $id): array
    {
        $errors = [];
        if (trim((string) ($data['name'] ?? '')) === '') {
            $errors[] = 'Name is required.';
        }
        $slug = trim((string) ($data['slug'] ?? ''));
        if (!preg_match('/^[a-z0-9-]{1,200}$/', $slug)) {
            $errors[] = 'Slug must be kebab-case using lowercase letters, numbers, and hyphens.';
        } elseif ($this->products->slugExists($slug, $id)) {
            $errors[] = 'Slug is already used.';
        }
        return $errors;
    }

    private function parseMediaIds(mixed $value): array
    {
        if (is_array($value)) {
            return array_filter(array_map('intval', $value));
        }

        return array_filter(array_map('intval', preg_split('/[\s,]+/', (string) $value) ?: []));
    }
}
