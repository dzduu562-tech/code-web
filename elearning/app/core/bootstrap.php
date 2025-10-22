<?php
// Basic bootstrap for the lightweight MVC app
error_reporting(E_ALL);
ini_set('display_errors', 1);

$rootPath = dirname(__DIR__, 2);

// Simple autoloader
spl_autoload_register(function ($class) use ($rootPath) {
    $prefixes = [
        'Core' => $rootPath . '/app/core',
        'Controllers' => $rootPath . '/app/controllers',
        'Models' => $rootPath . '/app/models',
    ];
    foreach ($prefixes as $prefix => $dir) {
        if (strpos($class, $prefix . '\\') === 0) {
            $relative = substr($class, strlen($prefix) + 1);
            $file = $dir . '/' . str_replace('\\', '/', $relative) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

// Load config
$configFile = $rootPath . '/config/config.php';
if (!file_exists($configFile)) {
    $configFile = $rootPath . '/config/config.php.sample';
}
$config = require $configFile;

// Start secure session
session_name('elearn_sess');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

// Include core utilities
require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/Helpers.php';
require_once __DIR__ . '/Auth.php';
require_once __DIR__ . '/Router.php';

use Core\Router;
use Core\Auth;

$router = new Router($config);

// Define routes minimal to start
$router->get('/', 'Controllers\\HomeController@index');
$router->get('/login', 'Controllers\\AuthController@showLogin');
$router->post('/login', 'Controllers\\AuthController@login');
$router->get('/logout', 'Controllers\\AuthController@logout');
$router->get('/register', 'Controllers\\AuthController@showRegister');
$router->post('/register', 'Controllers\\AuthController@register');

$router->get('/dashboard', 'Controllers\\DashboardController@index');
$router->get('/courses', 'Controllers\\CourseController@index');
$router->get('/course', 'Controllers\\CourseController@show');
$router->get('/lesson', 'Controllers\\LessonController@show');
$router->post('/lesson/complete', 'Controllers\\LessonController@complete');

// Assignments & submissions
$router->get('/assignments', 'Controllers\\AssignmentsController@index');
$router->get('/assignment', 'Controllers\\AssignmentsController@show');
$router->get('/submissions', 'Controllers\\AssignmentsController@my');
$router->post('/assignment/submit', 'Controllers\\AssignmentsController@submit');
$router->post('/assignment/grade', 'Controllers\\AssignmentsController@grade');

// Quizzes
$router->get('/quiz', 'Controllers\\QuizController@show');
$router->post('/quiz/submit', 'Controllers\\QuizController@submit');

// Forum
$router->get('/forum', 'Controllers\\ForumController@index');
$router->get('/thread', 'Controllers\\ForumController@thread');
$router->post('/thread/create', 'Controllers\\ForumController@create');
$router->post('/thread/reply', 'Controllers\\ForumController@reply');

// Admin
$router->get('/admin', 'Controllers\\AdminController@index');
$router->get('/admin/users', 'Controllers\\AdminController@users');
$router->post('/admin/user/update', 'Controllers\\AdminController@updateUser');

// Teacher minimal course CRUD
$router->get('/course/create', 'Controllers\\CoursesManageController@create');
$router->post('/course/create', 'Controllers\\CoursesManageController@create');

// API
$router->get('/api/notifications', 'Controllers\\ApiController@notifications');

// Fallback
$router->fallback(function () {
    http_response_code(404);
    echo '<h1>404 Not Found</h1>';
});

$router->dispatch();
