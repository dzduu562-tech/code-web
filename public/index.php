<?php
/**
 * E-Learning Platform - Main Entry Point
 * Compatible with XAMPP (PHP 8+ / MySQL)
 */

// Start session
session_start();

// Define constants
define('ROOT', dirname(__DIR__));
define('APP', ROOT . '/app');
define('PUBLIC_PATH', ROOT . '/public');
define('UPLOADS', PUBLIC_PATH . '/uploads');

// Load configuration
require_once ROOT . '/config/config.php';
require_once ROOT . '/config/database.php';

// Autoload classes
spl_autoload_register(function ($class) {
    $paths = [
        APP . '/controllers/' . $class . '.php',
        APP . '/models/' . $class . '.php',
        APP . '/core/' . $class . '.php',
        APP . '/helpers/' . $class . '.php',
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Load helper functions
require_once APP . '/helpers/functions.php';

// Initialize the application
$app = new App();
