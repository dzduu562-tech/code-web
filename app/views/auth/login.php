<?php require_once APP . '/views/layouts/header.php'; ?>

<div class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <!-- Login Card -->
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <!-- Logo & Title -->
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <i class="bi bi-mortarboard-fill text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h3 class="fw-bold"><?= SITE_NAME ?></h3>
                            <p class="text-muted">Đăng nhập vào hệ thống</p>
                        </div>

                        <!-- Error Message -->
                        <?php if (isset($errors['login'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="bi bi-exclamation-circle"></i> <?= $errors['login'] ?>
                            </div>
                        <?php endif; ?>

                        <!-- Login Form -->
                        <form method="POST" action="<?= BASE_URL ?>/auth/login">
                            <!-- Email -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-envelope"></i> Email
                                </label>
                                <input type="email" 
                                       name="email" 
                                       class="form-control form-control-lg <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                       placeholder="email@example.com"
                                       value="<?= $old['email'] ?? '' ?>"
                                       required
                                       autofocus>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?= $errors['email'] ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-lock"></i> Mật khẩu
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           name="password" 
                                           id="password"
                                           class="form-control form-control-lg <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                           placeholder="••••••••"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                        <i class="bi bi-eye" id="password-icon"></i>
                                    </button>
                                    <?php if (isset($errors['password'])): ?>
                                        <div class="invalid-feedback"><?= $errors['password'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                    <label class="form-check-label" for="remember">
                                        Ghi nhớ đăng nhập
                                    </label>
                                </div>
                                <a href="<?= BASE_URL ?>/auth/forgot-password" class="text-decoration-none small">
                                    Quên mật khẩu?
                                </a>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                            </button>

                            <!-- Register Link -->
                            <div class="text-center">
                                <span class="text-muted">Chưa có tài khoản?</span>
                                <a href="<?= BASE_URL ?>/auth/register" class="text-decoration-none fw-bold">
                                    Đăng ký ngay
                                </a>
                            </div>
                        </form>

                        <!-- Demo Accounts Info -->
                        <div class="mt-4 pt-4 border-top">
                            <p class="text-muted small mb-2"><strong>Tài khoản demo:</strong></p>
                            <ul class="list-unstyled small text-muted">
                                <li><i class="bi bi-person-badge"></i> Admin: admin@elearning.com</li>
                                <li><i class="bi bi-person"></i> GV: gv.nguyen@school.edu.vn</li>
                                <li><i class="bi bi-person"></i> HS: hs.an@school.edu.vn</li>
                                <li class="mt-2"><i class="bi bi-key"></i> Mật khẩu: <code>Admin@123</code></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const passwordIcon = document.getElementById('password-icon');
    
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
