<?php
namespace Controllers;

use Core\Auth;
use Core\Helpers;
use PDO;

class CoursesManageController extends BaseController
{
    public function create(): void
    {
        $this->requireRole(['teacher','admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!\Core\Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); echo 'Bad CSRF'; return; }
            $title = trim($_POST['title'] ?? '');
            $subject = trim($_POST['subject'] ?? '');
            $desc = trim($_POST['description'] ?? '');
            if ($title==='') { $this->render('courses/manage_form', ['error'=>'Thiếu tiêu đề']); return; }
            $st = $this->db->prepare('INSERT INTO courses(title,subject,teacher_id,description,created_at) VALUES(?,?,?,?,NOW())');
            $st->execute([$title, $subject, Auth::id(), $desc]);
            Helpers::redirect(Helpers::baseUrl($this->config).'/index.php?route=/courses');
            return;
        }
        $this->render('courses/manage_form');
    }
}
