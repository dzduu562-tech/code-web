<?php
/**
 * Student Controller - Student Dashboard & Features
 */

class Student extends Controller {
    private $userModel;
    private $courseModel;

    public function __construct() {
        $this->requireRole('student');
        $this->userModel = $this->model('User');
        $this->courseModel = $this->model('Course');
    }

    /**
     * Default index - redirect to dashboard
     */
    public function index() {
        redirect('student/dashboard');
    }

    /**
     * Student Dashboard
     */
    public function dashboard() {
        $studentId = $_SESSION['user_id'];
        
        // Get user info with fallback
        $user = $this->userModel->getUserWithProfile($studentId);
        if (!$user) {
            $user = [
                'id' => $studentId,
                'full_name' => $_SESSION['user_name'] ?? 'User',
                'email' => $_SESSION['user_email'] ?? '',
                'role' => $_SESSION['role'] ?? 'student',
                'avatar' => $_SESSION['avatar'] ?? '',
                'profile' => [
                    'total_xp' => 0,
                    'level' => 1
                ]
            ];
        }
        
        $data = [
            'title' => 'Dashboard - Học sinh',
            'user' => $user,
            'my_courses' => $this->courseModel->getStudentCourses($studentId),
            'stats' => $this->getStudentStats($studentId),
            'recent_activities' => $this->getRecentActivities($studentId),
            'notifications' => $this->getNotifications($studentId, 5)
        ];

        $this->view('student/dashboard', $data);
    }

    /**
     * My Courses
     */
    public function myCourses() {
        $studentId = $_SESSION['user_id'];
        
        $data = [
            'title' => 'Khóa học của tôi',
            'courses' => $this->courseModel->getStudentCourses($studentId)
        ];

        $this->view('student/my-courses', $data);
    }

    /**
     * Assignments
     */
    public function assignments() {
        $studentId = $_SESSION['user_id'];
        
        $data = [
            'title' => 'Bài tập',
            'assignments' => [] // TODO: Get student assignments
        ];

        $this->view('student/assignments', $data);
    }

    /**
     * Quizzes
     */
    public function quizzes() {
        $studentId = $_SESSION['user_id'];
        
        $data = [
            'title' => 'Kiểm tra',
            'quizzes' => [] // TODO: Get student quizzes
        ];

        $this->view('student/quizzes', $data);
    }

    /**
     * Calendar
     */
    public function calendar() {
        $studentId = $_SESSION['user_id'];
        
        $data = [
            'title' => 'Lịch học',
            'events' => [] // TODO: Get calendar events
        ];

        $this->view('student/calendar', $data);
    }

    /**
     * View course
     */
    public function course($slug = null) {
        if (!$slug) {
            redirect('student/my-courses');
        }

        $studentId = $_SESSION['user_id'];
        $course = $this->courseModel->getBySlug($slug);

        if (!$course) {
            flash('error', 'Khóa học không tồn tại', 'danger');
            redirect('student/my-courses');
        }

        // Check enrollment
        if (!$this->courseModel->isStudentEnrolled($course['id'], $studentId)) {
            flash('error', 'Bạn chưa đăng ký khóa học này', 'warning');
            redirect('courses/' . $slug);
        }

        $data = [
            'title' => $course['title'],
            'course' => $course,
            'lessons' => $this->courseModel->getLessons($course['id']),
            'progress' => $this->courseModel->getCourseProgress($course['id'], $studentId)
        ];

        $this->view('student/course', $data);
    }

    /**
     * Student progress
     */
    public function progress() {
        $studentId = $_SESSION['user_id'];
        
        $data = [
            'title' => 'Tiến độ học tập',
            'courses' => $this->courseModel->getStudentCourses($studentId),
            'stats' => $this->getProgressStats($studentId)
        ];

        $this->view('student/progress', $data);
    }

