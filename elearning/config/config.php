<?php
// E-Learning Platform - Configuration
// This is the default configuration for development

return [
    // Database Configuration
    'db_host' => 'localhost',
    'db_name' => 'elearning_db',
    'db_user' => 'root',
    'db_pass' => '',
    
    // Application Settings
    'app_name' => 'E-Learning Platform',
    'app_url' => 'http://localhost/elearning/public',
    
    // Upload Settings
    'upload_max_size' => 52428800, // 50MB in bytes
    'upload_path' => __DIR__ . '/../public/uploads',
    
    // Session Settings
    'session_lifetime' => 1800, // 30 minutes
];
