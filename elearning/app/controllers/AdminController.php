<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/DB.php';
require_once __DIR__ . '/../core/Helpers.php';

class AdminController extends BaseController {
    public function dashboard(): string {
        Auth::startSecureSession();
        Auth::requireRole(['admin']);
        $pdo = DB::getConnection();
        $stats = [
            'users' => (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(),
            'courses' => (int)$pdo->query('SELECT COUNT(*) FROM courses')->fetchColumn(),
            'threads' => (int)$pdo->query('SELECT COUNT(*) FROM forum_threads')->fetchColumn(),
        ];
        $recent = $pdo->query('SELECT email, role, created_at FROM users ORDER BY created_at DESC LIMIT 5')->fetchAll();
        return $this->render('admin/dashboard', compact('stats','recent'));
    }
}
