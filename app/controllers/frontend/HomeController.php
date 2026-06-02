<?php

declare(strict_types=1);

final class HomeController extends Controller
{
    private PageModel $pages;
    private SettingsModel $settings;
    private MenuModel $menus;
    private HeroSlideModel $heroSlides;
    private ProductModel $products;
    private PortfolioModel $portfolios;

    public function __construct()
    {
        parent::__construct();
        $this->pages = new PageModel();
        $this->settings = new SettingsModel();
        $this->menus = new MenuModel();
        $this->heroSlides = new HeroSlideModel();
        $this->products = new ProductModel();
        $this->portfolios = new PortfolioModel();
    }

    public function index(): void
    {
        $this->page('home', 'frontend/home');
    }

    public function about(): void
    {
        $this->page('about', 'frontend/about');
    }

    private function page(string $slug, string $view): void
    {
        $page = $this->pages->findPublishedBySlug($slug);
        if (!$page) {
            $this->notFound();
            return;
        }

        $settings = $this->settings->get();
        $this->view($view, [
            'title' => $page['seo_title'] ?: $page['title'],
            'metaDescription' => $page['seo_description'] ?: ($settings['site_description'] ?? ''),
            'page' => $page,
            'sections' => $this->pages->sections((int) $page['id']),
            'heroSlides' => $slug === 'home' ? $this->heroSlides->all(true) : [],
            'featuredProducts' => $slug === 'home' ? array_slice($this->products->published(), 0, 3) : [],
            'latestPortfolios' => $slug === 'home' ? array_slice($this->portfolios->published(), 0, 3) : [],
            'settings' => $settings,
            'menus' => $this->menus->all(true),
        ], 'frontend/layouts/main');
    }
}
