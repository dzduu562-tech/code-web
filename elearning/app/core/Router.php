<?php

class Router {
    private $routes = [];
    
    public function get($route, $controller, $method) {
        $this->routes['GET'][$route] = ['controller' => $controller, 'method' => $method];
    }
    
    public function post($route, $controller, $method) {
        $this->routes['POST'][$route] = ['controller' => $controller, 'method' => $method];
    }
    
    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $route = $_GET['route'] ?? '';
        
        // Remove leading/trailing slashes
        $route = trim($route, '/');
        
        // Default route
        if ($route === '') {
            $route = 'home';
        }
        
        if (isset($this->routes[$requestMethod][$route])) {
            $routeData = $this->routes[$requestMethod][$route];
            $controller = new $routeData['controller']();
            $method = $routeData['method'];
            $controller->$method();
        } else {
            // 404 - Not Found
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
        }
    }
}
