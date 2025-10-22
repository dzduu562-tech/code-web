<?php

class Router {
    private $routes = [];
    private $middleware = [];
    
    public function get($path, $handler, $middleware = []) {
        $this->addRoute('GET', $path, $handler, $middleware);
    }
    
    public function post($path, $handler, $middleware = []) {
        $this->addRoute('POST', $path, $handler, $middleware);
    }
    
    public function put($path, $handler, $middleware = []) {
        $this->addRoute('PUT', $path, $handler, $middleware);
    }
    
    public function delete($path, $handler, $middleware = []) {
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }
    
    private function addRoute($method, $path, $handler, $middleware = []) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }
    
    public function middleware($name, $callback) {
        $this->middleware[$name] = $callback;
    }
    
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = str_replace('/elearning', '', $path);
        $path = $path ?: '/';
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $path)) {
                // Run middleware
                foreach ($route['middleware'] as $middlewareName) {
                    if (isset($this->middleware[$middlewareName])) {
                        $result = call_user_func($this->middleware[$middlewareName]);
                        if ($result === false) {
                            return;
                        }
                    }
                }
                
                // Extract parameters
                $params = $this->extractParams($route['path'], $path);
                
                // Call handler
                $this->callHandler($route['handler'], $params);
                return;
            }
        }
        
        // 404 Not Found
        http_response_code(404);
        include __DIR__ . '/../views/errors/404.php';
    }
    
    private function matchPath($routePath, $requestPath) {
        $routePattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $routePath);
        $routePattern = '#^' . $routePattern . '$#';
        return preg_match($routePattern, $requestPath);
    }
    
    private function extractParams($routePath, $requestPath) {
        $routePattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $routePath);
        $routePattern = '#^' . $routePattern . '$#';
        
        if (preg_match($routePattern, $requestPath, $matches)) {
            array_shift($matches); // Remove full match
            return $matches;
        }
        
        return [];
    }
    
    private function callHandler($handler, $params) {
        if (is_string($handler)) {
            if (strpos($handler, '@') !== false) {
                list($controller, $method) = explode('@', $handler);
                $controllerClass = $controller . 'Controller';
                $controllerFile = __DIR__ . '/../controllers/' . $controllerClass . '.php';
                
                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                    $controllerInstance = new $controllerClass();
                    call_user_func_array([$controllerInstance, $method], $params);
                } else {
                    throw new Exception("Controller {$controllerClass} not found");
                }
            } else {
                // Direct view
                $viewFile = __DIR__ . '/../views/' . $handler . '.php';
                if (file_exists($viewFile)) {
                    include $viewFile;
                } else {
                    throw new Exception("View {$handler} not found");
                }
            }
        } elseif (is_callable($handler)) {
            call_user_func_array($handler, $params);
        }
    }
    
    public function url($path, $params = []) {
        $url = APP_URL . $path;
        foreach ($params as $key => $value) {
            $url = str_replace('{' . $key . '}', $value, $url);
        }
        return $url;
    }
    
    public function redirect($path, $params = []) {
        $url = $this->url($path, $params);
        header("Location: {$url}");
        exit;
    }
}