<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/DB.php';
require_once __DIR__ . '/../core/Helpers.php';

class TeacherController extends BaseController {
    private function requireTeacher(): void {
        Auth::startSecureSession();
        Auth::requireRole(['teacher']);
    }

    public function courses(): string {
        $this->requireTeacher();
        $pdo = DB::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM courses WHERE teacher_id=? ORDER BY created_at DESC');
        $stmt->execute([Auth::id()]);
        $courses = $stmt->fetchAll();
        return $this->render('courses/teacher_list', compact('courses'));
    }

    public function createForm(): string {
        $this->requireTeacher();
        $course = ['id'=>0,'title'=>'','subject'=>'','description'=>''];
        return $this->render('courses/form', compact('course'));
    }

    public function saveCourse(): void {
        $this->requireTeacher();
        if (!Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); exit; }
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        if ($title === '' || $subject === '') { $_SESSION['flash_error']='Thiếu dữ liệu'; Helpers::redirect('index.php?route=/teacher/courses'); }
        $pdo = DB::getConnection();
        if ($id > 0) {
            $stmt = $pdo->prepare('UPDATE courses SET title=?, subject=?, description=? WHERE id=? AND teacher_id=?');
            $stmt->execute([$title,$subject,$desc,$id,Auth::id()]);
            $_SESSION['flash_success'] = 'Đã cập nhật khóa học';
            Helpers::redirect('index.php?route=/teacher/course/manage&id='.$id);
        } else {
            $stmt = $pdo->prepare('INSERT INTO courses(title,subject,teacher_id,description,created_at) VALUES(?,?,?,?,NOW())');
            $stmt->execute([$title,$subject,Auth::id(),$desc]);
            $_SESSION['flash_success'] = 'Đã tạo khóa học';
            Helpers::redirect('index.php?route=/teacher/courses');
        }
    }

    public function manage(): string {
        $this->requireTeacher();
        $id = (int)($_GET['id'] ?? 0);
        $pdo = DB::getConnection();
        $courseStmt = $pdo->prepare('SELECT * FROM courses WHERE id=? AND teacher_id=?');
        $courseStmt->execute([$id, Auth::id()]);
        $course = $courseStmt->fetch();
        if (!$course) { http_response_code(404); return 'Course not found'; }
        $chapters = $pdo->prepare('SELECT * FROM chapters WHERE course_id=? ORDER BY position');
        $chapters->execute([$id]);
        $chapters = $chapters->fetchAll();
        $lessonsByChapter = [];
        foreach ($chapters as $ch) {
            $ls = $pdo->prepare('SELECT * FROM lessons WHERE chapter_id=? ORDER BY position');
            $ls->execute([$ch['id']]);
            $lessonsByChapter[$ch['id']] = $ls->fetchAll();
        }
        return $this->render('courses/manage', compact('course','chapters','lessonsByChapter'));
    }

    public function saveChapter(): void {
        $this->requireTeacher();
        if (!Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); exit; }
        $courseId = (int)($_POST['course_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $position = (int)($_POST['position'] ?? 1);
        $pdo = DB::getConnection();
        $own = $pdo->prepare('SELECT 1 FROM courses WHERE id=? AND teacher_id=?'); $own->execute([$courseId, Auth::id()]);
        if (!$own->fetch()) { http_response_code(403); exit; }
        $pdo->prepare('INSERT INTO chapters(course_id,title,position) VALUES(?,?,?)')->execute([$courseId,$title,$position]);
        $_SESSION['flash_success'] = 'Đã thêm chương';
        Helpers::redirect('index.php?route=/teacher/course/manage&id='.$courseId);
    }

    public function saveLesson(): void {
        $this->requireTeacher();
        if (!Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); exit; }
        $chapterId = (int)($_POST['chapter_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $content = $_POST['content_html'] ?? '';
        $video = trim($_POST['video_url'] ?? '');
        $position = (int)($_POST['position'] ?? 1);
        $pdo = DB::getConnection();
        $courseId = (int)$pdo->query('SELECT course_id FROM chapters WHERE id='.$chapterId)->fetchColumn();
        $own = $pdo->prepare('SELECT 1 FROM courses WHERE id=? AND teacher_id=?'); $own->execute([$courseId, Auth::id()]);
        if (!$own->fetch()) { http_response_code(403); exit; }
        $pdo->prepare('INSERT INTO lessons(chapter_id,title,content_html,video_url,position) VALUES(?,?,?,?,?)')->execute([$chapterId,$title,$content,$video,$position]);
        $_SESSION['flash_success'] = 'Đã thêm bài học';
        // notify enrolled students
        $students = $pdo->prepare('SELECT user_id FROM enrollments WHERE course_id=?'); $students->execute([$courseId]);
        foreach ($students->fetchAll() as $s) {
            $pdo->prepare('INSERT INTO notifications(user_id,type,payload_json,is_read,created_at) VALUES(?,?,?,?,NOW())')
                ->execute([$s['user_id'],'new_lesson', json_encode(['course_id'=>$courseId,'title'=>$title]), 0]);
        }
        Helpers::redirect('index.php?route=/teacher/course/manage&id='.$courseId);
    }

    public function deleteLesson(): void {
        $this->requireTeacher();
        if (!Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); exit; }
        $lessonId = (int)($_POST['lesson_id'] ?? 0);
        $pdo = DB::getConnection();
        $row = $pdo->query('SELECT ch.course_id, l.chapter_id FROM lessons l JOIN chapters ch ON ch.id=l.chapter_id WHERE l.id=' . $lessonId)->fetch();
        $own = $pdo->prepare('SELECT 1 FROM courses WHERE id=? AND teacher_id=?'); $own->execute([$row['course_id'] ?? 0, Auth::id()]);
        if (!$own->fetch()) { http_response_code(403); exit; }
        $pdo->prepare('DELETE FROM lessons WHERE id=?')->execute([$lessonId]);
        $_SESSION['flash_success'] = 'Đã xóa bài học';
        Helpers::redirect('index.php?route=/teacher/course/manage&id='.$row['course_id']);
    }

    public function uploadResource(): void {
        $this->requireTeacher();
        if (!Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); exit; }
        $lessonId = (int)($_POST['lesson_id'] ?? 0);
        if (empty($_FILES['file']['name'])) { $_SESSION['flash_error']='Chưa chọn file'; Helpers::redirect('index.php?route=/teacher/course/manage&id='.(int)($_POST['course_id'] ?? 0)); }
        $config = require __DIR__ . '/../../config/config.php';
        $maxBytes = $config['upload_max_mb'] * 1024 * 1024;
        if ($_FILES['file']['size'] > $maxBytes) { $_SESSION['flash_error'] = 'File quá lớn'; Helpers::redirect('index.php?route=/teacher/course/manage&id='.(int)($_POST['course_id'] ?? 0)); }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($_FILES['file']['tmp_name']);
        $allowed = ['application/pdf','image/png','image/jpeg','application/zip','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!in_array($mime, $allowed, true)) { $_SESSION['flash_error']='Loại file không hỗ trợ'; Helpers::redirect('index.php?route=/teacher/course/manage&id='.(int)($_POST['course_id'] ?? 0)); }
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('res_') . '.' . $ext;
        $destDir = __DIR__ . '/../../public/uploads/resources/';
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $destDir . $fileName)) { $_SESSION['flash_error']='Upload lỗi'; Helpers::redirect('index.php?route=/teacher/course/manage&id='.(int)($_POST['course_id'] ?? 0)); }
        $pdo = DB::getConnection();
        $pdo->prepare('INSERT INTO resources(lesson_id,file_path,file_name) VALUES(?,?,?)')->execute([$lessonId, 'uploads/resources/'.$fileName, $_FILES['file']['name']]);
        $_SESSION['flash_success']='Đã thêm tài liệu';
        Helpers::redirect('index.php?route=/teacher/course/manage&id='.(int)($_POST['course_id'] ?? 0));
    }
}
