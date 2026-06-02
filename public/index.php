<?php

declare(strict_types=1);

require dirname(__DIR__) . '/core/bootstrap.php';

$router = new Router();
$router->add('GET', '/', 'HomeController', 'index');
$router->add('GET', '/about', 'HomeController', 'about');
$router->add('GET', '/products', 'FrontendProductController', 'index');
$router->add('GET', '/products/{slug}', 'FrontendProductController', 'show');
$router->add('GET', '/portfolio', 'FrontendPortfolioController', 'index');
$router->add('GET', '/portfolio/{slug}', 'FrontendPortfolioController', 'show');
$router->add('GET', '/contact', 'ContactController', 'index');
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
