<?php

declare(strict_types=1);

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

load_env(BASE_PATH . '/.env');

$app = require BASE_PATH . '/config/app.php';

if (APP_ENV === 'production') {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', BASE_PATH . '/logs/php_errors.log');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

$sessionPath = (string) ini_get('session.save_path');
if ($sessionPath === '' || !is_dir($sessionPath) || !is_writable($sessionPath)) {
    $sessionPath = BASE_PATH . '/storage/sessions';
    if (!is_dir($sessionPath)) {
        mkdir($sessionPath, 0755, true);
    }
    session_save_path($sessionPath);
}

session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
    'use_strict_mode' => true,
]);

spl_autoload_register(static function (string $class): void {
    $paths = [
        BASE_PATH . '/core/' . $class . '.php',
        BASE_PATH . '/app/models/' . $class . '.php',
        BASE_PATH . '/app/controllers/admin/' . $class . '.php',
        BASE_PATH . '/app/controllers/frontend/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (is_file($path)) {
            require_once $path;
            return;
        }
    }
});

function load_env(string $path): void
{
    if (!is_file($path) || !is_readable($path)) {
        return;
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = array_map('trim', explode('=', $line, 2));
        if ($key === '' || getenv($key) !== false) {
            continue;
        }

        $value = trim($value, "\"'");
        putenv($key . '=' . $value);
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

function app_config(string $key, mixed $default = null): mixed
{
    global $app;
    return $app[$key] ?? $default;
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function base_url(string $path = ''): string
{
    return rtrim((string) app_config('base_url'), '/') . '/' . ltrim($path, '/');
}

function admin_url(string $path = ''): string
{
    return rtrim((string) app_config('admin_url'), '/') . '/' . ltrim($path, '/');
}

function normalize_line_url(mixed $value): string
{
    $url = trim((string) $value);
    if ($url === '') {
        return '';
    }

    if (preg_match('/^(https?:\/\/|line:\/\/)/i', $url)) {
        return $url;
    }

    if (str_starts_with($url, '@')) {
        return 'https://line.me/R/ti/p/' . rawurlencode($url);
    }

    if (preg_match('/^(lin\.ee|line\.me|page\.line\.me)\//i', $url)) {
        return 'https://' . $url;
    }

    return $url;
}
