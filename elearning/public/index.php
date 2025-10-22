<?php
// E-Learning Platform Entry Point

// Set error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Include core classes
require_once __DIR__ . '/../app/core/DB.php';
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/core/Auth.php';
require_once __DIR__ . '/../app/core/Helpers.php';

// Check if config file exists
$configFile = __DIR__ . '/../config/config.php';
if (!file_exists($configFile)) {
    die('Configuration file not found. Please copy config.php.sample to config.php and update settings.');
}

try {
    // Initialize router
    $router = new Router();

    // Define routes
    
    // Home routes
    $router->get('', 'HomeController@index');
    $router->get('home', 'HomeController@index');
    $router->get('about', 'HomeController@about');
    $router->get('contact', 'HomeController@contact');
    $router->post('contact', 'HomeController@contact');
    $router->get('search', 'HomeController@search');
    $router->get('privacy', 'HomeController@privacy');
    $router->get('terms', 'HomeController@terms');

    // Auth routes
    $router->get('login', 'AuthController@login');
    $router->post('login', 'AuthController@login');
    $router->get('register', 'AuthController@register');
    $router->post('register', 'AuthController@register');
    $router->get('logout', 'AuthController@logout');
    $router->get('forgot-password', 'AuthController@forgotPassword');
    $router->post('forgot-password', 'AuthController@forgotPassword');
    $router->get('reset-password', 'AuthController@resetPassword');
    $router->post('reset-password', 'AuthController@resetPassword');
    $router->get('profile', 'AuthController@profile');
    $router->post('profile', 'AuthController@profile');
    $router->get('change-password', 'AuthController@changePassword');
    $router->post('change-password', 'AuthController@changePassword');

    // Dashboard routes
    $router->get('dashboard', 'DashboardController@index');
    $router->get('notifications', 'DashboardController@notifications');
    $router->post('notifications', 'DashboardController@notifications');
    $router->get('api/notifications', 'DashboardController@getNotifications');
    $router->get('api/stats', 'DashboardController@stats');

    // Course routes
    $router->get('courses', 'CourseController@index');
    $router->get('course', 'CourseController@show');
    $router->post('course/enroll', 'CourseController@enroll');
    $router->get('course/create', 'CourseController@create');
    $router->post('course/create', 'CourseController@store');
    $router->get('course/edit', 'CourseController@edit');
    $router->post('course/edit', 'CourseController@update');
    $router->post('course/delete', 'CourseController@delete');
    $router->get('course/students', 'CourseController@students');

    // Lesson routes
    $router->get('lesson', 'LessonController@show');
    $router->get('lesson/create', 'LessonController@create');
    $router->post('lesson/create', 'LessonController@store');
    $router->get('lesson/edit', 'LessonController@edit');
    $router->post('lesson/edit', 'LessonController@update');
    $router->post('lesson/delete', 'LessonController@delete');
    $router->post('lesson/complete', 'LessonController@markComplete');
    $router->post('lesson/upload', 'LessonController@uploadResource');

    // Assignment routes
    $router->get('assignments', 'AssignmentController@index');
    $router->get('assignment', 'AssignmentController@show');
    $router->get('assignment/create', 'AssignmentController@create');
    $router->post('assignment/create', 'AssignmentController@store');
    $router->get('assignment/edit', 'AssignmentController@edit');
    $router->post('assignment/edit', 'AssignmentController@update');
    $router->post('assignment/delete', 'AssignmentController@delete');
    $router->post('assignment/submit', 'AssignmentController@submit');
    $router->post('assignment/grade', 'AssignmentController@grade');

    // Quiz routes
    $router->get('quiz', 'QuizController@show');
    $router->get('quiz/take', 'QuizController@take');
    $router->post('quiz/submit', 'QuizController@submit');
    $router->get('quiz/result', 'QuizController@result');
    $router->get('quiz/create', 'QuizController@create');
    $router->post('quiz/create', 'QuizController@store');
    $router->get('quiz/edit', 'QuizController@edit');
    $router->post('quiz/edit', 'QuizController@update');
    $router->post('quiz/delete', 'QuizController@delete');

    // Forum routes
    $router->get('forum', 'ForumController@index');
    $router->get('thread', 'ForumController@show');
    $router->get('thread/create', 'ForumController@create');
    $router->post('thread/create', 'ForumController@store');
    $router->post('thread/reply', 'ForumController@reply');
    $router->post('thread/edit', 'ForumController@edit');
    $router->post('thread/delete', 'ForumController@delete');

    // Admin routes
    $router->get('admin', 'AdminController@index');
    $router->get('admin/users', 'AdminController@users');
    $router->get('admin/courses', 'AdminController@courses');
    $router->get('admin/reports', 'AdminController@reports');
    $router->get('admin/settings', 'AdminController@settings');
    $router->post('admin/settings', 'AdminController@updateSettings');

    // API routes for AJAX
    $router->post('api/upload', 'ApiController@upload');
    $router->get('api/search', 'ApiController@search');

    // Dispatch the request
    $router->dispatch();

} catch (Exception $e) {
    // Log error in production
    error_log("Application Error: " . $e->getMessage());
    
    // Show error page
    http_response_code(500);
    include __DIR__ . '/../app/views/errors/500.php';
}