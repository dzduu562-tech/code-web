# Hướng dẫn cài đặt E-Learning Platform

## Yêu cầu hệ thống

- **PHP 8.0+** (khuyến nghị 8.1 hoặc 8.2)
- **MySQL 5.7+** hoặc **MariaDB 10.3+**
- **Apache** với mod_rewrite hoặc **Nginx**
- **50MB+** dung lượng ổ cứng
- **128MB+** PHP memory limit

## Cài đặt với XAMPP (Windows)

### 1. Tải và cài đặt XAMPP
```
1. Truy cập https://www.apachefriends.org/download.html
2. Tải phiên bản XAMPP với PHP 8.0+
3. Cài đặt vào C:\xampp\
4. Khởi động Apache và MySQL trong XAMPP Control Panel
```

### 2. Copy source code
```bash
# Copy thư mục elearning vào htdocs
copy elearning C:\xampp\htdocs\
```

### 3. Tạo database
```
1. Mở trình duyệt và truy cập http://localhost/phpmyadmin
2. Tạo database mới tên 'elearning_db'
3. Chọn database vừa tạo
4. Import file sql/schema.sql (tạo bảng)
5. Import file sql/seed.sql (dữ liệu mẫu)
```

### 4. Cấu hình
```bash
# Copy file config mẫu
copy config\config.php.sample config\config.php

# Chỉnh sửa config.php nếu cần (thường không cần với XAMPP mặc định)
```

### 5. Phân quyền thư mục
```bash
# Đảm bảo thư mục uploads có quyền ghi
# Thường XAMPP đã cấu hình sẵn
```

### 6. Truy cập hệ thống
```
URL: http://localhost/elearning/public/
```

## Cài đặt trên Linux/Ubuntu

### 1. Cài đặt LAMP Stack
```bash
sudo apt update
sudo apt install apache2 mysql-server php8.1 php8.1-mysql php8.1-mbstring php8.1-xml php8.1-curl
```

### 2. Cấu hình Apache
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### 3. Tạo database
```bash
sudo mysql -u root -p
CREATE DATABASE elearning_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'elearning'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON elearning_db.* TO 'elearning'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 4. Copy source code
```bash
sudo cp -r elearning /var/www/html/
sudo chown -R www-data:www-data /var/www/html/elearning
sudo chmod -R 755 /var/www/html/elearning
sudo chmod -R 777 /var/www/html/elearning/public/uploads
```

### 5. Import database
```bash
mysql -u elearning -p elearning_db < /var/www/html/elearning/sql/schema.sql
mysql -u elearning -p elearning_db < /var/www/html/elearning/sql/seed.sql
```

### 6. Cấu hình
```bash
cp /var/www/html/elearning/config/config.php.sample /var/www/html/elearning/config/config.php
# Chỉnh sửa database credentials trong config.php
```

## Cài đặt trên macOS

### 1. Cài đặt MAMP hoặc Homebrew
```bash
# Với Homebrew
brew install php@8.1 mysql apache2

# Hoặc tải MAMP từ https://www.mamp.info/
```

### 2. Các bước tương tự như Linux
```bash
# Thay đổi đường dẫn phù hợp với macOS
# /Applications/MAMP/htdocs/ cho MAMP
# /usr/local/var/www/ cho Homebrew
```

## Cấu hình nâng cao

### Virtual Host (tùy chọn)
```apache
<VirtualHost *:80>
    ServerName elearning.local
    DocumentRoot /path/to/elearning/public
    
    <Directory /path/to/elearning/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/elearning_error.log
    CustomLog ${APACHE_LOG_DIR}/elearning_access.log combined
</VirtualHost>
```

### Nginx Configuration
```nginx
server {
    listen 80;
    server_name elearning.local;
    root /path/to/elearning/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\. {
        deny all;
    }
}
```

### SSL/HTTPS (Production)
```apache
<VirtualHost *:443>
    ServerName yourdomain.com
    DocumentRoot /path/to/elearning/public
    
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    
    # Redirect HTTP to HTTPS
    <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteCond %{HTTPS} off
        RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    </IfModule>
</VirtualHost>
```

## Tài khoản mẫu

| Vai trò | Email | Mật khẩu |
|---------|-------|----------|
| Admin | admin@elearning.com | 123456 |
| Giáo viên | teacher1@elearning.com | 123456 |
| Học sinh | student1@elearning.com | 123456 |

## Xử lý sự cố

### Lỗi database connection
```
1. Kiểm tra MySQL đã chạy chưa
2. Kiểm tra thông tin đăng nhập trong config.php
3. Đảm bảo database đã được tạo
4. Kiểm tra firewall không chặn port 3306
```

### Lỗi 404 Not Found
```
1. Kiểm tra mod_rewrite đã enable chưa
2. Kiểm tra file .htaccess có quyền đọc
3. Kiểm tra DocumentRoot trong Apache config
4. Thử truy cập trực tiếp: /elearning/public/index.php
```

### Lỗi upload file
```
1. Kiểm tra quyền ghi thư mục uploads/
2. Kiểm tra upload_max_filesize trong php.ini
3. Kiểm tra post_max_size trong php.ini
4. Restart Apache sau khi thay đổi php.ini
```

### Lỗi session
```
1. Kiểm tra session.save_path trong php.ini
2. Đảm bảo thư mục session có quyền ghi
3. Kiểm tra session.cookie_secure nếu dùng HTTPS
```

## Bảo mật Production

### 1. Thay đổi mật khẩu mặc định
```
- Đổi mật khẩu tất cả tài khoản mẫu
- Tạo tài khoản admin mới và xóa tài khoản mặc định
```

### 2. Cấu hình PHP
```ini
# php.ini
display_errors = Off
log_errors = On
expose_php = Off
session.cookie_httponly = 1
session.cookie_secure = 1 # Nếu dùng HTTPS
```

### 3. Database Security
```sql
-- Tạo user riêng cho ứng dụng
CREATE USER 'elearning_app'@'localhost' IDENTIFIED BY 'strong_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON elearning_db.* TO 'elearning_app'@'localhost';

-- Xóa user root remote access
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');
```

### 4. File Permissions
```bash
# Chỉ cho phép web server đọc
chmod 644 config/config.php
chmod 755 public/uploads/

# Ẩn các file nhạy cảm
chattr +i config/config.php  # Linux
```

## Backup và Restore

### Backup Database
```bash
mysqldump -u username -p elearning_db > backup_$(date +%Y%m%d).sql
```

### Backup Files
```bash
tar -czf elearning_backup_$(date +%Y%m%d).tar.gz elearning/
```

### Restore
```bash
mysql -u username -p elearning_db < backup_20231201.sql
tar -xzf elearning_backup_20231201.tar.gz
```

## Monitoring

### Log Files
```bash
# Apache logs
tail -f /var/log/apache2/elearning_error.log

# PHP logs  
tail -f /var/log/php_errors.log

# MySQL logs
tail -f /var/log/mysql/error.log
```

### Performance Monitoring
```bash
# Check MySQL performance
SHOW PROCESSLIST;
SHOW STATUS LIKE 'Slow_queries';

# Check disk space
df -h

# Check memory usage
free -m
```

## Cập nhật

### Cập nhật code
```bash
# Backup trước khi cập nhật
cp -r elearning elearning_backup

# Cập nhật code mới (giữ nguyên config.php và uploads/)
# Import database changes nếu có
```

### Cập nhật database
```sql
-- Chạy migration scripts nếu có
SOURCE updates/migration_v1.1.sql;
```

Để được hỗ trợ thêm, vui lòng liên hệ: support@elearning.com