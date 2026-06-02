<?php

declare(strict_types=1);

final class AuthController extends Controller
{
    private AdminModel $admins;

    public function __construct()
    {
        parent::__construct();
        $this->admins = new AdminModel();
    }

    public function login(): void
    {
        if (Auth::check()) {
            $this->redirect(admin_url());
        }

        $errors = [];
        $username = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();
            $username = trim((string) ($_POST['username'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');
            $admin = $this->admins->findByUsername($username);

            if ($admin && password_verify($password, (string) $admin['password_hash'])) {
                Auth::login($admin);
                $this->redirect(admin_url());
            }

            $errors[] = 'Invalid username or password.';
        }

        $this->view('admin/auth/login', [
            'title' => 'Admin Login',
            'csrfToken' => Csrf::generate(),
            'errors' => $errors,
            'username' => $username,
        ], '');
    }

    public function logout(): void
    {
        $this->verifyCsrf();
        Auth::logout();
        $this->redirect(admin_url('login'));
    }
}
