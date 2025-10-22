<?php

class HomeController {
    
    public function index() {
        // Get statistics for landing page
        $userModel = new User();
        $courseModel = new Course();
        
        $stats = [
            'total_students' => $userModel->countByRole('student'),
            'total_teachers' => $userModel->countByRole('teacher'),
            'total_courses' => $courseModel->count(),
        ];
        
        // Get featured courses
        $courses = $courseModel->getAll(6, 0);
        
        require __DIR__ . '/../views/home/index.php';
    }
}
