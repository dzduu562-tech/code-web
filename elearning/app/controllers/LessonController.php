<?php

class LessonController {
    
    public function show() {
        Auth::requireLogin();
        
        $lessonId = $_GET['id'] ?? 0;
        $lessonModel = new Lesson();
        $courseModel = new Course();
        $quizModel = new Quiz();
        $forumModel = new Forum();
        
        $lesson = $lessonModel->findById($lessonId);
        
        if (!$lesson) {
            http_response_code(404);
            die('Bài học không tồn tại');
        }
        
        $course = $courseModel->findById($lesson['course_id']);
        $resources = $lessonModel->getResources($lessonId);
        $quizzes = $quizModel->getByLesson($lessonId);
        $isCompleted = $lessonModel->isCompleted(Auth::id(), $lessonId);
        
        // Get forum threads for this lesson
        $threads = $forumModel->getThreadsByCourse($lesson['course_id'], $lessonId, 5, 0);
        
        require __DIR__ . '/../views/lessons/show.php';
    }
    
    public function markComplete() {
        Auth::requireRoles(['student']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::json(['success' => false, 'message' => 'Invalid request']);
            return;
        }
        
        $lessonId = $_POST['lesson_id'] ?? 0;
        $lessonModel = new Lesson();
        $courseModel = new Course();
        
        $lesson = $lessonModel->findById($lessonId);
        
        if (!$lesson) {
            Helpers::json(['success' => false, 'message' => 'Bài học không tồn tại']);
            return;
        }
        
        $lessonModel->markAsCompleted(Auth::id(), $lessonId);
        $progress = $courseModel->updateProgress(Auth::id(), $lesson['course_id']);
        
        Helpers::json([
            'success' => true,
            'message' => 'Đã đánh dấu hoàn thành',
            'progress' => round($progress, 2)
        ]);
    }
    
    public function create() {
        Auth::requireRoles(['teacher', 'admin']);
        
        $chapterId = $_GET['chapter_id'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $chapterModel = new Chapter();
            $chapter = $chapterModel->findById($chapterId);
            
            if (!$chapter) {
                http_response_code(404);
                die('Chương không tồn tại');
            }
            
            require __DIR__ . '/../views/lessons/create.php';
            return;
        }
        
        if (!Auth::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token không hợp lệ';
            Helpers::redirect("lessons/create&chapter_id=$chapterId");
            return;
        }
        
        $title = Helpers::sanitizeInput($_POST['title'] ?? '');
        $contentHtml = $_POST['content_html'] ?? '';
        $videoUrl = Helpers::sanitizeInput($_POST['video_url'] ?? '');
        $position = (int)($_POST['position'] ?? 0);
        
        $lessonModel = new Lesson();
        $lessonId = $lessonModel->create([
            'chapter_id' => $chapterId,
            'title' => $title,
            'content_html' => $contentHtml,
            'video_url' => $videoUrl,
            'position' => $position
        ]);
        
        $_SESSION['success'] = 'Tạo bài học thành công!';
        Helpers::redirect("lesson&id=$lessonId");
    }
    
    public function edit() {
        Auth::requireRoles(['teacher', 'admin']);
        
        $lessonId = $_GET['id'] ?? 0;
        $lessonModel = new Lesson();
        $lesson = $lessonModel->findById($lessonId);
        
        if (!$lesson) {
            http_response_code(404);
            die('Bài học không tồn tại');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require __DIR__ . '/../views/lessons/edit.php';
            return;
        }
        
        if (!Auth::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token không hợp lệ';
            Helpers::redirect("lessons/edit&id=$lessonId");
            return;
        }
        
        $title = Helpers::sanitizeInput($_POST['title'] ?? '');
        $contentHtml = $_POST['content_html'] ?? '';
        $videoUrl = Helpers::sanitizeInput($_POST['video_url'] ?? '');
        
        $lessonModel->update($lessonId, [
            'title' => $title,
            'content_html' => $contentHtml,
            'video_url' => $videoUrl
        ]);
        
        $_SESSION['success'] = 'Cập nhật bài học thành công!';
        Helpers::redirect("lesson&id=$lessonId");
    }
}
