<?php
// E-Learning Platform - Main Entry Point
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Autoload core classes, models, and controllers
spl_autoload_register(function ($class) {
    $paths = [
        BASE_PATH . '/app/core/' . $class . '.php',
        BASE_PATH . '/app/models/' . $class . '.php',
        BASE_PATH . '/app/controllers/' . $class . '.php',
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Initialize router
$router = new Router();

// Public routes
$router->get('home', 'HomeController', 'index');
$router->get('', 'HomeController', 'index');

// Auth routes
$router->get('login', 'AuthController', 'showLogin');
$router->post('login', 'AuthController', 'login');
$router->get('register', 'AuthController', 'showRegister');
$router->post('register', 'AuthController', 'register');
$router->get('logout', 'AuthController', 'logout');

// Dashboard
$router->get('dashboard', 'DashboardController', 'index');

// Courses
$router->get('courses', 'CourseController', 'index');
$router->get('course', 'CourseController', 'show');
$router->get('courses/create', 'CourseController', 'create');
$router->post('courses/create', 'CourseController', 'create');
$router->get('courses/edit', 'CourseController', 'edit');
$router->post('courses/edit', 'CourseController', 'edit');
$router->post('courses/delete', 'CourseController', 'delete');
$router->post('courses/enroll', 'CourseController', 'enroll');

// Lessons
$router->get('lesson', 'LessonController', 'show');
$router->get('lessons/create', 'LessonController', 'create');
$router->post('lessons/create', 'LessonController', 'create');
$router->get('lessons/edit', 'LessonController', 'edit');
$router->post('lessons/edit', 'LessonController', 'edit');
$router->post('lessons/complete', 'LessonController', 'markComplete');

// Assignments
$router->get('assignments', 'AssignmentController', 'index');
$router->get('assignment', 'AssignmentController', 'show');
$router->get('assignments/create', 'AssignmentController', 'create');
$router->post('assignments/create', 'AssignmentController', 'create');
$router->post('assignments/submit', 'AssignmentController', 'submit');
$router->post('assignments/grade', 'AssignmentController', 'grade');

// Quiz
$router->get('quiz', 'QuizController', 'show');
$router->post('quiz/start', 'QuizController', 'start');
$router->get('quiz/take', 'QuizController', 'take');
$router->post('quiz/submit', 'QuizController', 'submit');
$router->get('quiz/result', 'QuizController', 'result');
$router->get('quiz/create', 'QuizController', 'create');
$router->post('quiz/create', 'QuizController', 'create');

// Forum
$router->get('forum', 'ForumController', 'index');
$router->get('forum/thread', 'ForumController', 'thread');
$router->get('forum/create', 'ForumController', 'createThread');
$router->post('forum/create', 'ForumController', 'createThread');
$router->post('forum/reply', 'ForumController', 'reply');

// Admin
$router->get('admin', 'AdminController', 'index');
$router->get('admin/users', 'AdminController', 'users');
$router->get('admin/users/edit', 'AdminController', 'editUser');
$router->post('admin/users/edit', 'AdminController', 'editUser');
$router->post('admin/users/delete', 'AdminController', 'deleteUser');
$router->get('admin/courses', 'AdminController', 'courses');

// Notifications
$router->get('notifications/unread', 'NotificationController', 'getUnread');
$router->post('notifications/mark-read', 'NotificationController', 'markRead');
$router->post('notifications/mark-all-read', 'NotificationController', 'markAllRead');

// Dispatch the request
$router->dispatch();
