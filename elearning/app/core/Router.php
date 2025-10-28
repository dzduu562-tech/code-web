<?php

class Router {
    private $routes = [];
    private $currentRoute = '';
    private $params = [];

    public function __construct() {
        $this->parseUrl();
    }

    private function parseUrl() {
        $url = $_GET['route'] ?? '';
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        
        $parts = explode('/', $url);
        $this->currentRoute = $parts[0] ?: 'home';
        
        // Parse parameters from URL
        parse_str($_SERVER['QUERY_STRING'], $this->params);
        unset($this->params['route']);
    }

    public function get($route, $callback) {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->routes['GET'][$route] = $callback;
        }
        return $this;
    }

    public function post($route, $callback) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->routes['POST'][$route] = $callback;
        }
        return $this;
    }

    public function any($route, $callback) {
        $this->routes['GET'][$route] = $callback;
        $this->routes['POST'][$route] = $callback;
        return $this;
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $route = $this->currentRoute;

        // Check if route exists
        if (isset($this->routes[$method][$route])) {
            $callback = $this->routes[$method][$route];
            
            if (is_callable($callback)) {
                return call_user_func($callback, $this->params);
            }
            
            if (is_string($callback)) {
                return $this->callControllerAction($callback);
            }
        }

        // Default routes based on convention
        return $this->handleConventionRoute($route);
    }

    private function callControllerAction($callback) {
        $parts = explode('@', $callback);
        $controllerName = $parts[0];
        $actionName = $parts[1] ?? 'index';

        $controllerFile = __DIR__ . "/../controllers/{$controllerName}.php";
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            
            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                
                if (method_exists($controller, $actionName)) {
                    return $controller->$actionName($this->params);
                }
            }
        }

        return $this->notFound();
    }

    private function handleConventionRoute($route) {
        // Convert route to controller name (e.g., 'courses' -> 'CourseController')
        $controllerName = $this->routeToController($route);
        $controllerFile = __DIR__ . "/../controllers/{$controllerName}.php";

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            
            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                
                // Determine action based on HTTP method and parameters
                $action = $this->determineAction();
                
                if (method_exists($controller, $action)) {
                    return $controller->$action($this->params);
                }
            }
        }

        return $this->notFound();
    }

    private function routeToController($route) {
        $controllers = [
            'home' => 'HomeController',
            'auth' => 'AuthController',
            'login' => 'AuthController',
            'register' => 'AuthController',
            'logout' => 'AuthController',
            'dashboard' => 'DashboardController',
            'courses' => 'CourseController',
            'course' => 'CourseController',
            'lessons' => 'LessonController',
            'lesson' => 'LessonController',
            'assignments' => 'AssignmentController',
            'assignment' => 'AssignmentController',
            'quizzes' => 'QuizController',
            'quiz' => 'QuizController',
            'forum' => 'ForumController',
            'thread' => 'ForumController',
            'notifications' => 'NotificationController',
            'admin' => 'AdminController',
            'profile' => 'ProfileController',
        ];

        return $controllers[$route] ?? 'HomeController';
    }

    private function determineAction() {
        $method = $_SERVER['REQUEST_METHOD'];
        $route = $this->currentRoute;

        // Special cases
        if ($route === 'login' && $method === 'POST') return 'login';
        if ($route === 'register' && $method === 'POST') return 'register';
        if ($route === 'logout') return 'logout';

        // RESTful conventions
        if (isset($this->params['id'])) {
            return $method === 'POST' ? 'update' : 'show';
        }

        if (isset($this->params['action'])) {
            return $this->params['action'];
        }

        return $method === 'POST' ? 'store' : 'index';
    }

    public function redirect($url, $statusCode = 302) {
        header("Location: {$url}", true, $statusCode);
        exit;
    }

    public function url($route, $params = []) {
        $config = require __DIR__ . '/../../config/config.php';
        $baseUrl = rtrim($config['app']['url'], '/');
        
        $url = $baseUrl . '/public/index.php';
        
        if ($route && $route !== 'home') {
            $params['route'] = $route;
        }
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        return $url;
    }

    public function back() {
        $referer = $_SERVER['HTTP_REFERER'] ?? $this->url('home');
        $this->redirect($referer);
    }

    private function notFound() {
        http_response_code(404);
        require_once __DIR__ . '/../views/errors/404.php';
        exit;
    }

    public function getCurrentRoute() {
        return $this->currentRoute;
    }

    public function getParams() {
        return $this->params;
    }
}