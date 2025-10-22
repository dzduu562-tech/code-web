<?php
namespace Controllers;

use Core\Helpers;
use PDO;

class HomeController extends BaseController
{
    public function index(): void
    {
        // Basic stats for landing
        $stats = [
            'students' => (int)$this->db->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetchColumn(),
            'teachers' => (int)$this->db->query("SELECT COUNT(*) FROM users WHERE role='teacher'")->fetchColumn(),
            'courses'  => (int)$this->db->query("SELECT COUNT(*) FROM courses")->fetchColumn(),
        ];
        $this->render('home/index', ['stats' => $stats]);
    }
}
