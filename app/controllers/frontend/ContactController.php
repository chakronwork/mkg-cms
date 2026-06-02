<?php

declare(strict_types=1);

final class ContactController extends Controller
{
    public function index(): void
    {
        $pages = new PageModel();
        $settingsModel = new SettingsModel();
        $menus = new MenuModel();
        $settings = $settingsModel->get();
        $page = $pages->findPublishedBySlug('contact') ?: ['title' => 'ติดต่อเรา', 'seo_title' => '', 'seo_description' => ''];

        $this->view('frontend/contact', [
            'title' => $page['seo_title'] ?: 'ติดต่อเรา',
            'metaDescription' => $page['seo_description'] ?: ($settings['site_description'] ?? ''),
            'page' => $page,
            'sections' => !empty($page['id']) ? $pages->sections((int) $page['id']) : [],
            'settings' => $settings,
            'menus' => $menus->all(true),
        ], 'frontend/layouts/main');
    }
}
