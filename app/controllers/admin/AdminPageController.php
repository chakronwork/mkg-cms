<?php

declare(strict_types=1);

final class AdminPageController extends Controller
{
    private PageModel $pages;

    public function __construct()
    {
        parent::__construct();
        Auth::requireAdmin();
        $this->pages = new PageModel();
    }

    public function index(): void
    {
        $this->view('admin/pages/list', ['title' => 'หน้าเว็บ', 'pages' => $this->pages->all()]);
    }

    public function edit(string $id): void
    {
        $pageId = (int) $id;
        $page = $this->pages->find($pageId);
        if (!$page) {
            $this->notFound();
            return;
        }

        $errors = [];
        $sections = $this->pages->sections($pageId);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();
            if (trim((string) ($_POST['title'] ?? '')) === '') {
                $errors[] = 'Title is required.';
            }

            if ($errors === []) {
                $this->pages->update($pageId, $_POST, $_POST['sections'] ?? []);
                $this->redirect(admin_url('pages'));
            }

            $page = array_merge($page, $_POST);
            $postedSections = is_array($_POST['sections'] ?? null) ? $_POST['sections'] : [];
            foreach ($sections as $index => $section) {
                $sectionId = (string) $section['id'];
                if (isset($postedSections[$sectionId]) && is_array($postedSections[$sectionId])) {
                    $sections[$index] = array_merge($section, $postedSections[$sectionId]);
                }
            }
        }

        $this->view('admin/pages/form', [
            'title' => 'แก้ไขหน้าเว็บ',
            'page' => $page,
            'sections' => $sections,
            'errors' => $errors,
            'csrfToken' => Csrf::generate(),
        ]);
    }
}
