<div class="auth-card">
    <div class="auth-card-header">
        <h3 class="text-center mb-0">Đăng nhập</h3>
        <p class="text-center text-muted mb-0">Chào mừng bạn trở lại!</p>
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

        <form method="POST" action="<?= Helpers::url('login') ?>" novalidate>
            <input type="hidden" name="_token" value="<?= Auth::getInstance()->generateCSRFToken() ?>">
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
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
                <label for="password" class="form-label">Mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Nhập mật khẩu của bạn" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bi bi-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        Ghi nhớ đăng nhập
                    </label>
                </div>
            </div>
            
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Đăng nhập
                </button>
            </div>
        </form>
        
        <div class="text-center">
            <a href="<?= Helpers::url('forgot-password') ?>" class="text-decoration-none">
                Quên mật khẩu?
            </a>
        </div>
    </div>
    
    <div class="auth-card-footer">
        <div class="text-center">
            <span class="text-muted">Chưa có tài khoản?</span>
            <a href="<?= Helpers::url('register') ?>" class="text-decoration-none fw-semibold">
                Đăng ký ngay
            </a>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePasswordIcon');
    
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

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    
    let isValid = true;
    
    // Clear previous validation states
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    
    // Validate email
    if (!email) {
        document.getElementById('email').classList.add('is-invalid');
        isValid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        document.getElementById('email').classList.add('is-invalid');
        isValid = false;
    }
    
    // Validate password
    if (!password) {
        document.getElementById('password').classList.add('is-invalid');
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
    }
});
</script>