<?php
namespace Controllers;

use Core\Helpers;
use Core\Auth;
use PDO;

class AssignmentsController extends BaseController
{
    public function index(): void
    {
        $this->requireLogin();
        $role = Auth::role();
        if ($role === 'teacher') {
            $st = $this->db->prepare('SELECT a.*, c.title AS course_title FROM assignments a JOIN courses c ON c.id=a.course_id WHERE c.teacher_id=? ORDER BY a.due_at DESC');
            $st->execute([Auth::id()]);
        } elseif ($role === 'student') {
            $st = $this->db->prepare('SELECT a.*, c.title AS course_title FROM assignments a JOIN courses c ON c.id=a.course_id JOIN enrollments e ON e.course_id=c.id AND e.user_id=? ORDER BY a.due_at DESC');
            $st->execute([Auth::id()]);
        } else { // admin
            $st = $this->db->query('SELECT a.*, c.title AS course_title FROM assignments a JOIN courses c ON c.id=a.course_id ORDER BY a.due_at DESC');
        }
        $assignments = $st->fetchAll(PDO::FETCH_ASSOC);
        $this->render('assignments/index', compact('assignments', 'role'));
    }

    public function show(): void
    {
        $this->requireRole(['teacher','admin']);
        $id = (int)($_GET['id'] ?? 0);
        $a = $this->db->prepare('SELECT a.*, c.title AS course_title, c.teacher_id FROM assignments a JOIN courses c ON c.id=a.course_id WHERE a.id=?');
        $a->execute([$id]);
        $assignment = $a->fetch(PDO::FETCH_ASSOC);
        if (!$assignment) { http_response_code(404); echo 'Assignment not found'; return; }
        if (Auth::role()==='teacher' && $assignment['teacher_id'] != Auth::id()) { http_response_code(403); echo 'Forbidden'; return; }
        $subs = $this->db->prepare('SELECT s.*, u.name AS student_name FROM submissions s JOIN users u ON u.id=s.student_id WHERE s.assignment_id=? ORDER BY s.created_at DESC');
        $subs->execute([$id]);
        $submissions = $subs->fetchAll(PDO::FETCH_ASSOC);
        $this->render('assignments/show', compact('assignment','submissions'));
    }

    public function my(): void
    {
        $this->requireRole(['student']);
        $st = $this->db->prepare('SELECT s.*, a.title AS assignment_title FROM submissions s JOIN assignments a ON a.id=s.assignment_id WHERE s.student_id=? ORDER BY s.created_at DESC');
        $st->execute([Auth::id()]);
        $submissions = $st->fetchAll(PDO::FETCH_ASSOC);
        $this->render('assignments/my', compact('submissions'));
    }

    public function submit(): void
    {
        $this->requireRole(['student']);
        if (!\Core\Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); echo 'Bad CSRF'; return; }
        $assignmentId = (int)($_POST['assignment_id'] ?? 0);
        $note = trim($_POST['note'] ?? '');
        if ($assignmentId <= 0) { http_response_code(400); echo 'Invalid'; return; }
        // File upload (optional)
        $filePath = null;
        if (!empty($_FILES['file']['name'])) {
            $maxBytes = ($this->config['app']['upload_max_mb'] ?? 50) * 1024 * 1024;
            if ($_FILES['file']['error'] !== UPLOAD_ERR_OK || $_FILES['file']['size'] > $maxBytes) {
                $this->render('assignments/result', ['message' => 'Tệp quá lớn hoặc lỗi tải lên']);
                return;
            }
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($_FILES['file']['tmp_name']);
            $allowed = ['application/pdf','application/zip','image/png','image/jpeg'];
            if (!in_array($mime, $allowed, true)) {
                $this->render('assignments/result', ['message' => 'Định dạng tệp không được phép']);
                return;
            }
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $safeName = 'sub_' . Auth::id() . '_' . time() . '.' . preg_replace('/[^a-zA-Z0-9]+/', '', $ext);
            $dest = dirname(__DIR__, 2) . '/public/uploads/submissions/' . $safeName;
            if (!move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
                $this->render('assignments/result', ['message' => 'Không thể lưu tệp']);
                return;
            }
            $filePath = $safeName;
        }
        $st = $this->db->prepare('INSERT INTO submissions(assignment_id, student_id, file_path, note, created_at) VALUES(?,?,?,?,NOW())');
        $st->execute([$assignmentId, Auth::id(), $filePath, $note]);
        $this->render('assignments/result', ['message' => 'Đã nộp bài thành công']);
    }

    public function grade(): void
    {
        $this->requireRole(['teacher','admin']);
        if (!\Core\Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); echo 'Bad CSRF'; return; }
        $submissionId = (int)($_POST['submission_id'] ?? 0);
        $score = (float)($_POST['score'] ?? 0);
        $st = $this->db->prepare('UPDATE submissions SET score=?, graded_at=NOW() WHERE id=?');
        $st->execute([$score, $submissionId]);
        // notify student
        $uid = $this->db->prepare('SELECT student_id FROM submissions WHERE id=?');
        $uid->execute([$submissionId]);
        $studentId = (int)$uid->fetchColumn();
        if ($studentId) {
            $payload = json_encode(['submission_id'=>$submissionId,'score'=>$score], JSON_UNESCAPED_UNICODE);
            $this->db->prepare('INSERT INTO notifications(user_id,type,payload_json,is_read,created_at) VALUES(?,?,?,?,NOW())')
                     ->execute([$studentId,'graded',$payload,0]);
        }
        $this->render('assignments/result', ['message' => 'Đã chấm điểm']);
    }
}
