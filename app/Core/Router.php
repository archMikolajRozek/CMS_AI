<?php

namespace App\Core;

class Router {
    private $routes = [];

    public function __construct($routes = []) {
        $this->routes = $routes;
    }

    /**
     * Obsługuje przekierowanie na odpowiednią metodę w kontrolerze.
     */
    public function dispatch($uri) {
        // Usuń query string, jeśli istnieje
        $uri = explode('?', $uri)[0];

        // Znajdź trasę wśród zarejestrowanych
        foreach ($this->routes as $route => $handler) {
            $pattern = preg_replace('/\{[^\/]+\}/', '([^\/]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Usuń pełny match

                $controller = $handler[0];
                $method = $handler[1];

                return $this->execute($controller, $method, $matches);
            }
        }

        // Jeśli nie znaleziono pasującej trasy
        http_response_code(404);
        echo "404 Not Found";
        exit;
    }

    /**
     * Wywołuje odpowiednią metodę w kontrolerze.
     */
    private function execute($controller, $method, $params = []) {
        if (!class_exists($controller)) {
            throw new \Exception("Controller '{$controller}' not found");
        }

        $instance = new $controller();

        if (!method_exists($instance, $method)) {
            throw new \Exception("Method '{$method}' not found in controller '{$controller}'");
        }

        call_user_func_array([$instance, $method], $params);
    }
}
