<?php
namespace Controllers;

class DashboardController extends BaseController
{
    public function index(): void
    {
        $this->requireLogin();
        $role = \Core\Auth::role();
        // Load some items depending on role
        if ($role === 'teacher') {
            $courses = $this->db->prepare('SELECT * FROM courses WHERE teacher_id = ? ORDER BY created_at DESC LIMIT 10');
            $courses->execute([\Core\Auth::id()]);
            $list = $courses->fetchAll();
        } elseif ($role === 'admin') {
            $stmt = $this->db->query('SELECT * FROM users ORDER BY created_at DESC LIMIT 10');
            $list = $stmt->fetchAll();
        } else {
            $stmt = $this->db->prepare('SELECT c.* FROM enrollments e JOIN courses c ON c.id=e.course_id WHERE e.user_id = ? ORDER BY c.created_at DESC');
            $stmt->execute([\Core\Auth::id()]);
            $list = $stmt->fetchAll();
        }
        $this->render('dashboard/index', ['items' => $list, 'role' => $role]);
    }
}
