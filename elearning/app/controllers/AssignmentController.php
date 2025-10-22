<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/DB.php';
require_once __DIR__ . '/../core/Helpers.php';

class AssignmentController extends BaseController {
    public function index(): string {
        Auth::startSecureSession();
        if (!Auth::check()) { header('Location: index.php?route=/login'); exit; }
        $pdo = DB::getConnection();
        $user = Auth::user();
        if ($user['role'] === 'teacher') {
            $stmt = $pdo->prepare('SELECT a.*, c.title AS course_title FROM assignments a JOIN courses c ON c.id=a.course_id WHERE c.teacher_id=? ORDER BY due_at DESC');
            $stmt->execute([$user['id']]);
        } else {
            $stmt = $pdo->prepare('SELECT a.*, c.title AS course_title FROM assignments a JOIN enrollments e ON e.course_id=a.course_id JOIN courses c ON c.id=a.course_id WHERE e.user_id=? ORDER BY due_at DESC');
            $stmt->execute([$user['id']]);
        }
        $assignments = $stmt->fetchAll();
        return $this->render('assignments/index', compact('assignments','user'));
    }

    public function submit(): void {
        Auth::startSecureSession();
        if (!Auth::check() || !Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); exit; }
        $assignmentId = (int)($_POST['assignment_id'] ?? 0);
        $note = trim($_POST['note'] ?? '');
        $filePath = null; $fileName = null;
        if (!empty($_FILES['file']['name'])) {
            $config = require __DIR__ . '/../../config/config.php';
            $maxBytes = $config['upload_max_mb'] * 1024 * 1024;
            if ($_FILES['file']['size'] > $maxBytes) { $_SESSION['flash_error'] = 'File quá lớn'; Helpers::redirect('index.php?route=/assignments'); }
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($_FILES['file']['tmp_name']);
            $allowed = ['application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/zip','application/x-zip-compressed','image/png','image/jpeg'];
            if (!in_array($mime, $allowed, true)) { $_SESSION['flash_error'] = 'Định dạng không cho phép'; Helpers::redirect('index.php?route=/assignments'); }
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('sub_') . '.' . $ext;
            $destDir = __DIR__ . '/../../public/uploads/submissions/';
            if (!move_uploaded_file($_FILES['file']['tmp_name'], $destDir . $fileName)) { $_SESSION['flash_error'] = 'Upload lỗi'; Helpers::redirect('index.php?route=/assignments'); }
            $filePath = 'uploads/submissions/' . $fileName;
        }
        $pdo = DB::getConnection();
        $pdo->prepare('INSERT INTO submissions(assignment_id, student_id, file_path, note, score, graded_at) VALUES(?,?,?,?,NULL,NULL) ON DUPLICATE KEY UPDATE file_path=VALUES(file_path), note=VALUES(note), graded_at=NULL, score=NULL')
            ->execute([$assignmentId, Auth::id(), $filePath, $note]);
        $_SESSION['flash_success'] = 'Đã nộp bài';
        Helpers::redirect('index.php?route=/assignments');
    }

    public function grade(): void {
        Auth::startSecureSession();
        $user = Auth::user();
        if (!$user || $user['role'] !== 'teacher' || !Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(403); exit; }
        $submissionId = (int)($_POST['submission_id'] ?? 0);
        $score = (int)($_POST['score'] ?? 0);
        $pdo = DB::getConnection();
        $pdo->prepare('UPDATE submissions SET score=?, graded_at=NOW() WHERE id=?')->execute([$score, $submissionId]);
        $_SESSION['flash_success'] = 'Đã chấm điểm';
        Helpers::redirect('index.php?route=/assignments');
    }
}
