<?php
namespace Controllers;

use Core\Auth;
use Core\Helpers;
use PDO;

class AdminController extends BaseController
{
    public function index(): void
    {
        $this->requireRole(['admin']);
        $stats = [
            'users' => (int)$this->db->query('SELECT COUNT(*) FROM users')->fetchColumn(),
            'courses' => (int)$this->db->query('SELECT COUNT(*) FROM courses')->fetchColumn(),
            'enrollments' => (int)$this->db->query('SELECT COUNT(*) FROM enrollments')->fetchColumn(),
        ];
        $recent = $this->db->query('SELECT name,email,role,created_at FROM users ORDER BY created_at DESC LIMIT 10')->fetchAll(PDO::FETCH_ASSOC);
        $this->render('admin/index', compact('stats','recent'));
    }

    public function users(): void
    {
        $this->requireRole(['admin']);
        $q = trim($_GET['q'] ?? '');
        $sql = 'SELECT id,name,email,role,created_at FROM users WHERE 1=1';
        $params = [];
        if ($q !== '') { $sql .= ' AND (name LIKE ? OR email LIKE ?)'; $params = ["%$q%","%$q%"]; }
        $sql .= ' ORDER BY created_at DESC LIMIT 50';
        $st = $this->db->prepare($sql);
        $st->execute($params);
        $users = $st->fetchAll(PDO::FETCH_ASSOC);
        $this->render('admin/users', compact('users','q'));
    }

    public function updateUser(): void
    {
        $this->requireRole(['admin']);
        if (!\Core\Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); echo 'Bad CSRF'; return; }
        $id = (int)($_POST['id'] ?? 0);
        $role = in_array($_POST['role'] ?? '', ['admin','teacher','student'], true) ? $_POST['role'] : 'student';
        $st = $this->db->prepare('UPDATE users SET role=? WHERE id=?');
        $st->execute([$role, $id]);
        Helpers::redirect(Helpers::baseUrl($this->config).'/index.php?route=/admin/users');
    }
}
