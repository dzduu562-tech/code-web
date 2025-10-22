<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../core/DB.php';

class SearchController extends BaseController {
    public function stats(): void {
        $pdo = DB::getConnection();
        $students = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetchColumn();
        $teachers = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='teacher'")->fetchColumn();
        $courses = (int)$pdo->query('SELECT COUNT(*) FROM courses')->fetchColumn();
        header('Content-Type: application/json');
        echo json_encode(['students'=>$students,'teachers'=>$teachers,'courses'=>$courses]);
        exit;
    }
}
