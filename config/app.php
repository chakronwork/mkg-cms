<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', BASE_PATH . '/public');
define('APP_ENV', 'development');

return [
    'base_url' => '/mkg-cms/public',
    'admin_url' => '/mkg-cms/public/admin',
    'upload_dir' => PUBLIC_PATH . '/uploads',
    'upload_url' => '/mkg-cms/public/uploads',
    'max_upload_bytes' => 5 * 1024 * 1024,
    'allowed_upload_mimes' => [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ],
];
