<?php

declare(strict_types=1);

final class FrontendProductController extends Controller
{
    private ProductModel $products;
    private SettingsModel $settings;
    private MenuModel $menus;

    public function __construct()
    {
        parent::__construct();
        $this->products = new ProductModel();
        $this->settings = new SettingsModel();
        $this->menus = new MenuModel();
    }

    public function index(): void
    {
        $settings = $this->settings->get();
        $this->view('frontend/products/index', [
            'title' => 'สินค้า',
            'metaDescription' => $settings['site_description'] ?? '',
            'products' => $this->products->published(),
            'settings' => $settings,
            'menus' => $this->menus->all(true),
        ], 'frontend/layouts/main');
    }

    public function show(string $slug): void
    {
        $product = $this->products->findPublishedBySlug($slug);
        if (!$product) {
            $this->notFound();
            return;
        }
        $settings = $this->settings->get();
        $this->view('frontend/products/show', [
            'title' => $product['seo_title'] ?: $product['name'],
            'metaDescription' => $product['seo_description'] ?: ($settings['site_description'] ?? ''),
            'product' => $product,
            'images' => $this->products->images((int) $product['id']),
            'settings' => $settings,
            'menus' => $this->menus->all(true),
        ], 'frontend/layouts/main');
    }
}
