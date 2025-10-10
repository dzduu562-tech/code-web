<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container py-5">
    <h2 class="fw-bold mb-4">Cài đặt</h2>

    <div class="row">
        <div class="col-md-8">
            <!-- Change Password -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-key"></i> Đổi mật khẩu
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>/settings/changePassword">
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu hiện tại</label>
                            <input type="password" 
                                   name="current_password" 
                                   class="form-control" 
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mật khẩu mới</label>
                            <input type="password" 
                                   name="new_password" 
                                   class="form-control" 
                                   minlength="6"
                                   required>
                            <small class="text-muted">Ít nhất 6 ký tự</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" 
                                   name="confirm_password" 
                                   class="form-control" 
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Đổi mật khẩu
                        </button>
                    </form>
                </div>
            </div>

            <!-- Preferences -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-sliders"></i> Tùy chọn
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="darkMode" onclick="toggleTheme()">
                        <label class="form-check-label" for="darkMode">
                            Chế độ tối (Dark Mode)
                        </label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="emailNotif" checked>
                        <label class="form-check-label" for="emailNotif">
                            Nhận thông báo qua email
                        </label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="pushNotif" checked>
                        <label class="form-check-label" for="pushNotif">
                            Nhận thông báo trên trình duyệt
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Quick Links -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">Liên kết nhanh</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="<?= BASE_URL ?>/profile" class="list-group-item list-group-item-action border-0">
                            <i class="bi bi-person"></i> Hồ sơ cá nhân
                        </a>
                        <a href="<?= BASE_URL ?>/<?= $_SESSION['role'] ?>/dashboard" class="list-group-item list-group-item-action border-0">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <a href="<?= BASE_URL ?>/auth/logout" class="list-group-item list-group-item-action border-0 text-danger">
                            <i class="bi bi-box-arrow-right"></i> Đăng xuất
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update dark mode toggle based on current theme
document.addEventListener('DOMContentLoaded', function() {
    const currentTheme = localStorage.getItem('theme') || 'light';
    document.getElementById('darkMode').checked = (currentTheme === 'dark');
});
</script>

<?php require_once APP . '/views/layouts/footer.php'; ?>
