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
     * Edit Class
     */
    public function editClass($id = null) {
        if (!$id) {
            redirect('admin/classes');
        }

        $classModel = $this->model('ClassModel');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => clean($_POST['name'] ?? ''),
                'code' => clean($_POST['code'] ?? ''),
                'grade_level' => intval($_POST['grade_level'] ?? 10),
                'academic_year' => clean($_POST['academic_year'] ?? ''),
                'teacher_id' => $_POST['teacher_id'] ?? null,
                'description' => clean($_POST['description'] ?? ''),
                'status' => $_POST['status'] ?? 'active'
            ];

            $classModel->update($id, $data);
            flash('success', 'Cập nhật lớp học thành công!', 'success');
            redirect('admin/classes');
        }

        $class = $classModel->getClassWithStats($id);
        $teachers = $this->userModel->getTeachers();
        
        $this->view('admin/edit-class', [
            'title' => 'Sửa lớp học',
            'class' => $class,
            'teachers' => $teachers
        ]);
    }

    /**
     * Delete Class
     */
    public function deleteClass($id = null) {
        if ($id) {
            $classModel = $this->model('ClassModel');
            $classModel->delete($id);
            flash('success', 'Xóa lớp học thành công!', 'success');
        }
        redirect('admin/classes');
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
     * Backup Page
     */
    public function backup() {
        $this->view('admin/backup', ['title' => 'Sao lưu & Khôi phục']);
    }

    /**
     * Backup Database
     */
    public function backupDatabase() {
        try {
            $filename = 'elearning_backup_' . date('Y-m-d_His') . '.sql';
            
            // Get database config
            $dbHost = DB_HOST;
            $dbName = DB_NAME;
            $dbUser = DB_USER;
            $dbPass = DB_PASS;
            
            // Create backup directory if not exists
            $backupDir = ROOT . '/backups';
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }
            
            $backupFile = $backupDir . '/' . $filename;
            
            // Create mysqldump command
            $command = sprintf(
                'mysqldump --host=%s --user=%s --password=%s %s > %s',
                escapeshellarg($dbHost),
                escapeshellarg($dbUser),
                escapeshellarg($dbPass),
                escapeshellarg($dbName),
                escapeshellarg($backupFile)
            );
            
            // Execute backup
            exec($command, $output, $result);
            
            if ($result === 0 && file_exists($backupFile)) {
                // Download file
                header('Content-Type: application/sql');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Content-Length: ' . filesize($backupFile));
                readfile($backupFile);
                
                // Delete file after download
                unlink($backupFile);
                exit;
            } else {
                flash('error', 'Không thể tạo backup. Vui lòng kiểm tra mysqldump.', 'danger');
            }
        } catch (Exception $e) {
            flash('error', 'Lỗi: ' . $e->getMessage(), 'danger');
        }
        
        redirect('admin/backup');
    }

    /**
     * Export Users to Excel (CSV)
     */
    public function exportUsers() {
        $users = $this->userModel->getAll();
        
        $filename = 'users_export_' . date('Y-m-d_His') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Header
        fputcsv($output, ['ID', 'Họ tên', 'Email', 'Vai trò', 'Trạng thái', 'Ngày tạo']);
        
        // Data
        foreach ($users as $user) {
            fputcsv($output, [
                $user['id'],
                $user['full_name'],
                $user['email'],
                $user['role'],
                $user['status'],
                $user['created_at']
            ]);
        }
        
        fclose($output);
        exit;
    }

    /**
     * Export Courses to Excel (CSV)
     */
    public function exportCourses() {
        $courses = $this->courseModel->getAllWithDetails();
        
        $filename = 'courses_export_' . date('Y-m-d_His') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Header
        fputcsv($output, ['ID', 'Tiêu đề', 'Giáo viên', 'Môn học', 'Học sinh', 'Trạng thái', 'Ngày tạo']);
        
        // Data
        foreach ($courses as $course) {
            fputcsv($output, [
                $course['id'],
                $course['title'],
                $course['teacher_name'] ?? '',
                $course['subject_name'] ?? '',
                $course['enrollment_count'] ?? 0,
                $course['is_published'] ? 'Đã xuất bản' : 'Nháp',
                $course['created_at']
            ]);
        }
        
        fclose($output);
        exit;
    }

    /**
     * Export Students to Excel (CSV)
     */
    public function exportStudents() {
        $students = $this->userModel->getByRole('student');
        
        $filename = 'students_export_' . date('Y-m-d_His') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Header
        fputcsv($output, ['ID', 'Họ tên', 'Email', 'Trạng thái', 'Ngày đăng ký']);
        
        // Data
        foreach ($students as $student) {
            fputcsv($output, [
                $student['id'],
                $student['full_name'],
                $student['email'],
                $student['status'],
                $student['created_at']
            ]);
        }
        
        fclose($output);
        exit;
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
     * Delete Course
     */
    public function deleteCourse($id = null) {
        if ($id) {
            try {
                // Delete related data first
                $db = Database::getInstance();
                $db->query("DELETE FROM enrollments WHERE course_id = :id", ['id' => $id]);
                $db->query("DELETE FROM lessons WHERE course_id = :id", ['id' => $id]);
                $db->query("DELETE FROM assignments WHERE course_id = :id", ['id' => $id]);
                $db->query("DELETE FROM quizzes WHERE course_id = :id", ['id' => $id]);
                
                // Delete course
                $this->courseModel->delete($id);
                
                flash('success', 'Xóa khóa học thành công!', 'success');
            } catch (Exception $e) {
                flash('error', 'Có lỗi xảy ra: ' . $e->getMessage(), 'danger');
            }
        }
        redirect('admin/courses');
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
