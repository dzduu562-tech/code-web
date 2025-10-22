<?php $pageTitle = 'Đăng ký - E-Learning'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Đăng ký tài khoản</h1>
            <p>Tạo tài khoản để bắt đầu học tập</p>
        </div>
        
        <form action="/elearning/public/index.php?route=register" method="POST" class="auth-form">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
            
            <div class="form-group">
                <label for="name">Họ và tên</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-control" 
                    required 
                    autofocus
                    placeholder="Nguyễn Văn A"
                >
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-control" 
                    required
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
                    minlength="6"
                    placeholder="Tối thiểu 6 ký tự"
                >
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu</label>
                <input 
                    type="password" 
                    id="confirm_password" 
                    name="confirm_password" 
                    class="form-control" 
                    required
                    placeholder="Nhập lại mật khẩu"
                >
            </div>
            
            <div class="form-group">
                <label for="role">Vai trò</label>
                <select id="role" name="role" class="form-control" required>
                    <option value="student">Học sinh</option>
                    <option value="teacher">Giáo viên</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Đăng ký</button>
        </form>
        
        <div class="auth-footer">
            <p>Đã có tài khoản? <a href="/elearning/public/index.php?route=login">Đăng nhập</a></p>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
