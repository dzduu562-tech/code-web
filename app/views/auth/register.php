<?php require_once APP . '/views/layouts/header.php'; ?>

<div class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <!-- Register Card -->
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <!-- Logo & Title -->
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <i class="bi bi-person-plus-fill text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h3 class="fw-bold">Đăng ký tài khoản</h3>
                            <p class="text-muted">Tạo tài khoản học sinh mới</p>
                        </div>

                        <!-- Error Message -->
                        <?php if (isset($errors['register'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="bi bi-exclamation-circle"></i> <?= $errors['register'] ?>
                            </div>
                        <?php endif; ?>

                        <!-- Register Form -->
                        <form method="POST" action="<?= BASE_URL ?>/auth/register">
                            <!-- Full Name -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-person"></i> Họ và tên <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="full_name" 
                                       class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>" 
                                       placeholder="Nguyễn Văn A"
                                       value="<?= $old['full_name'] ?? '' ?>"
                                       required
                                       autofocus>
                                <?php if (isset($errors['full_name'])): ?>
                                    <div class="invalid-feedback"><?= $errors['full_name'] ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-envelope"></i> Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       name="email" 
                                       class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                       placeholder="email@example.com"
                                       value="<?= $old['email'] ?? '' ?>"
                                       required>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?= $errors['email'] ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-lock"></i> Mật khẩu <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           name="password" 
                                           id="password"
                                           class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                           placeholder="Ít nhất 6 ký tự"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                        <i class="bi bi-eye" id="password-icon"></i>
                                    </button>
                                    <?php if (isset($errors['password'])): ?>
                                        <div class="invalid-feedback"><?= $errors['password'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-lock-fill"></i> Xác nhận mật khẩu <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           name="confirm_password" 
                                           id="confirm_password"
                                           class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" 
                                           placeholder="Nhập lại mật khẩu"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password')">
                                        <i class="bi bi-eye" id="confirm_password-icon"></i>
                                    </button>
                                    <?php if (isset($errors['confirm_password'])): ?>
                                        <div class="invalid-feedback"><?= $errors['confirm_password'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Terms Agreement -->
                            <div class="form-check mb-4">
                                <input type="checkbox" class="form-check-input" id="terms" required>
                                <label class="form-check-label small" for="terms">
                                    Tôi đồng ý với <a href="#" class="text-decoration-none">Điều khoản sử dụng</a> 
                                    và <a href="#" class="text-decoration-none">Chính sách bảo mật</a>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="bi bi-person-plus"></i> Đăng ký
                            </button>

                            <!-- Login Link -->
                            <div class="text-center">
                                <span class="text-muted">Đã có tài khoản?</span>
                                <a href="<?= BASE_URL ?>/auth/login" class="text-decoration-none fw-bold">
                                    Đăng nhập ngay
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    const passwordIcon = document.getElementById(inputId + '-icon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordIcon.classList.remove('bi-eye');
        passwordIcon.classList.add('bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        passwordIcon.classList.remove('bi-eye-slash');
        passwordIcon.classList.add('bi-eye');
    }
}
</script>

<?php require_once APP . '/views/layouts/footer.php'; ?>
