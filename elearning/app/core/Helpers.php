<?php

class Helpers {
    
    public static function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    public static function redirect($route) {
        header("Location: /elearning/public/index.php?route=$route");
        exit;
    }
    
    public static function redirectTo($url) {
        header("Location: $url");
        exit;
    }
    
    public static function timeAgo($timestamp) {
        $time = strtotime($timestamp);
        $diff = time() - $time;
        
        if ($diff < 60) {
            return $diff . ' giây trước';
        } elseif ($diff < 3600) {
            return floor($diff / 60) . ' phút trước';
        } elseif ($diff < 86400) {
            return floor($diff / 3600) . ' giờ trước';
        } elseif ($diff < 2592000) {
            return floor($diff / 86400) . ' ngày trước';
        } else {
            return date('d/m/Y', $time);
        }
    }
    
    public static function formatDate($timestamp, $format = 'd/m/Y H:i') {
        return date($format, strtotime($timestamp));
    }
    
    public static function uploadFile($file, $destination, $allowedTypes = [], $maxSize = 52428800) {
        // Validate file exists
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Lỗi upload file'];
        }
        
        // Validate file size (default 50MB)
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'File quá lớn (tối đa 50MB)'];
        }
        
        // Validate MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!empty($allowedTypes) && !in_array($mimeType, $allowedTypes)) {
            return ['success' => false, 'message' => 'Định dạng file không được phép'];
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $filepath = $destination . '/' . $filename;
        
        // Create directory if not exists
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return ['success' => true, 'filename' => $filename, 'filepath' => $filepath];
        }
        
        return ['success' => false, 'message' => 'Không thể lưu file'];
    }
    
    public static function paginate($totalItems, $currentPage = 1, $itemsPerPage = 10) {
        $totalPages = ceil($totalItems / $itemsPerPage);
        $currentPage = max(1, min($currentPage, $totalPages));
        $offset = ($currentPage - 1) * $itemsPerPage;
        
        return [
            'total_items' => $totalItems,
            'total_pages' => $totalPages,
            'current_page' => $currentPage,
            'items_per_page' => $itemsPerPage,
            'offset' => $offset,
        ];
    }
    
    public static function sanitizeInput($data) {
        if (is_array($data)) {
            return array_map([self::class, 'sanitizeInput'], $data);
        }
        return trim(strip_tags($data));
    }
    
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    public static function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    public static function truncate($text, $length = 100, $suffix = '...') {
        if (mb_strlen($text) > $length) {
            return mb_substr($text, 0, $length) . $suffix;
        }
        return $text;
    }
}
