<?php
namespace Core;

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];
    private $fallback;
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function get(string $path, $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function fallback(callable $cb): void
    {
        $this->fallback = $cb;
    }

    private function resolveHandler($handler)
    {
        if (is_callable($handler)) return $handler;
        if (is_string($handler) && strpos($handler, '@') !== false) {
            [$class, $method] = explode('@', $handler, 2);
            return function () use ($class, $method) {
                $controller = new $class($this->config);
                return $controller->$method();
            };
        }
        return null;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $route = $_GET['route'] ?? '/';
        if ($route === '') $route = '/';

        $handler = $this->routes[$method][$route] ?? null;
        if ($handler) {
            $callable = $this->resolveHandler($handler);
            if ($callable) { $callable(); return; }
        }
        if ($this->fallback) { call_user_func($this->fallback); return; }
        http_response_code(404);
        echo 'Not Found';
    }
}
