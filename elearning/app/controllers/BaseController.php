<?php
namespace Controllers;

use Core\DB;
use Core\Helpers;
use Core\Auth;
use PDO;

abstract class BaseController
{
    protected array $config;
    protected PDO $db;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->db = DB::conn($config);
    }

    protected function render(string $view, array $params = []): void
    {
        $baseUrl = Helpers::baseUrl($this->config);
        $authUser = Auth::user();
        extract($params);
        ob_start();
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            echo '<p>View not found: ' . Helpers::e($view) . '</p>';
        } else {
            include $viewFile;
        }
        $content = ob_get_clean();
        include __DIR__ . '/../views/layouts/main.php';
    }

    protected function requireLogin(): void
    {
        if (!Auth::check()) {
            Helpers::redirect(Helpers::baseUrl($this->config) . '/index.php?route=/login');
        }
    }

    protected function requireRole(array $roles): void
    {
        \Core\Auth::requireRole($roles);
    }
}
