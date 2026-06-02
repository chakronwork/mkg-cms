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
        $this->view('admin/pages/list', ['title' => 'Pages', 'pages' => $this->pages->all()]);
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
        }

        $this->view('admin/pages/form', [
            'title' => 'Edit Page',
            'page' => $page,
            'sections' => $this->pages->sections($pageId),
            'errors' => $errors,
            'csrfToken' => Csrf::generate(),
        ]);
    }
}
