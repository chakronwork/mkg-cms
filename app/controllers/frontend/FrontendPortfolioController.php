<?php

declare(strict_types=1);

final class FrontendPortfolioController extends Controller
{
    private PortfolioModel $portfolios;
    private SettingsModel $settings;
    private MenuModel $menus;

    public function __construct()
    {
        parent::__construct();
        $this->portfolios = new PortfolioModel();
        $this->settings = new SettingsModel();
        $this->menus = new MenuModel();
    }

    public function index(): void
    {
        $settings = $this->settings->get();
        $this->view('frontend/portfolio/index', [
            'title' => 'ผลงาน',
            'metaDescription' => $settings['site_description'] ?? '',
            'portfolios' => $this->portfolios->published(),
            'settings' => $settings,
            'menus' => $this->menus->all(true),
        ], 'frontend/layouts/main');
    }

    public function show(string $slug): void
    {
        $portfolio = $this->portfolios->findPublishedBySlug($slug);
        if (!$portfolio) {
            $this->notFound();
            return;
        }
        $settings = $this->settings->get();
        $this->view('frontend/portfolio/show', [
            'title' => $portfolio['seo_title'] ?: $portfolio['title'],
            'metaDescription' => $portfolio['seo_description'] ?: ($settings['site_description'] ?? ''),
            'portfolio' => $portfolio,
            'images' => $this->portfolios->images((int) $portfolio['id']),
            'settings' => $settings,
            'menus' => $this->menus->all(true),
        ], 'frontend/layouts/main');
    }
}
