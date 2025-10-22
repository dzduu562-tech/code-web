<?php
// Start session
session_start();

// Include configuration
require_once __DIR__ . '/../config/config.php';

// Include core classes
require_once __DIR__ . '/../app/core/DB.php';
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/core/Auth.php';
require_once __DIR__ . '/../app/core/Helpers.php';

// Include models
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/models/Course.php';
require_once __DIR__ . '/../app/models/Lesson.php';
require_once __DIR__ . '/../app/models/Quiz.php';
require_once __DIR__ . '/../app/models/Assignment.php';
require_once __DIR__ . '/../app/models/Forum.php';
require_once __DIR__ . '/../app/models/Notification.php';

// Include controllers
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/CourseController.php';
require_once __DIR__ . '/../app/controllers/LessonController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';

// Create router instance
$router = new Router();

// Define routes
$router->get('/', function() {
    $courseModel = new Course();
    $userModel = new User();
    
    $stats = [
        'total_courses' => $courseModel->getTotalCount(true),
        'total_teachers' => $userModel->getCountByRole('teacher'),
        'total_students' => $userModel->getCountByRole('student')
    ];
    
    $recent_courses = $courseModel->getRecentCourses(6);
    
    $data = [
        'title' => 'Trang chủ',
        'stats' => $stats,
        'recent_courses' => $recent_courses
    ];
    
    include __DIR__ . '/../app/views/home.php';
});

// Auth routes
$router->get('/login', 'Auth@showLogin');
$router->post('/login', 'Auth@login');
$router->get('/register', 'Auth@showRegister');
$router->post('/register', 'Auth@register');
$router->get('/logout', 'Auth@logout');
$router->get('/profile', 'Auth@showProfile');
$router->post('/profile', 'Auth@updateProfile');
$router->get('/change-password', 'Auth@showChangePassword');
$router->post('/change-password', 'Auth@changePassword');

// Course routes
$router->get('/courses', 'Course@index');
$router->get('/course', 'Course@show');
$router->post('/course/enroll', 'Course@enroll');
$router->post('/course/unenroll', 'Course@unenroll');
$router->get('/course/create', 'Course@create');
$router->post('/course/create', 'Course@store');
$router->get('/course/edit', 'Course@edit');
$router->post('/course/edit', 'Course@update');
$router->post('/course/delete', 'Course@delete');

// Lesson routes
$router->get('/lesson', 'Lesson@show');
$router->post('/lesson/mark-completed', 'Lesson@markCompleted');
$router->get('/lesson/create', 'Lesson@create');
$router->post('/lesson/create', 'Lesson@store');
$router->get('/lesson/edit', 'Lesson@edit');
$router->post('/lesson/edit', 'Lesson@update');
$router->post('/lesson/delete', 'Lesson@delete');

// Dashboard routes
$router->get('/dashboard', 'Dashboard@index');
$router->get('/notifications', 'Dashboard@notifications');
$router->post('/notifications/mark-read', 'Dashboard@markNotificationRead');
$router->post('/notifications/mark-all-read', 'Dashboard@markAllNotificationsRead');

// API routes
$router->get('/api/notifications', 'Dashboard@getNotifications');

// Admin routes
$router->get('/admin/dashboard', 'Admin@dashboard');
$router->get('/admin/users', 'Admin@users');
$router->get('/admin/users/create', 'Admin@createUser');
$router->post('/admin/users/create', 'Admin@storeUser');
$router->get('/admin/users/edit', 'Admin@editUser');
$router->post('/admin/users/edit', 'Admin@updateUser');
$router->post('/admin/users/delete', 'Admin@deleteUser');
$router->get('/admin/courses', 'Admin@courses');
$router->post('/admin/courses/publish', 'Admin@publishCourse');
$router->post('/admin/courses/unpublish', 'Admin@unpublishCourse');
$router->post('/admin/courses/delete', 'Admin@deleteCourse');

// Add middleware
$router->middleware('auth', function() {
    $auth = new Auth();
    if (!$auth->check()) {
        Helpers::redirect('/login');
        return false;
    }
    return true;
});

$router->middleware('admin', function() {
    $auth = new Auth();
    if (!$auth->isAdmin()) {
        Helpers::redirect('/dashboard');
        return false;
    }
    return true;
});

$router->middleware('teacher', function() {
    $auth = new Auth();
    if (!$auth->isTeacher()) {
        Helpers::redirect('/dashboard');
        return false;
    }
    return true;
});

// Apply middleware to protected routes
$protectedRoutes = [
    '/dashboard', '/profile', '/change-password', '/course/create', '/course/edit', 
    '/lesson/create', '/lesson/edit', '/notifications', '/admin'
];

foreach ($protectedRoutes as $route) {
    if (strpos($_SERVER['REQUEST_URI'], $route) === 0) {
        $router->middleware('auth', function() {
            $auth = new Auth();
            if (!$auth->check()) {
                Helpers::redirect('/login');
                return false;
            }
            return true;
        });
        break;
    }
}

// Apply admin middleware to admin routes
if (strpos($_SERVER['REQUEST_URI'], '/admin') === 0) {
    $router->middleware('admin', function() {
        $auth = new Auth();
        if (!$auth->isAdmin()) {
            Helpers::redirect('/dashboard');
            return false;
        }
        return true;
    });
}

// Dispatch the request
$router->dispatch();
?>