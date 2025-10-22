<?php

class AssignmentController {
    
    public function index() {
        Auth::requireLogin();
        
        $assignmentModel = new Assignment();
        
        if (Auth::isStudent()) {
            $assignments = $assignmentModel->getByStudent(Auth::id());
        } else {
            // For teachers/admins, show all assignments from their courses
            $courseModel = new Course();
            $courses = $courseModel->getByTeacher(Auth::id(), 100, 0);
            $assignments = [];
            foreach ($courses as $course) {
                $assignments = array_merge($assignments, $assignmentModel->getByCourse($course['id']));
            }
        }
        
        require __DIR__ . '/../views/assignments/index.php';
    }
    
    public function show() {
        Auth::requireLogin();
        
        $assignmentId = $_GET['id'] ?? 0;
        $assignmentModel = new Assignment();
        
        $assignment = $assignmentModel->findById($assignmentId);
        
        if (!$assignment) {
            http_response_code(404);
            die('Bài tập không tồn tại');
        }
        
        if (Auth::isStudent()) {
            $submission = $assignmentModel->getSubmission($assignmentId, Auth::id());
            require __DIR__ . '/../views/assignments/show.php';
        } else {
            $submissions = $assignmentModel->getSubmissions($assignmentId);
            require __DIR__ . '/../views/assignments/submissions.php';
        }
    }
    
    public function create() {
        Auth::requireRoles(['teacher', 'admin']);
        
        $courseId = $_GET['course_id'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require __DIR__ . '/../views/assignments/create.php';
            return;
        }
        
        if (!Auth::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token không hợp lệ';
            Helpers::redirect("assignments/create&course_id=$courseId");
            return;
        }
        
        $title = Helpers::sanitizeInput($_POST['title'] ?? '');
        $description = Helpers::sanitizeInput($_POST['description'] ?? '');
        $dueAt = $_POST['due_at'] ?? null;
        
        $assignmentModel = new Assignment();
        $assignmentId = $assignmentModel->create([
            'course_id' => $courseId,
            'title' => $title,
            'description' => $description,
            'due_at' => $dueAt
        ]);
        
        $_SESSION['success'] = 'Tạo bài tập thành công!';
        Helpers::redirect("assignment&id=$assignmentId");
    }
    
    public function submit() {
        Auth::requireRoles(['student']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('assignments');
            return;
        }
        
        if (!Auth::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token không hợp lệ';
            Helpers::redirect('assignments');
            return;
        }
        
        $assignmentId = $_POST['assignment_id'] ?? 0;
        $note = Helpers::sanitizeInput($_POST['note'] ?? '');
        $urlSubmission = Helpers::sanitizeInput($_POST['url_submission'] ?? '');
        
        $data = [
            'assignment_id' => $assignmentId,
            'student_id' => Auth::id(),
            'note' => $note,
            'url_submission' => $urlSubmission
        ];
        
        // Handle file upload
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = Helpers::uploadFile(
                $_FILES['file'],
                __DIR__ . '/../../public/uploads/assignments',
                [],
                52428800 // 50MB
            );
            
            if ($uploadResult['success']) {
                $data['file_path'] = 'uploads/assignments/' . $uploadResult['filename'];
            } else {
                $_SESSION['error'] = $uploadResult['message'];
                Helpers::redirect("assignment&id=$assignmentId");
                return;
            }
        }
        
        $assignmentModel = new Assignment();
        $assignmentModel->submitAssignment($data);
        
        $_SESSION['success'] = 'Nộp bài thành công!';
        Helpers::redirect("assignment&id=$assignmentId");
    }
    
    public function grade() {
        Auth::requireRoles(['teacher', 'admin']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('assignments');
            return;
        }
        
        if (!Auth::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token không hợp lệ';
            Helpers::redirect('assignments');
            return;
        }
        
        $submissionId = $_POST['submission_id'] ?? 0;
        $score = (float)($_POST['score'] ?? 0);
        $feedback = Helpers::sanitizeInput($_POST['feedback'] ?? '');
        
        $assignmentModel = new Assignment();
        $assignmentModel->gradeSubmission($submissionId, $score, $feedback);
        
        // Get student ID for notification
        $db = DB::getInstance();
        $submission = $db->fetchOne("SELECT * FROM submissions WHERE id = :id", ['id' => $submissionId]);
        $assignment = $assignmentModel->findById($submission['assignment_id']);
        
        // Send notification
        $notificationModel = new Notification();
        $notificationModel->notifyAssignmentGraded($submission['student_id'], $assignment['title'], $score);
        
        $_SESSION['success'] = 'Chấm điểm thành công!';
        Helpers::redirect("assignment&id=" . $submission['assignment_id']);
    }
}