    /**
     * Badges & Achievements
     */
    public function badges() {
        $studentId = $_SESSION['user_id'];
        $db = Database::getInstance();
        
        // Get earned badges
        $earnedSql = "SELECT b.*, ub.earned_at 
                      FROM user_badges ub
                      INNER JOIN badges b ON ub.badge_id = b.id
                      WHERE ub.user_id = :user_id
                      ORDER BY ub.earned_at DESC";
        $earned = $db->query($earnedSql, ['user_id' => $studentId])->fetchAll();
        
        // Get available badges
        $availableSql = "SELECT * FROM badges 
                         WHERE is_active = 1 
                         AND id NOT IN (SELECT badge_id FROM user_badges WHERE user_id = :user_id)
                         ORDER BY xp_required ASC";
        $available = $db->query($availableSql, ['user_id' => $studentId])->fetchAll();
        
        $data = [
            'title' => 'Huy hiệu & Thành tích',
            'earned_badges' => $earned,
            'available_badges' => $available,
            'user' => $this->userModel->getUserWithProfile($studentId)
        ];

        $this->view('student/badges', $data);
    }

    /**
     * Get student stats
     */
    private function getStudentStats($studentId) {
        $db = Database::getInstance();
        
        // Total courses
        $coursesQuery = $db->query("SELECT COUNT(*) as total FROM enrollments WHERE student_id = :id AND status = 'active'", 
            ['id' => $studentId]);
        $totalCourses = $coursesQuery->fetch()['total'];
        
        // Completed courses
        $completedQuery = $db->query("SELECT COUNT(*) as total FROM enrollments WHERE student_id = :id AND status = 'completed'", 
            ['id' => $studentId]);
        $completedCourses = $completedQuery->fetch()['total'];
        
        // Total XP
        $xpQuery = $db->query("SELECT total_xp, level FROM student_profiles WHERE user_id = :id", 
            ['id' => $studentId]);
        $xpData = $xpQuery->fetch();
        
        // Badges
        $badgesQuery = $db->query("SELECT COUNT(*) as total FROM user_badges WHERE user_id = :id", 
            ['id' => $studentId]);
        $totalBadges = $badgesQuery->fetch()['total'];
        
        return [
            'total_courses' => $totalCourses,
            'completed_courses' => $completedCourses,
            'total_xp' => $xpData['total_xp'] ?? 0,
            'level' => $xpData['level'] ?? 1,
            'total_badges' => $totalBadges
        ];
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities($studentId, $limit = 10) {
        $db = Database::getInstance();
        
        $sql = "SELECT * FROM activity_logs 
                WHERE user_id = :user_id 
                ORDER BY created_at DESC 
                LIMIT {$limit}";
        
        return $db->query($sql, ['user_id' => $studentId])->fetchAll();
    }

    /**
     * Get notifications
     */
    private function getNotifications($userId, $limit = 10) {
        $db = Database::getInstance();
        
        $sql = "SELECT * FROM notifications 
                WHERE user_id = :user_id 
                ORDER BY created_at DESC 
                LIMIT {$limit}";
        
        return $db->query($sql, ['user_id' => $userId])->fetchAll();
    }

    /**
     * Get progress stats
     */
    private function getProgressStats($studentId) {
        $db = Database::getInstance();
        
        // Total lessons completed
        $lessonsQuery = $db->query("SELECT COUNT(*) as total FROM lesson_progress WHERE student_id = :id AND completed = 1", 
            ['id' => $studentId]);
        $completedLessons = $lessonsQuery->fetch()['total'];
        
        // Total quizzes taken
        $quizzesQuery = $db->query("SELECT COUNT(*) as total FROM quiz_attempts WHERE student_id = :id AND status = 'completed'", 
            ['id' => $studentId]);
        $totalQuizzes = $quizzesQuery->fetch()['total'];
        
        // Average quiz score
        $avgQuery = $db->query("SELECT AVG(score) as avg_score FROM quiz_attempts WHERE student_id = :id AND status = 'completed'", 
            ['id' => $studentId]);
        $avgScore = $avgQuery->fetch()['avg_score'] ?? 0;
        
        return [
            'completed_lessons' => $completedLessons,
            'total_quizzes' => $totalQuizzes,
            'avg_score' => round($avgScore, 2)
        ];
    }
}
