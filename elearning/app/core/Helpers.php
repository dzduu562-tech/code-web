<?php

class Helpers {
    
    public static function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    public static function clean($input) {
        if (is_array($input)) {
            return array_map([self::class, 'clean'], $input);
        }
        return trim(strip_tags($input));
    }

    public static function sanitizeHtml($html) {
        // Basic HTML sanitization - remove dangerous tags
        $allowed_tags = '<p><br><strong><b><em><i><u><ul><ol><li><h1><h2><h3><h4><h5><h6><blockquote><code><pre>';
        return strip_tags($html, $allowed_tags);
    }

    public static function formatDate($date, $format = 'd/m/Y H:i') {
        if (empty($date)) return '';
        
        try {
            $dateObj = new DateTime($date);
            return $dateObj->format($format);
        } catch (Exception $e) {
            return $date;
        }
    }

    public static function timeAgo($datetime) {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) return 'vừa xong';
        if ($time < 3600) return floor($time/60) . ' phút trước';
        if ($time < 86400) return floor($time/3600) . ' giờ trước';
        if ($time < 2592000) return floor($time/86400) . ' ngày trước';
        if ($time < 31104000) return floor($time/2592000) . ' tháng trước';
        
        return floor($time/31104000) . ' năm trước';
    }

    public static function formatFileSize($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    public static function generateSlug($string) {
        $string = self::removeAccents($string);
        $string = preg_replace('/[^a-zA-Z0-9\s]/', '', $string);
        $string = preg_replace('/\s+/', '-', trim($string));
        return strtolower($string);
    }

    public static function removeAccents($string) {
        $accents = [
            'à','á','ạ','ả','ã','â','ầ','ấ','ậ','ẩ','ẫ','ă','ằ','ắ','ặ','ẳ','ẵ',
            'è','é','ẹ','ẻ','ẽ','ê','ề','ế','ệ','ể','ễ',
            'ì','í','ị','ỉ','ĩ',
            'ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ','ơ','ờ','ớ','ợ','ở','ỡ',
            'ù','ú','ụ','ủ','ũ','ư','ừ','ứ','ự','ử','ữ',
            'ỳ','ý','ỵ','ỷ','ỹ',
            'đ',
            'À','Á','Ạ','Ả','Ã','Â','Ầ','Ấ','Ậ','Ẩ','Ẫ','Ă','Ằ','Ắ','Ặ','Ẳ','Ẵ',
            'È','É','Ẹ','Ẻ','Ẽ','Ê','Ề','Ế','Ệ','Ể','Ễ',
            'Ì','Í','Ị','Ỉ','Ĩ',
            'Ò','Ó','Ọ','Ỏ','Õ','Ô','Ồ','Ố','Ộ','Ổ','Ỗ','Ơ','Ờ','Ớ','Ợ','Ở','Ỡ',
            'Ù','Ú','Ụ','Ủ','Ũ','Ư','Ừ','Ứ','Ự','Ử','Ữ',
            'Ỳ','Ý','Ỵ','Ỷ','Ỹ',
            'Đ'
        ];
        
        $noAccents = [
            'a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a',
            'e','e','e','e','e','e','e','e','e','e','e',
            'i','i','i','i','i',
            'o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o',
            'u','u','u','u','u','u','u','u','u','u','u',
            'y','y','y','y','y',
            'd',
            'A','A','A','A','A','A','A','A','A','A','A','A','A','A','A','A','A',
            'E','E','E','E','E','E','E','E','E','E','E',
            'I','I','I','I','I',
            'O','O','O','O','O','O','O','O','O','O','O','O','O','O','O','O','O',
            'U','U','U','U','U','U','U','U','U','U','U',
            'Y','Y','Y','Y','Y',
            'D'
        ];
        
        return str_replace($accents, $noAccents, $string);
    }

    public static function truncate($string, $length = 100, $suffix = '...') {
        if (strlen($string) <= $length) {
            return $string;
        }
        
        return substr($string, 0, $length) . $suffix;
    }

    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function validateUrl($url) {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    public static function generateRandomString($length = 10) {
        return bin2hex(random_bytes($length / 2));
    }

    public static function uploadFile($file, $allowedTypes = [], $maxSize = null) {
        $config = require __DIR__ . '/../../config/config.php';
        
        if (!$allowedTypes) {
            $allowedTypes = $config['upload']['allowed_types'];
        }
        
        if (!$maxSize) {
            $maxSize = $config['upload']['max_size'];
        }

        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Lỗi upload file'];
        }

        // Check file size
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'File quá lớn. Tối đa ' . self::formatFileSize($maxSize)];
        }

        // Check file type
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedTypes)) {
            return ['success' => false, 'message' => 'Loại file không được phép'];
        }

        // Generate unique filename
        $filename = uniqid() . '_' . time() . '.' . $fileExtension;
        $uploadPath = __DIR__ . '/../../public/uploads/';
        
        // Create upload directory if it doesn't exist
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $fullPath = $uploadPath . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $fullPath)) {
            return [
                'success' => true,
                'filename' => $filename,
                'path' => '/uploads/' . $filename,
                'size' => $file['size'],
                'original_name' => $file['name']
            ];
        }

        return ['success' => false, 'message' => 'Không thể lưu file'];
    }

    public static function deleteFile($filename) {
        $filePath = __DIR__ . '/../../public/uploads/' . basename($filename);
        
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        
        return false;
    }

    public static function paginate($totalItems, $currentPage = 1, $perPage = 10) {
        $totalPages = ceil($totalItems / $perPage);
        $currentPage = max(1, min($currentPage, $totalPages));
        $offset = ($currentPage - 1) * $perPage;

        return [
            'total_items' => $totalItems,
            'total_pages' => $totalPages,
            'current_page' => $currentPage,
            'per_page' => $perPage,
            'offset' => $offset,
            'has_prev' => $currentPage > 1,
            'has_next' => $currentPage < $totalPages,
            'prev_page' => $currentPage > 1 ? $currentPage - 1 : null,
            'next_page' => $currentPage < $totalPages ? $currentPage + 1 : null
        ];
    }

    public static function calculateProgress($completed, $total) {
        if ($total == 0) return 0;
        return round(($completed / $total) * 100, 2);
    }

    public static function sendNotification($userId, $type, $title, $message, $payload = []) {
        try {
            $db = DB::getInstance();
            return $db->insert('notifications', [
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'payload_json' => json_encode($payload),
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            error_log("Failed to send notification: " . $e->getMessage());
            return false;
        }
    }

    public static function getUnreadNotificationCount($userId) {
        $db = DB::getInstance();
        return $db->count('notifications', 'user_id = ? AND is_read = 0', [$userId]);
    }

    public static function markNotificationAsRead($notificationId, $userId) {
        $db = DB::getInstance();
        return $db->update('notifications', 
            ['is_read' => 1], 
            'id = ? AND user_id = ?', 
            [$notificationId, $userId]
        );
    }

    public static function redirect($url) {
        header("Location: $url");
        exit;
    }

    public static function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function currentUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    public static function baseUrl() {
        $config = require __DIR__ . '/../../config/config.php';
        return $config['app']['url'];
    }

    public static function asset($path) {
        return self::baseUrl() . '/public/assets/' . ltrim($path, '/');
    }

    public static function url($route = '', $params = []) {
        $router = new Router();
        return $router->url($route, $params);
    }
}