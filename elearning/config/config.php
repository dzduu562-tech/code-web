<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'elearning_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application Configuration
define('APP_NAME', 'E-Learning Platform');
define('APP_URL', 'http://localhost/elearning');
define('APP_PATH', __DIR__ . '/..');

// Security
define('SECRET_KEY', 'elearning-secret-key-2024');
define('CSRF_TOKEN_NAME', 'csrf_token');

// Upload Configuration
define('UPLOAD_PATH', APP_PATH . '/public/uploads');
define('MAX_FILE_SIZE', 50 * 1024 * 1024); // 50MB
define('ALLOWED_EXTENSIONS', ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'txt', 'jpg', 'jpeg', 'png', 'gif']);

// Pagination
define('ITEMS_PER_PAGE', 10);

// Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');