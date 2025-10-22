<?php
class Router {
    private array $routes = [];

    public function get(string $path, callable $handler): void {
        $this->routes['GET'][$path] = $handler;
    }
    public function post(string $path, callable $handler): void {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(): void {
        $route = $_GET['route'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $handler = $this->routes[$method][$route] ?? null;
        if (!$handler) { http_response_code(404); echo '404 Not Found'; return; }
        echo call_user_func($handler);
    }
}
