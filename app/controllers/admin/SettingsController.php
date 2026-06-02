<?php

declare(strict_types=1);

final class SettingsController extends Controller
{
    private SettingsModel $settings;
    private MediaModel $media;

    public function __construct()
    {
        parent::__construct();
        Auth::requireAdmin();
        $this->settings = new SettingsModel();
        $this->media = new MediaModel();
    }

    public function index(): void
    {
        $errors = [];
        $settings = $this->settings->get();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();
            if (trim((string) ($_POST['site_name'] ?? '')) === '') {
                $errors[] = 'Site name is required.';
            }

            if ($errors === []) {
                $this->settings->update($_POST);
                $this->redirect(admin_url('settings'));
            }

            $settings = array_merge($settings, $_POST);
        }

        $this->view('admin/settings/index', [
            'title' => 'ตั้งค่าเว็บไซต์',
            'settings' => $settings,
            'mediaItems' => $this->media->all(),
            'errors' => $errors,
            'csrfToken' => Csrf::generate(),
        ]);
    }
}
