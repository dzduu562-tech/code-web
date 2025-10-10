<?php
/**
 * FILE TEST - Để kiểm tra lỗi
 * Đặt file này vào: htdocs/elearning/test.php
 * Truy cập: http://localhost/elearning/test.php
 */

echo "<h1>🔍 KIỂM TRA HỆ THỐNG</h1>";
echo "<style>body{font-family:Arial;padding:20px;} .ok{color:green;} .error{color:red;} .warning{color:orange;}</style>";

// 1. PHP Version
echo "<h2>1. PHP Version</h2>";
if (version_compare(PHP_VERSION, '8.0.0', '>=')) {
    echo "<p class='ok'>✅ PHP Version: " . PHP_VERSION . " (OK)</p>";
} else {
    echo "<p class='error'>❌ PHP Version: " . PHP_VERSION . " (Cần PHP 8.0+)</p>";
}

// 2. Extensions
echo "<h2>2. PHP Extensions</h2>";
$extensions = ['pdo', 'pdo_mysql', 'mysqli', 'mbstring', 'json'];
foreach ($extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p class='ok'>✅ $ext: Đã cài đặt</p>";
    } else {
        echo "<p class='error'>❌ $ext: Chưa cài đặt</p>";
    }
}

// 3. File Structure
echo "<h2>3. Kiểm tra File</h2>";
$files = [
    'public/index.php' => 'Entry point',
    'public/.htaccess' => 'Rewrite rules',
    'config/config.php' => 'Config file',
    'config/database.php' => 'Database config',
    'app/core/App.php' => 'Router',
    'app/controllers/Auth.php' => 'Auth Controller',
    'app/views/auth/login.php' => 'Login View',
    'app/views/auth/register.php' => 'Register View',
];

foreach ($files as $file => $desc) {
    if (file_exists($file)) {
        echo "<p class='ok'>✅ $file ($desc)</p>";
    } else {
        echo "<p class='error'>❌ $file - THIẾU FILE!</p>";
    }
}

// 4. Database Connection
echo "<h2>4. Kết nối Database</h2>";
if (file_exists('config/database.php')) {
    require_once 'config/database.php';
    
    try {
        $dsn = "mysql:host=" . DB_HOST . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        echo "<p class='ok'>✅ Kết nối MySQL thành công!</p>";
        
        // Check database exists
        $stmt = $pdo->query("SHOW DATABASES LIKE '" . DB_NAME . "'");
        if ($stmt->rowCount() > 0) {
            echo "<p class='ok'>✅ Database '" . DB_NAME . "' tồn tại</p>";
            
            // Check tables
            $pdo->exec("USE " . DB_NAME);
            $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            if (count($tables) > 0) {
                echo "<p class='ok'>✅ Có " . count($tables) . " bảng trong database</p>";
                echo "<small>Tables: " . implode(', ', array_slice($tables, 0, 5)) . "...</small>";
            } else {
                echo "<p class='error'>❌ Database RỖNG - Chưa import schema.sql!</p>";
            }
        } else {
            echo "<p class='error'>❌ Database '" . DB_NAME . "' CHƯA TỒN TẠI!</p>";
            echo "<p class='warning'>⚠️ Cần tạo database và import schema.sql</p>";
        }
    } catch (PDOException $e) {
        echo "<p class='error'>❌ Lỗi kết nối: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p class='error'>❌ Không tìm thấy file config/database.php</p>";
}

// 5. Config
echo "<h2>5. Cấu hình</h2>";
if (file_exists('config/config.php')) {
    require_once 'config/config.php';
    echo "<p class='ok'>✅ BASE_URL: " . (defined('BASE_URL') ? BASE_URL : 'CHƯA ĐỊNH NGHĨA') . "</p>";
    
    // Test BASE_URL
    $currentUrl = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
    $expectedUrl = rtrim(BASE_URL, '/');
    
    if (strpos($currentUrl, 'test.php') !== false) {
        $currentUrl = str_replace('/test.php', '/public', $currentUrl);
    }
    
    echo "<p class='warning'>⚠️ URL hiện tại: $currentUrl</p>";
    echo "<p class='warning'>⚠️ BASE_URL config: $expectedUrl</p>";
}

// 6. mod_rewrite
echo "<h2>6. Apache mod_rewrite</h2>";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    if (in_array('mod_rewrite', $modules)) {
        echo "<p class='ok'>✅ mod_rewrite: Đã bật</p>";
    } else {
        echo "<p class='error'>❌ mod_rewrite: CHƯA BẬT!</p>";
        echo "<p class='warning'>⚠️ Cần bật trong httpd.conf</p>";
    }
} else {
    echo "<p class='warning'>⚠️ Không thể kiểm tra (không chạy Apache hoặc hàm bị tắt)</p>";
}

// 7. Permissions
echo "<h2>7. Quyền File</h2>";
$writableDirs = ['public/uploads'];
foreach ($writableDirs as $dir) {
    if (is_dir($dir)) {
        if (is_writable($dir)) {
            echo "<p class='ok'>✅ $dir: Có quyền ghi</p>";
        } else {
            echo "<p class='error'>❌ $dir: KHÔNG có quyền ghi!</p>";
        }
    } else {
        echo "<p class='warning'>⚠️ $dir: Thư mục chưa tồn tại</p>";
    }
}

// 8. Test Routing
echo "<h2>8. Test URL Routing</h2>";
echo "<p>Thử truy cập các URL sau:</p>";
echo "<ul>";
echo "<li><a href='public/' target='_blank'>Trang chủ: public/</a></li>";
echo "<li><a href='public/auth/login' target='_blank'>Login: public/auth/login</a></li>";
echo "<li><a href='public/auth/register' target='_blank'>Register: public/auth/register</a></li>";
echo "</ul>";

// 9. Error Log
echo "<h2>9. Kiểm tra Error Log</h2>";
$errorLog = 'C:/xampp/apache/logs/error.log'; // Windows
if (!file_exists($errorLog)) {
    $errorLog = '/opt/lampp/logs/error_log'; // Linux
}
if (file_exists($errorLog)) {
    echo "<p class='ok'>✅ Error log: $errorLog</p>";
    echo "<p><small>Xem file này nếu có lỗi</small></p>";
} else {
    echo "<p class='warning'>⚠️ Không tìm thấy error log</p>";
}

// 10. Recommendations
echo "<h2>10. Khuyến nghị</h2>";
echo "<div style='background:#fff3cd;padding:15px;border-left:4px solid #ffc107;'>";
echo "<h3>Nếu không vào được login/register:</h3>";
echo "<ol>";
echo "<li><strong>Database RỖNG</strong> → Import <code>database/schema.sql</code> qua phpMyAdmin</li>";
echo "<li><strong>mod_rewrite CHƯA BẬT</strong> → Bật trong httpd.conf và restart Apache</li>";
echo "<li><strong>BASE_URL SAI</strong> → Sửa trong <code>config/config.php</code></li>";
echo "<li><strong>Xem error log</strong> → Kiểm tra file error.log của Apache</li>";
echo "<li><strong>Bật hiển thị lỗi</strong> → Sửa <code>config/config.php</code>:<br>";
echo "<code>error_reporting(E_ALL);<br>ini_set('display_errors', 1);</code></li>";
echo "</ol>";
echo "</div>";

echo "<hr>";
echo "<p><strong>📧 Gửi kết quả test này cho developer nếu cần hỗ trợ!</strong></p>";
?>
