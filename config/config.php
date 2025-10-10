<?php
/**
 * Configuration File - E-Learning Platform
 * Cấu hình cho XAMPP (PHP 8 + MySQL)
 */

// Site Configuration
define('SITE_NAME', 'E-Learning Platform');
define('SITE_TITLE', 'Hệ Thống Học Tập Trực Tuyến');
define('SITE_VERSION', '1.0.0');

// Base URL - Cấu hình cho XAMPP
define('BASE_URL', 'http://localhost/elearning/public');
define('ASSETS_URL', BASE_URL . '/assets');

// Application Settings
define('DEFAULT_CONTROLLER', 'Home');
define('DEFAULT_METHOD', 'index');
define('DEFAULT_LANGUAGE', 'vi');

// Upload Settings
define('MAX_UPLOAD_SIZE', 50 * 1024 * 1024); // 50MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('ALLOWED_DOC_TYPES', ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx']);
define('ALLOWED_VIDEO_TYPES', ['mp4', 'webm', 'ogg']);

// Pagination
define('ITEMS_PER_PAGE', 10);

// Security
define('HASH_ALGO', PASSWORD_DEFAULT);
define('SESSION_LIFETIME', 7200); // 2 hours

// Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Error Reporting (development mode)
error_reporting(E_ALL);
ini_set('display_errors', 1);
