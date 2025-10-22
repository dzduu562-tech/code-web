<?php
/**
 * Base Controller Class
 */

class Controller {
    /**
     * Load view
     */
    public function view($view, $data = []) {
        extract($data);
        
        $viewFile = APP . '/views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View does not exist: " . $view);
        }
    }

    /**
     * Load model
     */
    public function model($model) {
        $modelFile = APP . '/models/' . $model . '.php';
        
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        } else {
            die("Model does not exist: " . $model);
        }
    }

    /**
     * Check if user is logged in
     */
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    /**
     * Check user role
     */
    protected function hasRole($role) {
        return isset($_SESSION['role']) && $_SESSION['role'] === $role;
    }

    /**
     * Require login
     */
    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            redirect('auth/login');
        }
    }

    /**
     * Require specific role
     */
    protected function requireRole($role) {
        $this->requireLogin();
        if (!$this->hasRole($role)) {
            redirect('home');
        }
    }

    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
