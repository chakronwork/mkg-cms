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

    public function index(array $errors = [], array $formValues = []): void
    {
        $menus = $this->menus->all();
        $editingId = (int) ($formValues['id'] ?? 0);
        if ($editingId > 0) {
            foreach ($menus as $index => $menu) {
                if ((int) $menu['id'] === $editingId) {
                    $menus[$index] = array_merge($menu, $formValues);
                    break;
                }
            }
        }

        $this->view('admin/menus/index', [
            'title' => 'เมนู',
            'menus' => $menus,
            'formValues' => $editingId > 0 ? [] : $formValues,
            'errors' => $errors,
            'csrfToken' => Csrf::generate(),
        ]);
    }

    public function save(): void
    {
        $this->verifyCsrf();
        if (trim((string) ($_POST['title'] ?? '')) === '' || trim((string) ($_POST['url'] ?? '')) === '') {
            $this->index(['กรุณากรอกชื่อเมนูและ URL'], $_POST);
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

    public function reorder(): void
    {
        $this->verifyCsrf();
        $ids = $_POST['menu_ids'] ?? [];
        $this->menus->reorder(is_array($ids) ? $ids : []);
        $this->redirect(admin_url('menus'));
    }
}
