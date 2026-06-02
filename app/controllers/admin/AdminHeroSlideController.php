<?php

declare(strict_types=1);

final class AdminHeroSlideController extends Controller
{
    private HeroSlideModel $slides;
    private MediaModel $media;

    public function __construct()
    {
        parent::__construct();
        Auth::requireAdmin();
        $this->slides = new HeroSlideModel();
        $this->media = new MediaModel();
    }

    public function index(array $errors = [], array $values = []): void
    {
        $this->view('admin/hero_slides/index', [
            'title' => 'สไลด์หน้าแรก',
            'slides' => $this->slides->all(),
            'mediaItems' => $this->media->all(),
            'values' => $values,
            'errors' => $errors,
            'csrfToken' => Csrf::generate(),
        ]);
    }

    public function save(): void
    {
        $this->verifyCsrf();
        $errors = $this->validate($_POST);
        if ($errors !== []) {
            $this->index($errors, $_POST);
            return;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $this->slides->save($_POST, $id > 0 ? $id : null);
        $this->redirect(admin_url('hero-slides'));
    }

    public function delete(string $id): void
    {
        $this->verifyCsrf();
        $this->slides->delete((int) $id);
        $this->redirect(admin_url('hero-slides'));
    }

    private function validate(array $data): array
    {
        $errors = [];
        if ((int) ($data['media_id'] ?? 0) <= 0) {
            $errors[] = 'กรุณาเลือกรูปสำหรับสไลด์';
        }

        return $errors;
    }
}
