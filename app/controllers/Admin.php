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
     * Add User
     */
    public function addUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processAddUser();
        }

        $this->view('admin/add-user', ['title' => 'Thêm người dùng']);
    }

    /**
     * Process add user
     */
    private function processAddUser() {
        $data = [
            'email' => clean($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'full_name' => clean($_POST['full_name'] ?? ''),
            'role' => $_POST['role'] ?? 'student',
            'status' => 'active'
        ];

        try {
            $this->userModel->create($data);
            flash('success', 'Thêm người dùng thành công!', 'success');
        } catch (Exception $e) {
            flash('error', 'Có lỗi xảy ra: ' . $e->getMessage(), 'danger');
        }

        redirect('admin/users');
    }

    /**
     * Delete User
     */
    public function deleteUser($id = null) {
        if ($id && $id != $_SESSION['user_id']) {
            $this->userModel->delete($id);
            flash('success', 'Xóa người dùng thành công!', 'success');
        }
        redirect('admin/users');
    }

    /**
     * Manage Subjects
     */
    public function subjects() {
        $subjectModel = $this->model('Subject');
        $subjects = $subjectModel->getAll('name ASC');

        $data = [
            'title' => 'Quản lý môn học',
            'subjects' => $subjects
        ];

        $this->view('admin/subjects', $data);
    }

    /**
     * Add Subject
     */
    public function addSubject() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subjectModel = $this->model('Subject');
            
            $data = [
                'name' => clean($_POST['name'] ?? ''),
                'code' => clean($_POST['code'] ?? ''),
                'description' => clean($_POST['description'] ?? ''),
                'color' => $_POST['color'] ?? '#3B82F6',
                'icon' => clean($_POST['icon'] ?? 'book'),
                'status' => 'active'
            ];

            $subjectModel->create($data);
            flash('success', 'Thêm môn học thành công!', 'success');
            redirect('admin/subjects');
        }

        $this->view('admin/add-subject', ['title' => 'Thêm môn học']);
    }

    /**
     * Manage Classes
     */
    public function classes() {
        $classModel = $this->model('ClassModel');
        $classes = $classModel->getActiveClasses();

        $data = [
            'title' => 'Quản lý lớp học',
            'classes' => $classes
        ];

        $this->view('admin/classes', $data);
    }

    /**
     * Add Class
     */
    public function addClass() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $classModel = $this->model('ClassModel');
            
            $data = [
                'name' => clean($_POST['name'] ?? ''),
                'code' => clean($_POST['code'] ?? ''),
                'grade_level' => intval($_POST['grade_level'] ?? 10),
                'academic_year' => clean($_POST['academic_year'] ?? date('Y') . '-' . (date('Y') + 1)),
                'teacher_id' => $_POST['teacher_id'] ?? null,
                'description' => clean($_POST['description'] ?? ''),
                'status' => 'active'
            ];

            $classModel->insert($data);
            flash('success', 'Thêm lớp học thành công!', 'success');
            redirect('admin/classes');
        }

        $teachers = $this->userModel->getTeachers();
        $this->view('admin/add-class', [
            'title' => 'Thêm lớp học',
            'teachers' => $teachers
        ]);
    }

    /**
     * Reports
     */
    public function reports() {
        $data = [
            'title' => 'Báo cáo & Thống kê',
            'stats' => $this->getDetailedStats()
        ];

        $this->view('admin/reports', $data);
    }

    /**
     * Activity Logs
     */
    public function logs() {
        $db = Database::getInstance();
        $logs = $db->query("SELECT al.*, u.full_name as user_name 
                           FROM activity_logs al 
                           LEFT JOIN users u ON al.user_id = u.id 
                           ORDER BY al.created_at DESC 
                           LIMIT 100")->fetchAll();

        $data = [
            'title' => 'Nhật ký hoạt động',
            'logs' => $logs
        ];

        $this->view('admin/logs', $data);
    }

    /**
     * Get detailed stats
     */
    private function getDetailedStats() {
        $db = Database::getInstance();
        
        return [
            'total_courses' => $db->query("SELECT COUNT(*) as c FROM courses")->fetch()['c'],
            'total_lessons' => $db->query("SELECT COUNT(*) as c FROM lessons")->fetch()['c'],
            'total_quizzes' => $db->query("SELECT COUNT(*) as c FROM quizzes")->fetch()['c'],
            'total_assignments' => $db->query("SELECT COUNT(*) as c FROM assignments")->fetch()['c']
        ];
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
