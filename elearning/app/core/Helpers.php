<?php

class Helpers {
    
    public static function redirect($url) {
        header("Location: $url");
        exit;
    }
    
    public static function redirectBack() {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        self::redirect($referer);
    }
    
    public static function sanitize($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public static function validatePassword($password) {
        return strlen($password) >= 6;
    }
    
    public static function generateSlug($text) {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        return trim($text, '-');
    }
    
    public static function formatDate($date, $format = 'd/m/Y H:i') {
        return date($format, strtotime($date));
    }
    
    public static function timeAgo($datetime) {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) return 'vừa xong';
        if ($time < 3600) return floor($time/60) . ' phút trước';
        if ($time < 86400) return floor($time/3600) . ' giờ trước';
        if ($time < 2592000) return floor($time/86400) . ' ngày trước';
        if ($time < 31536000) return floor($time/2592000) . ' tháng trước';
        
        return floor($time/31536000) . ' năm trước';
    }
    
    public static function truncate($text, $length = 100, $suffix = '...') {
        if (strlen($text) <= $length) {
            return $text;
        }
        
        return substr($text, 0, $length) . $suffix;
    }
    
    public static function uploadFile($file, $uploadDir = null) {
        if (!$uploadDir) {
            $uploadDir = UPLOAD_PATH;
        }
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileName = $file['name'];
        $fileSize = $file['size'];
        $fileTmp = $file['tmp_name'];
        $fileType = $file['type'];
        
        // Validate file size
        if ($fileSize > MAX_FILE_SIZE) {
            return ['success' => false, 'message' => 'File quá lớn'];
        }
        
        // Validate file extension
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (!in_array($fileExt, ALLOWED_EXTENSIONS)) {
            return ['success' => false, 'message' => 'Định dạng file không được hỗ trợ'];
        }
        
        // Generate unique filename
        $newFileName = uniqid() . '_' . time() . '.' . $fileExt;
        $filePath = $uploadDir . '/' . $newFileName;
        
        if (move_uploaded_file($fileTmp, $filePath)) {
            return [
                'success' => true,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'mime_type' => $fileType
            ];
        }
        
        return ['success' => false, 'message' => 'Không thể upload file'];
    }
    
    public static function deleteFile($filePath) {
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return true;
    }
    
    public static function paginate($totalItems, $currentPage = 1, $itemsPerPage = ITEMS_PER_PAGE) {
        $totalPages = ceil($totalItems / $itemsPerPage);
        $currentPage = max(1, min($currentPage, $totalPages));
        $offset = ($currentPage - 1) * $itemsPerPage;
        
        return [
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'items_per_page' => $itemsPerPage,
            'total_items' => $totalItems,
            'offset' => $offset,
            'has_prev' => $currentPage > 1,
            'has_next' => $currentPage < $totalPages,
            'prev_page' => $currentPage > 1 ? $currentPage - 1 : null,
            'next_page' => $currentPage < $totalPages ? $currentPage + 1 : null
        ];
    }
    
    public static function generatePaginationLinks($pagination, $baseUrl) {
        $links = [];
        
        // Previous page
        if ($pagination['has_prev']) {
            $links[] = [
                'url' => $baseUrl . '?page=' . $pagination['prev_page'],
                'text' => 'Trước',
                'class' => 'page-link'
            ];
        }
        
        // Page numbers
        $start = max(1, $pagination['current_page'] - 2);
        $end = min($pagination['total_pages'], $pagination['current_page'] + 2);
        
        for ($i = $start; $i <= $end; $i++) {
            $links[] = [
                'url' => $baseUrl . '?page=' . $i,
                'text' => $i,
                'class' => $i == $pagination['current_page'] ? 'page-link active' : 'page-link'
            ];
        }
        
        // Next page
        if ($pagination['has_next']) {
            $links[] = [
                'url' => $baseUrl . '?page=' . $pagination['next_page'],
                'text' => 'Sau',
                'class' => 'page-link'
            ];
        }
        
        return $links;
    }
    
    public static function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    public static function flash($key, $message = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($message === null) {
            $message = $_SESSION['flash'][$key] ?? null;
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        
        $_SESSION['flash'][$key] = $message;
    }
    
    public static function setFlash($key, $message) {
        self::flash($key, $message);
    }
    
    public static function getFlash($key) {
        return self::flash($key);
    }
    
    public static function hasFlash($key) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['flash'][$key]);
    }
    
    public static function old($key, $default = '') {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return $_SESSION['old'][$key] ?? $default;
    }
    
    public static function setOld($data) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['old'] = $data;
    }
    
    public static function clearOld() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        unset($_SESSION['old']);
    }
    
    public static function getCurrentUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
    
    public static function getBaseUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        return $protocol . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
    }
}