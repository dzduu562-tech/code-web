<?php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Helpers.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../models/Assignment.php';
require_once __DIR__ . '/../models/Notification.php';

class DashboardController {
    private $auth;
    private $courseModel;
    private $lessonModel;
    private $assignmentModel;
    private $notificationModel;
    
    public function __construct() {
        $this->auth = new Auth();
        $this->courseModel = new Course();
        $this->lessonModel = new Lesson();
        $this->assignmentModel = new Assignment();
        $this->notificationModel = new Notification();
    }
    
    public function index() {
        $this->auth->requireAuth();
        
        $user = $this->auth->user();
        
        if ($user['role'] === 'admin') {
            Helpers::redirect('/admin/dashboard');
        }
        
        $data = [
            'title' => 'Bảng điều khiển',
            'user' => $user
        ];
        
        if ($user['role'] === 'teacher') {
            $this->showTeacherDashboard($data);
        } else {
            $this->showStudentDashboard($data);
        }
    }
    
    private function showTeacherDashboard($data) {
        $user = $data['user'];
        
        // Get teacher's courses
        $courses = $this->courseModel->getByTeacher($user['id'], 1, 10);
        $totalCourses = $this->courseModel->getTotalCount();
        
        // Get recent assignments
        $assignments = $this->assignmentModel->getByTeacher($user['id'], 1, 5);
        
        // Get notifications
        $notifications = $this->notificationModel->getByUser($user['id'], 1, 5);
        $unreadCount = $this->notificationModel->getUnreadCount($user['id']);
        
        $data['courses'] = $courses;
        $data['total_courses'] = $totalCourses;
        $data['assignments'] = $assignments;
        $data['notifications'] = $notifications;
        $data['unread_count'] = $unreadCount;
        
        include __DIR__ . '/../views/dashboard/teacher.php';
    }
    
    private function showStudentDashboard($data) {
        $user = $data['user'];
        
        // Get enrolled courses
        $courses = $this->courseModel->getEnrolledCourses($user['id'], 1, 10);
        
        // Get recent assignments
        $assignments = $this->assignmentModel->getByStudent($user['id'], 1, 5);
        $upcomingAssignments = $this->assignmentModel->getUpcomingAssignments($user['id'], 5);
        $overdueAssignments = $this->assignmentModel->getOverdueAssignments($user['id']);
        
        // Get notifications
        $notifications = $this->notificationModel->getByUser($user['id'], 1, 5);
        $unreadCount = $this->notificationModel->getUnreadCount($user['id']);
        
        $data['courses'] = $courses;
        $data['assignments'] = $assignments;
        $data['upcoming_assignments'] = $upcomingAssignments;
        $data['overdue_assignments'] = $overdueAssignments;
        $data['notifications'] = $notifications;
        $data['unread_count'] = $unreadCount;
        
        include __DIR__ . '/../views/dashboard/student.php';
    }
    
    public function notifications() {
        $this->auth->requireAuth();
        
        $user = $this->auth->user();
        $page = (int)($_GET['page'] ?? 1);
        $unreadOnly = isset($_GET['unread']);
        
        $notifications = $this->notificationModel->getByUser($user['id'], $page, ITEMS_PER_PAGE, $unreadOnly);
        $totalNotifications = $this->notificationModel->getNotificationStats($user['id']);
        
        $pagination = Helpers::paginate($totalNotifications['total'], $page);
        
        $data = [
            'title' => 'Thông báo',
            'notifications' => $notifications,
            'pagination' => $pagination,
            'unread_only' => $unreadOnly,
            'stats' => $totalNotifications
        ];
        
        include __DIR__ . '/../views/dashboard/notifications.php';
    }
    
    public function markNotificationRead() {
        $this->auth->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('/notifications');
        }
        
        if (!$this->auth->verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            Helpers::jsonResponse(['success' => false, 'message' => 'Token không hợp lệ'], 400);
        }
        
        $notificationId = (int)($_POST['notification_id'] ?? 0);
        $user = $this->auth->user();
        
        if ($this->notificationModel->markAsRead($notificationId, $user['id'])) {
            Helpers::jsonResponse(['success' => true, 'message' => 'Đã đánh dấu đã đọc']);
        } else {
            Helpers::jsonResponse(['success' => false, 'message' => 'Có lỗi xảy ra'], 500);
        }
    }
    
    public function markAllNotificationsRead() {
        $this->auth->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('/notifications');
        }
        
        if (!$this->auth->verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            Helpers::jsonResponse(['success' => false, 'message' => 'Token không hợp lệ'], 400);
        }
        
        $user = $this->auth->user();
        
        if ($this->notificationModel->markAllAsRead($user['id'])) {
            Helpers::jsonResponse(['success' => true, 'message' => 'Đã đánh dấu tất cả đã đọc']);
        } else {
            Helpers::jsonResponse(['success' => false, 'message' => 'Có lỗi xảy ra'], 500);
        }
    }
    
    public function getNotifications() {
        $this->auth->requireAuth();
        
        $user = $this->auth->user();
        $notifications = $this->notificationModel->getByUser($user['id'], 1, 10);
        $unreadCount = $this->notificationModel->getUnreadCount($user['id']);
        
        Helpers::jsonResponse([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }
}