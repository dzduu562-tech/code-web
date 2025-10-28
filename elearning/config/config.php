<?php
// E-Learning Platform Configuration
// Production ready configuration file

return [
    // Database Configuration
    'database' => [
        'host' => 'localhost',
        'dbname' => 'elearning_db',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ],

    // Application Settings
    'app' => [
        'name' => 'E-Learning Platform',
        'url' => 'http://localhost/elearning',
        'timezone' => 'Asia/Ho_Chi_Minh',
        'debug' => true,
        'maintenance' => false,
    ],

    // Security Settings
    'security' => [
        'session_name' => 'elearning_session',
        'csrf_token_name' => '_token',
        'password_min_length' => 6,
        'session_lifetime' => 7200, // 2 hours
    ],

    // Upload Settings
    'upload' => [
        'max_size' => 52428800, // 50MB in bytes
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'zip'],
        'path' => '/public/uploads/',
    ],

    // Notification Settings
    'notifications' => [
        'refresh_interval' => 30, // seconds
        'max_per_page' => 20,
    ],

    // Pagination
    'pagination' => [
        'per_page' => 10,
        'max_per_page' => 100,
    ],

    // Email Settings (for future use)
    'email' => [
        'smtp_host' => '',
        'smtp_port' => 587,
        'smtp_username' => '',
        'smtp_password' => '',
        'from_email' => 'noreply@elearning.com',
        'from_name' => 'E-Learning Platform',
    ]
];