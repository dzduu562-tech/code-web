<?php

require_once __DIR__ . '/../core/DB.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Helpers.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../models/Assignment.php';
require_once __DIR__ . '/../models/Quiz.php';
require_once __DIR__ . '/../models/Forum.php';

class DashboardController {
    private $auth;
    private $userModel;
    private $courseModel;
    private $lessonModel;
    private $assignmentModel;
    private $quizModel;
    private $forumModel;

    public function __construct() {
        $this->auth = Auth::getInstance();
        $this->userModel = new User();
        $this->courseModel = new Course();
        $this->lessonModel = new Lesson();
        $this->assignmentModel = new Assignment();
        $this->quizModel = new Quiz();
        $this->forumModel = new Forum();
    }

    public function index($params = []) {
        $this->auth->requireAuth();

        $user = $this->auth->user();
        $role = $this->auth->role();

        switch ($role) {
            case 'admin':
                return $this->adminDashboard();
            case 'teacher':
                return $this->teacherDashboard();
            case 'student':
                return $this->studentDashboard();
            default:
                $router = new Router();
                $router->redirect($router->url('login'));
        }
    }

    private function adminDashboard() {
        // Get system statistics
        $userStats = $this->userModel->getStats();
        $courseStats = $this->courseModel->getStats();
        $lessonStats = $this->lessonModel->getStats();
        $forumStats = $this->forumModel->getForumStats();

        // Get recent activities
        $recentUsers = $this->userModel->getAll([
            'limit' => 5,
            'offset' => 0
        ]);

        $recentCourses = $this->courseModel->getAll([
            'limit' => 5,
            'offset' => 0
        ]);

        $recentForumActivity = $this->forumModel->getRecentActivity(null, 10);

        // Get popular courses
        $popularCourses = $this->courseModel->getPopularCourses(5);

        $data = [
            'title' => 'Bảng điều khiển Admin - E-Learning Platform',
            'user_stats' => $userStats,
            'course_stats' => $courseStats,
            'lesson_stats' => $lessonStats,
            'forum_stats' => $forumStats,
            'recent_users' => $recentUsers,
            'recent_courses' => $recentCourses,
            'recent_forum_activity' => $recentForumActivity,
            'popular_courses' => $popularCourses
        ];

        $this->render('dashboard/admin', $data);
    }

    private function teacherDashboard() {
        $teacherId = $this->auth->id();

        // Get teacher's courses
        $myCourses = $this->courseModel->getAll([
            'teacher_id' => $teacherId,
            'limit' => 10
        ]);

        // Get teacher's assignments
        $myAssignments = $this->assignmentModel->getTeacherAssignments($teacherId);

        // Get recent submissions to grade
        $pendingSubmissions = [];
        foreach ($myAssignments as $assignment) {
            $submissions = $this->assignmentModel->getSubmissions($assignment['id'], ['graded' => false]);
            $pendingSubmissions = array_merge($pendingSubmissions, $submissions);
        }
        
        // Sort by submission date and limit
        usort($pendingSubmissions, function($a, $b) {
            return strtotime($b['submitted_at']) - strtotime($a['submitted_at']);
        });
        $pendingSubmissions = array_slice($pendingSubmissions, 0, 10);

        // Get teacher statistics
        $stats = [
            'total_courses' => count($myCourses),
            'published_courses' => count(array_filter($myCourses, function($c) { return $c['is_published']; })),
            'total_students' => array_sum(array_column($myCourses, 'student_count')),
            'pending_submissions' => count($pendingSubmissions)
        ];

        // Get recent forum activity in teacher's courses
        $courseIds = array_column($myCourses, 'id');
        $recentForumActivity = [];
        foreach ($courseIds as $courseId) {
            $threads = $this->forumModel->getThreads([
                'course_id' => $courseId,
                'limit' => 3
            ]);
            $recentForumActivity = array_merge($recentForumActivity, $threads);
        }

        // Sort by updated date
        usort($recentForumActivity, function($a, $b) {
            return strtotime($b['updated_at']) - strtotime($a['updated_at']);
        });
        $recentForumActivity = array_slice($recentForumActivity, 0, 5);

        $data = [
            'title' => 'Bảng điều khiển Giáo viên - E-Learning Platform',
            'my_courses' => $myCourses,
            'my_assignments' => array_slice($myAssignments, 0, 5),
            'pending_submissions' => $pendingSubmissions,
            'stats' => $stats,
            'recent_forum_activity' => $recentForumActivity
        ];

        $this->render('dashboard/teacher', $data);
    }

