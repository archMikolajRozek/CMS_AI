<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Middleware\AuthMiddleware;
use App\Core\Router;

// Załaduj trasy aplikacji
$routes = require __DIR__ . '/routes/web.php';
$router = new Router($routes);

// Middleware dla autoryzacji
$middleware = new AuthMiddleware();
$middleware->handle();

// Obsłuż bieżącą ścieżkę
$router->dispatch($_SERVER['REQUEST_URI']);
?>