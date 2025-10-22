<div class="auth-card">
    <div class="auth-card-header">
        <h3 class="text-center mb-0">Đăng ký tài khoản</h3>
        <p class="text-center text-muted mb-0">Tạo tài khoản để bắt đầu học tập</p>
    </div>
    
    <div class="auth-card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <?= Helpers::escape($error) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= Helpers::escape($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= Helpers::url('register') ?>" novalidate>
            <input type="hidden" name="_token" value="<?= Auth::getInstance()->generateCSRFToken() ?>">
            
            <div class="mb-3">
                <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-person"></i>
                    </span>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="<?= Helpers::escape($old['name'] ?? '') ?>" 
                           placeholder="Nhập họ và tên của bạn" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?= Helpers::escape($old['email'] ?? '') ?>" 
                           placeholder="Nhập địa chỉ email của bạn" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="phone" class="form-label">Số điện thoại</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-telephone"></i>
                    </span>
                    <input type="tel" class="form-control" id="phone" name="phone" 
                           value="<?= Helpers::escape($old['phone'] ?? '') ?>" 
                           placeholder="Nhập số điện thoại (tùy chọn)">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="role" class="form-label">Vai trò <span class="text-danger">*</span></label>
                <select class="form-select" id="role" name="role" required>
                    <option value="">Chọn vai trò của bạn</option>
                    <option value="student" <?= ($old['role'] ?? '') === 'student' ? 'selected' : '' ?>>
                        Học sinh
                    </option>
                    <option value="teacher" <?= ($old['role'] ?? '') === 'teacher' ? 'selected' : '' ?>>
                        Giáo viên
                    </option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Nhập mật khẩu (ít nhất 6 ký tự)" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bi bi-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>
                <div class="form-text">Mật khẩu phải có ít nhất 6 ký tự</div>
            </div>
            
            <div class="mb-3">
                <label for="password_confirm" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" 
                           placeholder="Nhập lại mật khẩu để xác nhận" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                        <i class="bi bi-eye" id="togglePasswordConfirmIcon"></i>
                    </button>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="terms" required>
                    <label class="form-check-label" for="terms">
                        Tôi đồng ý với 
                        <a href="<?= Helpers::url('terms') ?>" target="_blank" class="text-decoration-none">
                            Điều khoản sử dụng
                        </a> và 
                        <a href="<?= Helpers::url('privacy') ?>" target="_blank" class="text-decoration-none">
                            Chính sách bảo mật
                        </a>
                    </label>
                </div>
            </div>
            
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-person-plus me-2"></i>
                    Tạo tài khoản
                </button>
            </div>
        </form>
    </div>
    
    <div class="auth-card-footer">
        <div class="text-center">
            <span class="text-muted">Đã có tài khoản?</span>
            <a href="<?= Helpers::url('login') ?>" class="text-decoration-none fw-semibold">
                Đăng nhập ngay
            </a>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
function setupPasswordToggle(passwordId, toggleId, iconId) {
    document.getElementById(toggleId).addEventListener('click', function() {
        const passwordField = document.getElementById(passwordId);
        const toggleIcon = document.getElementById(iconId);
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    });
}

setupPasswordToggle('password', 'togglePassword', 'togglePasswordIcon');
setupPasswordToggle('password_confirm', 'togglePasswordConfirm', 'togglePasswordConfirmIcon');

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const role = document.getElementById('role').value;
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirm').value;
    const terms = document.getElementById('terms').checked;
    
    let isValid = true;
    
    // Clear previous validation states
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    
    // Validate name
    if (!name || name.length < 2) {
        document.getElementById('name').classList.add('is-invalid');
        isValid = false;
    }
    
    // Validate email
    if (!email) {
        document.getElementById('email').classList.add('is-invalid');
        isValid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        document.getElementById('email').classList.add('is-invalid');
        isValid = false;
    }
    
    // Validate role
    if (!role) {
        document.getElementById('role').classList.add('is-invalid');
        isValid = false;
    }
    
    // Validate password
    if (!password || password.length < 6) {
        document.getElementById('password').classList.add('is-invalid');
        isValid = false;
    }
    
    // Validate password confirmation
    if (password !== passwordConfirm) {
        document.getElementById('password_confirm').classList.add('is-invalid');
        isValid = false;
    }
    
    // Validate terms
    if (!terms) {
        document.getElementById('terms').classList.add('is-invalid');
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
    }
});

// Real-time password confirmation validation
document.getElementById('password_confirm').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const passwordConfirm = this.value;
    
    if (passwordConfirm && password !== passwordConfirm) {
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-invalid');
    }
});
</script>