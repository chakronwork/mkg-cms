<?php

declare(strict_types=1);

final class AdminPortfolioController extends Controller
{
    private PortfolioModel $portfolios;
    private MediaModel $media;

    public function __construct()
    {
        parent::__construct();
        Auth::requireAdmin();
        $this->portfolios = new PortfolioModel();
        $this->media = new MediaModel();
    }

    public function index(): void
    {
        $this->view('admin/portfolios/list', ['title' => 'ผลงาน', 'portfolios' => $this->portfolios->all()]);
    }

    public function create(): void
    {
        $this->form();
    }

    public function edit(string $id): void
    {
        $portfolio = $this->portfolios->find((int) $id);
        if (!$portfolio) {
            $this->notFound();
            return;
        }
        $this->form($portfolio);
    }

    public function delete(string $id): void
    {
        $this->verifyCsrf();
        $this->portfolios->softDelete((int) $id);
        $this->redirect(admin_url('portfolios'));
    }

    private function form(?array $portfolio = null): void
    {
        $errors = [];
        $selectedMedia = $portfolio ? array_column($this->portfolios->images((int) $portfolio['id']), 'id') : [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();
            $errors = $this->validate($_POST, $portfolio ? (int) $portfolio['id'] : null);
            $mediaInput = $_POST['media_ids'] ?? [];
            $selectedMedia = is_array($mediaInput)
                ? array_filter(array_map('intval', $mediaInput))
                : array_filter(array_map('intval', preg_split('/[\s,]+/', (string) $mediaInput) ?: []));

            if ($errors === []) {
                $this->portfolios->save($_POST, $selectedMedia, $portfolio ? (int) $portfolio['id'] : null);
                $this->redirect(admin_url('portfolios'));
            }

            $portfolio = array_merge($portfolio ?? [], $_POST);
        }

        $this->view('admin/portfolios/form', [
            'title' => $portfolio ? 'แก้ไขผลงาน' : 'เพิ่มผลงาน',
            'portfolio' => $portfolio,
            'mediaItems' => $this->media->all(),
            'selectedMedia' => $selectedMedia,
            'errors' => $errors,
            'csrfToken' => Csrf::generate(),
        ]);
    }

    private function validate(array $data, ?int $id): array
    {
        $errors = [];
        if (trim((string) ($data['title'] ?? '')) === '') {
            $errors[] = 'Title is required.';
        }
        $slug = trim((string) ($data['slug'] ?? ''));
        if (!preg_match('/^[a-z0-9-]{1,200}$/', $slug)) {
            $errors[] = 'Slug must be kebab-case using lowercase letters, numbers, and hyphens.';
        } elseif ($this->portfolios->slugExists($slug, $id)) {
            $errors[] = 'Slug is already used.';
        }
        return $errors;
    }
}
