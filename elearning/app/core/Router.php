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
        
        // Remove base path if exists
        $basePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname($_SERVER['SCRIPT_NAME']));
        $path = str_replace($basePath, '', $path);
        $path = $path ?: '/';
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $path)) {
                // Execute middleware
                foreach ($route['middleware'] as $middlewareName) {
                    if (isset($this->middleware[$middlewareName])) {
                        $result = call_user_func($this->middleware[$middlewareName]);
                        if ($result === false) {
                            return; // Middleware blocked the request
                        }
                    }
                }
                
                // Execute handler
                $this->executeHandler($route['handler'], $this->extractParams($route['path'], $path));
                return;
            }
        }
        
        // 404 Not Found
        http_response_code(404);
        include __DIR__ . '/../views/errors/404.php';
    }
    
    private function matchPath($routePath, $requestPath) {
        if ($routePath === $requestPath) {
            return true;
        }
        
        // Convert route path to regex
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';
        
        return preg_match($pattern, $requestPath);
    }
    
    private function extractParams($routePath, $requestPath) {
        $params = [];
        
        // Convert route path to regex and extract parameters
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';
        
        if (preg_match($pattern, $requestPath, $matches)) {
            preg_match_all('/\{([^}]+)\}/', $routePath, $paramNames);
            for ($i = 1; $i < count($matches); $i++) {
                $params[$paramNames[1][$i - 1]] = $matches[$i];
            }
        }
        
        return $params;
    }
    
    private function executeHandler($handler, $params) {
        if (is_string($handler)) {
            // Controller@method format
            list($controller, $method) = explode('@', $handler);
            $controllerClass = $controller . 'Controller';
            $controllerFile = __DIR__ . '/../controllers/' . $controllerClass . '.php';
            
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                $controllerInstance = new $controllerClass();
                if (method_exists($controllerInstance, $method)) {
                    call_user_func_array([$controllerInstance, $method], $params);
                } else {
                    throw new Exception("Method {$method} not found in {$controllerClass}");
                }
            } else {
                throw new Exception("Controller {$controllerClass} not found");
            }
        } elseif (is_callable($handler)) {
            call_user_func_array($handler, $params);
        }
    }
}