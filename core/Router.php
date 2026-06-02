<?php

declare(strict_types=1);

final class Router
{
    private array $routes = [];

    public function add(string $method, string $pattern, string $controller, string $action): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $this->normalize($pattern),
            'controller' => $controller,
            'action' => $action,
        ];
    }

    public function dispatch(string $method, string $path): void
    {
        $path = $this->normalize($path);

        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($method)) {
                continue;
            }

            $params = $this->match($route['pattern'], $path);
            if ($params === null) {
                continue;
            }

            $controller = new $route['controller']();
            $controller->{$route['action']}(...array_values($params));
            return;
        }

        http_response_code(404);
        echo '404 Not Found';
    }

    private function normalize(string $path): string
    {
        $path = parse_url($path, PHP_URL_PATH) ?: '/';
        $base = parse_url((string) app_config('base_url'), PHP_URL_PATH) ?: '';
        if ($base !== '' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base)) ?: '/';
        }

        return '/' . trim($path, '/');
    }

    private function match(string $pattern, string $path): ?array
    {
        $names = [];
        $regex = preg_replace_callback('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', static function (array $matches) use (&$names): string {
            $names[] = $matches[1];
            return '([^/]+)';
        }, $pattern);

        if (!preg_match('#^' . $regex . '$#', $path, $matches)) {
            return null;
        }

        array_shift($matches);
        return array_combine($names, $matches) ?: [];
    }
}
