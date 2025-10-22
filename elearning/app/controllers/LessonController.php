<?php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Helpers.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../models/Quiz.php';
require_once __DIR__ . '/../models/Forum.php';

class LessonController {
    private $auth;
    private $courseModel;
    private $lessonModel;
    private $quizModel;
    private $forumModel;
    
    public function __construct() {
        $this->auth = new Auth();
        $this->courseModel = new Course();
        $this->lessonModel = new Lesson();
        $this->quizModel = new Quiz();
        $this->forumModel = new Forum();
    }
    
    public function show() {
        $this->auth->requireAuth();
        
        $id = (int)($_GET['id'] ?? 0);
        $lesson = $this->lessonModel->find($id);
        
        if (!$lesson) {
            http_response_code(404);
            include __DIR__ . '/../views/errors/404.php';
            return;
        }
        
        $user = $this->auth->user();
        
        // Check if user is enrolled in the course
        if (!$this->courseModel->isEnrolled($user['id'], $lesson['course_id'])) {
            Helpers::setFlash('error', 'Bạn cần đăng ký khóa học để xem bài học này');
            Helpers::redirect("/course?id={$lesson['course_id']}");
        }
        
        $course = $this->courseModel->find($lesson['course_id']);
        $resources = $this->lessonModel->getResources($id);
        $quiz = $this->lessonModel->getQuiz($id);
        $isCompleted = $this->lessonModel->isCompleted($user['id'], $id);
        
        // Get forum threads for this lesson
        $threads = $this->forumModel->getThreads($lesson['course_id'], $id, 1, 10);
        
        $data = [
            'title' => $lesson['title'],
            'lesson' => $lesson,
            'course' => $course,
            'resources' => $resources,
            'quiz' => $quiz,
            'is_completed' => $isCompleted,
            'threads' => $threads
        ];
        
        include __DIR__ . '/../views/lessons/show.php';
    }
    
    public function markCompleted() {
        $this->auth->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('/lessons');
        }
        
        if (!$this->auth->verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            Helpers::jsonResponse(['success' => false, 'message' => 'Token không hợp lệ'], 400);
        }
        
        $lessonId = (int)($_POST['lesson_id'] ?? 0);
        $user = $this->auth->user();
        
        $lesson = $this->lessonModel->find($lessonId);
        if (!$lesson) {
            Helpers::jsonResponse(['success' => false, 'message' => 'Bài học không tồn tại'], 404);
        }
        
        // Check if user is enrolled in the course
        if (!$this->courseModel->isEnrolled($user['id'], $lesson['course_id'])) {
            Helpers::jsonResponse(['success' => false, 'message' => 'Bạn cần đăng ký khóa học'], 403);
        }
        
