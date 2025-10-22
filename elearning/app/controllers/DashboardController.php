<?php

class DashboardController {
    
    public function index() {
        Auth::requireLogin();
        
        $user = Auth::user();
        $role = $user['role'];
        
        if ($role === 'admin') {
            $this->adminDashboard();
        } elseif ($role === 'teacher') {
            $this->teacherDashboard();
        } else {
            $this->studentDashboard();
        }
    }
    
    private function adminDashboard() {
        $userModel = new User();
        $courseModel = new Course();
        
        $stats = [
            'total_users' => $userModel->count(),
            'total_students' => $userModel->countByRole('student'),
            'total_teachers' => $userModel->countByRole('teacher'),
            'total_courses' => $courseModel->count(),
        ];
        
        $recentCourses = $courseModel->getAll(5, 0);
        
        require __DIR__ . '/../views/dashboard/admin.php';
    }
    
    private function teacherDashboard() {
        $courseModel = new Course();
        $userId = Auth::id();
        
        $courses = $courseModel->getByTeacher($userId, 10, 0);
        
        require __DIR__ . '/../views/dashboard/teacher.php';
    }
    
    private function studentDashboard() {
        $courseModel = new Course();
        $notificationModel = new Notification();
        $userId = Auth::id();
        
        $enrolledCourses = $courseModel->getEnrolledCourses($userId);
        $notifications = $notificationModel->getByUser($userId, 5);
        
        require __DIR__ . '/../views/dashboard/student.php';
    }
}
