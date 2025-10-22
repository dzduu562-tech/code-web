<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/DB.php';
require_once __DIR__ . '/../core/Helpers.php';

class AdminUsersController extends BaseController {
    public function index(): string {
        Auth::startSecureSession();
        Auth::requireRole(['admin']);
        $pdo = DB::getConnection();
        $users = $pdo->query('SELECT id,name,email,role,created_at FROM users ORDER BY created_at DESC LIMIT 50')->fetchAll();
        return $this->render('admin/users', compact('users'));
    }
}
