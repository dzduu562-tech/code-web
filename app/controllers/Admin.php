<?php
/**
 * Admin Controller - Admin Dashboard & System Management
 */

class Admin extends Controller {
    private $userModel;
    private $courseModel;

    public function __construct() {
        $this->requireRole('admin');
        $this->userModel = $this->model('User');
        $this->courseModel = $this->model('Course');
    }

    /**
     * Default index - redirect to dashboard
     */
    public function index() {
        redirect('admin/dashboard');
    }

    /**
     * Admin Dashboard
     */
    public function dashboard() {
        $data = [
            'title' => 'Dashboard - Admin',
            'stats' => $this->getSystemStats(),
            'recent_users' => $this->getRecentUsers(10),
            'recent_activities' => $this->getRecentActivities(15)
        ];

        $this->view('admin/dashboard', $data);
    }

    /**
     * Manage Users
     */
    public function users() {
        $users = $this->userModel->getAll('created_at DESC');

        $data = [
            'title' => 'Quản lý người dùng',
            'users' => $users
        ];

        $this->view('admin/users', $data);
    }

    /**
     * Manage Courses
     */
    public function courses() {
        $db = Database::getInstance();
        $courses = $db->query("SELECT c.*, u.full_name as teacher_name, s.name as subject_name 
                               FROM courses c 
                               LEFT JOIN users u ON c.teacher_id = u.id 
                               LEFT JOIN subjects s ON c.subject_id = s.id 
                               ORDER BY c.created_at DESC")->fetchAll();

        $data = [
            'title' => 'Quản lý khóa học',
            'courses' => $courses
        ];

        $this->view('admin/courses', $data);
    }

    /**
     * System Settings
     */
    public function settings() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->saveSettings();
        }

        $db = Database::getInstance();
        $settings = $db->query("SELECT * FROM settings")->fetchAll();
        $settingsArray = [];
        foreach ($settings as $setting) {
            $settingsArray[$setting['setting_key']] = $setting['setting_value'];
        }

        $data = [
            'title' => 'Cài đặt hệ thống',
            'settings' => $settingsArray
        ];

        $this->view('admin/settings', $data);
    }

    /**
     * Save settings
     */
    private function saveSettings() {
        $db = Database::getInstance();
        
        foreach ($_POST as $key => $value) {
            $sql = "UPDATE settings SET setting_value = :value WHERE setting_key = :key";
            $db->query($sql, ['value' => $value, 'key' => $key]);
        }

        flash('success', 'Cài đặt đã được lưu!', 'success');
        redirect('admin/settings');
    }

    /**
     * Get system stats
     */
    private function getSystemStats() {
        $db = Database::getInstance();
        
        // Count by role
        $students = $db->query("SELECT COUNT(*) as total FROM users WHERE role = 'student'")->fetch()['total'];
        $teachers = $db->query("SELECT COUNT(*) as total FROM users WHERE role = 'teacher'")->fetch()['total'];
        $courses = $db->query("SELECT COUNT(*) as total FROM courses")->fetch()['total'];
        $enrollments = $db->query("SELECT COUNT(*) as total FROM enrollments WHERE status = 'active'")->fetch()['total'];
        
        return [
            'students' => $students,
            'teachers' => $teachers,
            'courses' => $courses,
            'enrollments' => $enrollments
        ];
    }

    /**
     * Get recent users
     */
    private function getRecentUsers($limit = 10) {
        $sql = "SELECT * FROM users ORDER BY created_at DESC LIMIT {$limit}";
        return $this->userModel->query($sql)->fetchAll();
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities($limit = 15) {
        $db = Database::getInstance();
        $sql = "SELECT al.*, u.full_name as user_name 
                FROM activity_logs al 
                LEFT JOIN users u ON al.user_id = u.id 
                ORDER BY al.created_at DESC 
                LIMIT {$limit}";
        return $db->query($sql)->fetchAll();
    }
}
