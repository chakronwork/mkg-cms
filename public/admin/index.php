<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/core/bootstrap.php';

$router = new Router();
$router->add('GET', '/admin/login', 'AuthController', 'login');
$router->add('POST', '/admin/login', 'AuthController', 'login');
$router->add('POST', '/admin/logout', 'AuthController', 'logout');
$router->add('GET', '/admin', 'DashboardController', 'index');
$router->add('GET', '/admin/dashboard', 'DashboardController', 'index');
$router->add('GET', '/admin/pages', 'AdminPageController', 'index');
$router->add('GET', '/admin/pages/{id}/edit', 'AdminPageController', 'edit');
$router->add('POST', '/admin/pages/{id}/edit', 'AdminPageController', 'edit');
$router->add('GET', '/admin/products', 'AdminProductController', 'index');
$router->add('GET', '/admin/products/create', 'AdminProductController', 'create');
$router->add('POST', '/admin/products/create', 'AdminProductController', 'create');
$router->add('GET', '/admin/products/{id}/edit', 'AdminProductController', 'edit');
$router->add('POST', '/admin/products/{id}/edit', 'AdminProductController', 'edit');
$router->add('POST', '/admin/products/{id}/delete', 'AdminProductController', 'delete');
$router->add('GET', '/admin/portfolios', 'AdminPortfolioController', 'index');
$router->add('GET', '/admin/portfolios/create', 'AdminPortfolioController', 'create');
$router->add('POST', '/admin/portfolios/create', 'AdminPortfolioController', 'create');
$router->add('GET', '/admin/portfolios/{id}/edit', 'AdminPortfolioController', 'edit');
$router->add('POST', '/admin/portfolios/{id}/edit', 'AdminPortfolioController', 'edit');
$router->add('POST', '/admin/portfolios/{id}/delete', 'AdminPortfolioController', 'delete');
$router->add('GET', '/admin/media', 'MediaController', 'index');
$router->add('POST', '/admin/media/upload', 'MediaController', 'upload');
$router->add('POST', '/admin/media/{id}/alt', 'MediaController', 'alt');
$router->add('POST', '/admin/media/{id}/delete', 'MediaController', 'delete');
$router->add('GET', '/admin/menus', 'MenuController', 'index');
$router->add('POST', '/admin/menus', 'MenuController', 'save');
$router->add('POST', '/admin/menus/reorder', 'MenuController', 'reorder');
$router->add('POST', '/admin/menus/{id}/delete', 'MenuController', 'delete');
$router->add('GET', '/admin/settings', 'SettingsController', 'index');
$router->add('POST', '/admin/settings', 'SettingsController', 'index');
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
