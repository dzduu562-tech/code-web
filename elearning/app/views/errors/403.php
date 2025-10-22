<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <div class="error-page">
                <div class="error-icon mb-4">
                    <i class="bi bi-shield-x display-1 text-danger"></i>
                </div>
                
                <h1 class="display-4 fw-bold text-danger mb-3">403</h1>
                <h2 class="h4 mb-4">Truy cập bị từ chối</h2>
                
                <p class="text-muted mb-4">
                    Bạn không có quyền truy cập vào trang này. Vui lòng liên hệ quản trị viên nếu bạn cho rằng đây là lỗi.
                </p>
                
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="<?= Helpers::url('home') ?>" class="btn btn-primary">
                        <i class="bi bi-house me-2"></i>
                        Về trang chủ
                    </a>
                    
                    <button onclick="history.back()" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>
                        Quay lại
                    </button>
                </div>
                
                <?php if (!Auth::getInstance()->isLoggedIn()): ?>
                <div class="mt-4">
                    <p class="text-muted">Bạn chưa đăng nhập?</p>
                    <a href="<?= Helpers::url('login') ?>" class="btn btn-outline-primary">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Đăng nhập ngay
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>