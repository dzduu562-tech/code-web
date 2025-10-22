<?php

class ForumController {
    
    public function index() {
        Auth::requireLogin();
        
        $forumModel = new Forum();
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 15;
        $offset = ($page - 1) * $perPage;
        
        $courseId = $_GET['course_id'] ?? null;
        
        if ($courseId) {
            $courseModel = new Course();
            $course = $courseModel->findById($courseId);
            $threads = $forumModel->getThreadsByCourse($courseId, null, $perPage, $offset);
        } else {
            $course = null;
            $threads = $forumModel->getAllThreads($perPage, $offset);
        }
        
        require __DIR__ . '/../views/forum/index.php';
    }
    
    public function thread() {
        Auth::requireLogin();
        
        $threadId = $_GET['id'] ?? 0;
        $forumModel = new Forum();
        
        $thread = $forumModel->getThreadById($threadId);
        
        if (!$thread) {
            http_response_code(404);
            die('Thread không tồn tại');
        }
        
        $posts = $forumModel->getPostsByThread($threadId);
        
        require __DIR__ . '/../views/forum/thread.php';
    }
    
    public function createThread() {
        Auth::requireLogin();
        
        $courseId = $_GET['course_id'] ?? 0;
        $lessonId = $_GET['lesson_id'] ?? null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $courseModel = new Course();
            $course = $courseModel->findById($courseId);
            require __DIR__ . '/../views/forum/create_thread.php';
            return;
        }
        
        if (!Auth::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token không hợp lệ';
            Helpers::redirect("forum/create&course_id=$courseId");
            return;
        }
        
        $title = Helpers::sanitizeInput($_POST['title'] ?? '');
        $content = Helpers::sanitizeInput($_POST['content'] ?? '');
        
        $forumModel = new Forum();
        $threadId = $forumModel->createThread([
            'course_id' => $courseId,
            'lesson_id' => $lessonId ?: null,
            'author_id' => Auth::id(),
            'title' => $title
        ]);
        
        // Add first post
        $forumModel->createPost([
            'thread_id' => $threadId,
            'author_id' => Auth::id(),
            'content' => $content
        ]);
        
        $_SESSION['success'] = 'Tạo topic thành công!';
        Helpers::redirect("forum/thread&id=$threadId");
    }
    
    public function reply() {
        Auth::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('forum');
            return;
        }
        
        if (!Auth::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token không hợp lệ';
            Helpers::redirect('forum');
            return;
        }
        
        $threadId = $_POST['thread_id'] ?? 0;
        $content = Helpers::sanitizeInput($_POST['content'] ?? '');
        
        $forumModel = new Forum();
        $thread = $forumModel->getThreadById($threadId);
        
        if (!$thread) {
            $_SESSION['error'] = 'Thread không tồn tại';
            Helpers::redirect('forum');
            return;
        }
        
        $forumModel->createPost([
            'thread_id' => $threadId,
            'author_id' => Auth::id(),
            'content' => $content
        ]);
        
        // Notify thread author if different user
        if ($thread['author_id'] != Auth::id()) {
            $notificationModel = new Notification();
            $notificationModel->notifyForumReply($thread['author_id'], $threadId);
        }
        
        $_SESSION['success'] = 'Đã trả lời!';
        Helpers::redirect("forum/thread&id=$threadId");
    }
}
