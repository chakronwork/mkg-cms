<?php

declare(strict_types=1);

abstract class Controller
{
    public function __construct()
    {
        $this->setSecurityHeaders();
    }

    protected function view(string $view, array $data = [], string $layout = 'admin/layouts/main'): void
    {
        extract($data, EXTR_SKIP);
        $viewPath = BASE_PATH . '/app/views/' . $view . '.php';

        if (!is_file($viewPath)) {
            http_response_code(500);
            echo 'View not found.';
            return;
        }

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        if ($layout === '') {
            echo $content;
            return;
        }

        require BASE_PATH . '/app/views/' . $layout . '.php';
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    protected function verifyCsrf(): void
    {
        $token = (string) ($_POST['csrf_token'] ?? '');
        if (!Csrf::verify($token)) {
            http_response_code(403);
            echo 'Invalid CSRF token.';
            exit;
        }
    }

    protected function notFound(): void
    {
        http_response_code(404);
        $this->view('frontend/not_found', ['title' => 'Not found'], 'frontend/layouts/main');
    }

    private function setSecurityHeaders(): void
    {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
    }
}
