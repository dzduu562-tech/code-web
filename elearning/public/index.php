<?php
require_once __DIR__ . '/../app/core/Auth.php';
require_once __DIR__ . '/../app/core/Helpers.php';
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/core/DB.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/CourseController.php';
require_once __DIR__ . '/../app/controllers/LessonController.php';
require_once __DIR__ . '/../app/controllers/AssignmentController.php';
require_once __DIR__ . '/../app/controllers/QuizController.php';
require_once __DIR__ . '/../app/controllers/ForumController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';
require_once __DIR__ . '/../app/controllers/NotificationController.php';
require_once __DIR__ . '/../app/controllers/TeacherController.php';
require_once __DIR__ . '/../app/controllers/SearchController.php';
require_once __DIR__ . '/../app/controllers/AdminUsersController.php';

Auth::startSecureSession();
// Load config; if missing, copy from sample
$configPath = __DIR__ . '/../config/config.php';
if (!file_exists($configPath)) {
    $sample = __DIR__ . '/../config/config.php.sample';
    if (file_exists($sample)) { copy($sample, $configPath); }
}

$router = new Router();

$router->get('/', function(){
    ob_start();
    require __DIR__ . '/../app/views/layouts/landing.php';
    $content = ob_get_clean();
    ob_start();
    require __DIR__ . '/../app/views/layouts/main.php';
    return ob_get_clean();
});

$auth = new AuthController();
$router->get('/login', fn() => $auth->showLogin());
$router->post('/login', fn() => $auth->login());
$router->get('/register', fn() => $auth->showRegister());
$router->post('/register', fn() => $auth->register());
$router->post('/logout', fn() => $auth->logout());

$dashboard = new DashboardController();
$router->get('/dashboard', fn() => $dashboard->index());

$course = new CourseController();
$router->get('/courses', fn() => $course->index());
$router->get('/course', fn() => $course->show());

$lesson = new LessonController();
$router->get('/lesson', fn() => $lesson->show());
$router->post('/lesson/mark', fn() => $lesson->markDone());

$assign = new AssignmentController();
$router->get('/assignments', fn() => $assign->index());
$router->post('/assignments/submit', fn() => $assign->submit());
$router->post('/assignments/grade', fn() => $assign->grade());

$quiz = new QuizController();
$router->get('/quiz', fn() => $quiz->take());
$router->post('/quiz/submit', fn() => $quiz->submit());

$forum = new ForumController();
$router->get('/forum', fn() => $forum->index());
$router->get('/thread', fn() => $forum->thread());
$router->post('/forum/create', fn() => $forum->createThread());
$router->post('/forum/reply', fn() => $forum->postReply());

$notif = new NotificationController();
$router->get('/notifications', fn() => $notif->index());
$router->get('/notifications/poll', fn() => $notif->poll());
$router->post('/notifications/read', fn() => $notif->markRead());

$teacher = new TeacherController();
$router->get('/teacher/courses', fn() => $teacher->courses());
$router->get('/teacher/course/new', fn() => $teacher->createForm());
$router->post('/teacher/course/save', fn() => $teacher->saveCourse());
$router->get('/teacher/course/manage', fn() => $teacher->manage());
$router->post('/teacher/chapter/save', fn() => $teacher->saveChapter());
$router->post('/teacher/lesson/save', fn() => $teacher->saveLesson());
$router->post('/teacher/lesson/delete', fn() => $teacher->deleteLesson());
$router->post('/teacher/resource/upload', fn() => $teacher->uploadResource());

$search = new SearchController();
$router->get('/stats', fn() => $search->stats());

$admin = new AdminUsersController();
$router->get('/admin', fn() => (new AdminController())->dashboard());
$router->get('/admin/users', fn() => $admin->index());

$router->dispatch();
