<?php

declare(strict_types=1);

final class MenuController extends Controller
{
    private MenuModel $menus;

    public function __construct()
    {
        parent::__construct();
        Auth::requireAdmin();
        $this->menus = new MenuModel();
    }

    public function index(array $errors = []): void
    {
        $this->view('admin/menus/index', [
            'title' => 'Menus',
            'menus' => $this->menus->all(),
            'errors' => $errors,
            'csrfToken' => Csrf::generate(),
        ]);
    }

    public function save(): void
    {
        $this->verifyCsrf();
        if (trim((string) ($_POST['title'] ?? '')) === '' || trim((string) ($_POST['url'] ?? '')) === '') {
            $this->index(['Title and URL are required.']);
            return;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $this->menus->save($_POST, $id > 0 ? $id : null);
        $this->redirect(admin_url('menus'));
    }

    public function delete(string $id): void
    {
        $this->verifyCsrf();
        $this->menus->delete((int) $id);
        $this->redirect(admin_url('menus'));
    }
}