    private function studentDashboard() {
        $studentId = $this->auth->id();

        // Get enrolled courses
        $enrolledCourses = $this->userModel->getEnrolledCourses($studentId);

        // Get recent assignments
        $assignments = $this->assignmentModel->getStudentAssignments($studentId);
        $recentAssignments = array_slice($assignments, 0, 5);

        // Get upcoming assignments
        $upcomingAssignments = $this->assignmentModel->getUpcomingAssignments($studentId, 5);

        // Get recent activity
        $recentActivity = $this->userModel->getRecentActivity($studentId, 10);

        // Calculate statistics
        $stats = [
            'enrolled_courses' => count($enrolledCourses),
            'completed_courses' => count(array_filter($enrolledCourses, function($c) { 
                return $c['progress_percent'] >= 100; 
            })),
            'total_assignments' => count($assignments),
            'completed_assignments' => count(array_filter($assignments, function($a) { 
                return !empty($a['submission_id']); 
            })),
            'avg_progress' => count($enrolledCourses) > 0 ? 
                array_sum(array_column($enrolledCourses, 'progress_percent')) / count($enrolledCourses) : 0
        ];

        // Get recent forum activity
        $recentForumActivity = $this->forumModel->getRecentActivity($studentId, 5);

        // Get notifications
        $notifications = [];
        $db = DB::getInstance();
        $notifications = $db->fetchAll(
            "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 5",
            [$studentId]
        );

        $data = [
            'title' => 'Bảng điều khiển Học sinh - E-Learning Platform',
            'enrolled_courses' => $enrolledCourses,
            'recent_assignments' => $recentAssignments,
            'upcoming_assignments' => $upcomingAssignments,
            'recent_activity' => $recentActivity,
            'stats' => $stats,
            'recent_forum_activity' => $recentForumActivity,
            'notifications' => $notifications
        ];

        $this->render('dashboard/student', $data);
    }

    public function notifications($params = []) {
        $this->auth->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleNotificationAction();
        }

        $userId = $this->auth->id();
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 20;

        $db = DB::getInstance();
        
        $totalNotifications = $db->count('notifications', 'user_id = ?', [$userId]);
        $notifications = $db->fetchAll(
            "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$userId, $perPage, ($page - 1) * $perPage]
        );

        $pagination = Helpers::paginate($totalNotifications, $page, $perPage);

        $data = [
            'title' => 'Thông báo - E-Learning Platform',
            'notifications' => $notifications,
            'pagination' => $pagination
        ];

        $this->render('dashboard/notifications', $data);
    }

    private function handleNotificationAction() {
        $action = $_POST['action'] ?? '';
        $notificationId = (int)($_POST['notification_id'] ?? 0);
        $userId = $this->auth->id();

        switch ($action) {
            case 'mark_read':
                Helpers::markNotificationAsRead($notificationId, $userId);
                break;
            case 'mark_all_read':
                $db = DB::getInstance();
                $db->update('notifications', ['is_read' => 1], 'user_id = ?', [$userId]);
                break;
        }

        Helpers::json(['success' => true]);
    }

    public function getNotifications($params = []) {
        $this->auth->requireAuth();

        $userId = $this->auth->id();
        $limit = min(20, (int)($_GET['limit'] ?? 10));

        $db = DB::getInstance();
        $notifications = $db->fetchAll(
            "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?",
            [$userId, $limit]
        );

        $unreadCount = Helpers::getUnreadNotificationCount($userId);

        Helpers::json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    public function stats($params = []) {
        $this->auth->requireAuth();

        $role = $this->auth->role();
        $userId = $this->auth->id();

        switch ($role) {
            case 'admin':
                $stats = [
                    'users' => $this->userModel->getStats(),
                    'courses' => $this->courseModel->getStats(),
                    'lessons' => $this->lessonModel->getStats(),
                    'forum' => $this->forumModel->getForumStats()
                ];
                break;

            case 'teacher':
                $myCourses = $this->courseModel->getAll(['teacher_id' => $userId]);
                $courseStats = [];
                foreach ($myCourses as $course) {
                    $courseStats[] = $this->courseModel->getStats($course['id']);
                }
                
                $stats = [
                    'courses' => $courseStats,
                    'total_students' => array_sum(array_column($myCourses, 'student_count'))
                ];
                break;

            case 'student':
                $stats = $this->userModel->getStats($userId);
                break;

            default:
                $stats = [];
        }

        Helpers::json($stats);
    }

    private function render($view, $data = []) {
        // Extract data to variables
        extract($data);

        // Include layout
        include __DIR__ . '/../views/layouts/main.php';
    }
}