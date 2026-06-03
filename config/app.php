<?php

declare(strict_types=1);

defined('BASE_PATH') || define('BASE_PATH', dirname(__DIR__));
defined('PUBLIC_PATH') || define('PUBLIC_PATH', BASE_PATH . '/public');
defined('APP_ENV') || define('APP_ENV', getenv('APP_ENV') ?: 'development');

return [
    'base_url' => getenv('BASE_URL') ?: '/mkg-cms/public',
    'admin_url' => getenv('ADMIN_URL') ?: '/mkg-cms/public/admin',
    'upload_dir' => PUBLIC_PATH . '/uploads',
    'upload_url' => getenv('UPLOAD_URL') ?: '/mkg-cms/public/uploads',
    'tinymce_script_url' => 'https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js',
    'max_upload_bytes' => 5 * 1024 * 1024,
    'allowed_upload_mimes' => [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ],
];
