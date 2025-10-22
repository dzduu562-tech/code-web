<?php $pageTitle = 'Đăng nhập - E-Learning'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Đăng nhập</h1>
            <p>Chào mừng bạn trở lại!</p>
        </div>
        
        <form action="/elearning/public/index.php?route=login" method="POST" class="auth-form">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
            
            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-control" 
                    required 
                    autofocus
                    placeholder="email@example.com"
                >
            </div>
            
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-control" 
                    required
                    placeholder="••••••••"
                >
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
        </form>
        
        <div class="auth-footer">
            <p>Chưa có tài khoản? <a href="/elearning/public/index.php?route=register">Đăng ký ngay</a></p>
        </div>
        
        <div class="demo-accounts">
            <h4>Tài khoản demo:</h4>
            <ul>
                <li><strong>Admin:</strong> admin@elearning.vn / password123</li>
                <li><strong>Giáo viên:</strong> teacher1@elearning.vn / password123</li>
                <li><strong>Học sinh:</strong> student1@elearning.vn / password123</li>
            </ul>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