        if ($this->lessonModel->markCompleted($user['id'], $lessonId)) {
            // Update course progress
            $progress = $this->lessonModel->getProgress($user['id'], $lesson['course_id']);
            $this->courseModel->updateProgress($user['id'], $lesson['course_id'], $progress);
            
            Helpers::jsonResponse(['success' => true, 'message' => 'Đánh dấu hoàn thành thành công']);
        } else {
            Helpers::jsonResponse(['success' => false, 'message' => 'Có lỗi xảy ra'], 500);
        }
    }
    
    public function create() {
        $this->auth->requireTeacher();
        
        $courseId = (int)($_GET['course_id'] ?? 0);
        $chapterId = (int)($_GET['chapter_id'] ?? 0);
        
        $course = $this->courseModel->find($courseId);
        $user = $this->auth->user();
        
        if (!$course || $course['teacher_id'] != $user['id']) {
            Helpers::setFlash('error', 'Khóa học không tồn tại hoặc bạn không có quyền tạo bài học');
            Helpers::redirect('/courses');
        }
        
        $chapters = $this->courseModel->getChapters($courseId);
        
        $data = [
            'title' => 'Tạo bài học mới',
            'course' => $course,
            'chapters' => $chapters,
            'selected_chapter_id' => $chapterId,
            'error' => Helpers::getFlash('error'),
            'success' => Helpers::getFlash('success'),
            'old' => [
                'title' => Helpers::old('title'),
                'content_html' => Helpers::old('content_html'),
                'video_url' => Helpers::old('video_url'),
                'position' => Helpers::old('position'),
                'chapter_id' => Helpers::old('chapter_id', $chapterId)
            ]
        ];
        
        include __DIR__ . '/../views/lessons/create.php';
    }
    
    public function store() {
        $this->auth->requireTeacher();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('/lessons');
        }
        
        if (!$this->auth->verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            Helpers::setFlash('error', 'Token không hợp lệ');
            Helpers::redirect('/lessons');
        }
        
        $courseId = (int)($_POST['course_id'] ?? 0);
        $chapterId = (int)($_POST['chapter_id'] ?? 0);
        $title = Helpers::sanitize($_POST['title'] ?? '');
        $contentHtml = $_POST['content_html'] ?? '';
        $videoUrl = Helpers::sanitize($_POST['video_url'] ?? '');
        $position = (int)($_POST['position'] ?? 1);
        $isPublished = isset($_POST['is_published']);
        
        $course = $this->courseModel->find($courseId);
        $user = $this->auth->user();
        
        if (!$course || $course['teacher_id'] != $user['id']) {
            Helpers::setFlash('error', 'Khóa học không tồn tại hoặc bạn không có quyền tạo bài học');
            Helpers::redirect('/courses');
        }
        
        // Validation
        $errors = [];
        
        if (empty($title)) {
            $errors[] = 'Vui lòng nhập tên bài học';
        }
        
        if (empty($contentHtml)) {
            $errors[] = 'Vui lòng nhập nội dung bài học';
        }
        
        if ($chapterId <= 0) {
            $errors[] = 'Vui lòng chọn chương';
        }
        
        if (!empty($errors)) {
            Helpers::setFlash('error', implode('<br>', $errors));
            Helpers::setOld([
                'title' => $title,
                'content_html' => $contentHtml,
                'video_url' => $videoUrl,
                'position' => $position,
                'chapter_id' => $chapterId
            ]);
            Helpers::redirect("/lesson/create?course_id={$courseId}&chapter_id={$chapterId}");
        }
        
        $lessonData = [
            'chapter_id' => $chapterId,
            'title' => $title,
            'content_html' => $contentHtml,
            'video_url' => $videoUrl,
            'position' => $position,
            'is_published' => $isPublished ? 1 : 0
        ];
        
        $lessonId = $this->lessonModel->create($lessonData);
        
        if ($lessonId) {
            // Handle file uploads
            if (isset($_FILES['resources']) && !empty($_FILES['resources']['name'][0])) {
                $this->handleResourceUploads($lessonId, $_FILES['resources']);
            }
            
            Helpers::setFlash('success', 'Tạo bài học thành công');
            Helpers::redirect("/lesson?id={$lessonId}");
        } else {
            Helpers::setFlash('error', 'Có lỗi xảy ra khi tạo bài học');
            Helpers::redirect("/lesson/create?course_id={$courseId}&chapter_id={$chapterId}");
        }
    }
    
    public function edit() {
        $this->auth->requireTeacher();
        
        $id = (int)($_GET['id'] ?? 0);
        $lesson = $this->lessonModel->find($id);
        $user = $this->auth->user();
        
        if (!$lesson || $lesson['teacher_id'] != $user['id']) {
            Helpers::setFlash('error', 'Bài học không tồn tại hoặc bạn không có quyền chỉnh sửa');
            Helpers::redirect('/courses');
        }
        
        $course = $this->courseModel->find($lesson['course_id']);
        $chapters = $this->courseModel->getChapters($lesson['course_id']);
        $resources = $this->lessonModel->getResources($id);
        
        $data = [
            'title' => 'Chỉnh sửa bài học',
            'lesson' => $lesson,
            'course' => $course,
            'chapters' => $chapters,
            'resources' => $resources,
            'error' => Helpers::getFlash('error'),
            'success' => Helpers::getFlash('success'),
            'old' => [
                'title' => Helpers::old('title', $lesson['title']),
                'content_html' => Helpers::old('content_html', $lesson['content_html']),
                'video_url' => Helpers::old('video_url', $lesson['video_url']),
                'position' => Helpers::old('position', $lesson['position']),
                'chapter_id' => Helpers::old('chapter_id', $lesson['chapter_id'])
            ]
        ];
        
        include __DIR__ . '/../views/lessons/edit.php';
    }
    
    public function update() {
        $this->auth->requireTeacher();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('/lessons');
        }
        
        if (!$this->auth->verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            Helpers::setFlash('error', 'Token không hợp lệ');
            Helpers::redirect('/lessons');
        }
        
        $id = (int)($_POST['id'] ?? 0);
        $lesson = $this->lessonModel->find($id);
        $user = $this->auth->user();
        
        if (!$lesson || $lesson['teacher_id'] != $user['id']) {
            Helpers::setFlash('error', 'Bài học không tồn tại hoặc bạn không có quyền chỉnh sửa');
            Helpers::redirect('/courses');
        }
        
        $chapterId = (int)($_POST['chapter_id'] ?? 0);
        $title = Helpers::sanitize($_POST['title'] ?? '');
        $contentHtml = $_POST['content_html'] ?? '';
        $videoUrl = Helpers::sanitize($_POST['video_url'] ?? '');
        $position = (int)($_POST['position'] ?? 1);
        $isPublished = isset($_POST['is_published']);
        
        // Validation
        $errors = [];
        
        if (empty($title)) {
            $errors[] = 'Vui lòng nhập tên bài học';
        }
        
        if (empty($contentHtml)) {
            $errors[] = 'Vui lòng nhập nội dung bài học';
        }
        
        if ($chapterId <= 0) {
            $errors[] = 'Vui lòng chọn chương';
        }
        
        if (!empty($errors)) {
            Helpers::setFlash('error', implode('<br>', $errors));
            Helpers::redirect("/lesson/edit?id={$id}");
        }
        
        $lessonData = [
            'chapter_id' => $chapterId,
            'title' => $title,
            'content_html' => $contentHtml,
            'video_url' => $videoUrl,
            'position' => $position,
            'is_published' => $isPublished ? 1 : 0
        ];
        
        if ($this->lessonModel->update($id, $lessonData)) {
            // Handle file uploads
            if (isset($_FILES['resources']) && !empty($_FILES['resources']['name'][0])) {
                $this->handleResourceUploads($id, $_FILES['resources']);
            }
            
            Helpers::setFlash('success', 'Cập nhật bài học thành công');
        } else {
            Helpers::setFlash('error', 'Có lỗi xảy ra khi cập nhật bài học');
        }
        
        Helpers::redirect("/lesson?id={$id}");
    }
    
    public function delete() {
        $this->auth->requireTeacher();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('/lessons');
        }
        
        if (!$this->auth->verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            Helpers::setFlash('error', 'Token không hợp lệ');
            Helpers::redirect('/lessons');
        }
        
        $id = (int)($_POST['id'] ?? 0);
        $lesson = $this->lessonModel->find($id);
        $user = $this->auth->user();
        
        if (!$lesson || $lesson['teacher_id'] != $user['id']) {
            Helpers::setFlash('error', 'Bài học không tồn tại hoặc bạn không có quyền xóa');
            Helpers::redirect('/courses');
        }
        
        // Delete resources
        $resources = $this->lessonModel->getResources($id);
        foreach ($resources as $resource) {
            $this->lessonModel->removeResource($resource['id']);
        }
        
        if ($this->lessonModel->delete($id)) {
            Helpers::setFlash('success', 'Xóa bài học thành công');
        } else {
            Helpers::setFlash('error', 'Có lỗi xảy ra khi xóa bài học');
        }
        
        Helpers::redirect("/course?id={$lesson['course_id']}");
    }
    
    private function handleResourceUploads($lessonId, $files) {
        $fileCount = count($files['name']);
        
        for ($i = 0; $i < $fileCount; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $file = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i]
                ];
                
                $uploadResult = Helpers::uploadFile($file);
                if ($uploadResult['success']) {
                    $this->lessonModel->addResource(
                        $lessonId,
                        $uploadResult['file_path'],
                        $uploadResult['file_name'],
                        $uploadResult['file_size'],
                        $uploadResult['mime_type']
                    );
                }
            }
        }
    }
}