<?php
/**
 * Home Controller
 */

class Home extends Controller {
    public function index() {
        // Redirect to appropriate dashboard if logged in
        if ($this->isLoggedIn()) {
            $role = $_SESSION['role'] ?? 'student';
            redirect($role . '/dashboard');
        }

        $courseModel = $this->model('Course');
        
        $data = [
            'title' => 'Trang chủ',
            'featured_courses' => $courseModel->getFeaturedCourses(6),
            'stats' => $this->getStats()
        ];

        $this->view('home/index', $data);
    }

    private function getStats() {
        $db = Database::getInstance();
        
        // Count students
        $studentsQuery = $db->query("SELECT COUNT(*) as total FROM users WHERE role = 'student' AND status = 'active'");
        $students = $studentsQuery->fetch()['total'];
        
        // Count teachers
        $teachersQuery = $db->query("SELECT COUNT(*) as total FROM users WHERE role = 'teacher' AND status = 'active'");
        $teachers = $teachersQuery->fetch()['total'];
        
        // Count courses
        $coursesQuery = $db->query("SELECT COUNT(*) as total FROM courses WHERE is_published = 1");
        $courses = $coursesQuery->fetch()['total'];
        
        // Count lessons
        $lessonsQuery = $db->query("SELECT COUNT(*) as total FROM lessons WHERE is_published = 1");
        $lessons = $lessonsQuery->fetch()['total'];
        
        return [
            'students' => $students,
            'teachers' => $teachers,
            'courses' => $courses,
            'lessons' => $lessons
        ];
    }
}
