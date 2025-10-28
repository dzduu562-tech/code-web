<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <div class="error-page">
                <div class="error-icon mb-4">
                    <i class="bi bi-exclamation-triangle display-1 text-warning"></i>
                </div>
                
                <h1 class="display-4 fw-bold text-primary mb-3">404</h1>
                <h2 class="h4 mb-4">Không tìm thấy trang</h2>
                
                <p class="text-muted mb-4">
                    Trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.
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
                
                <div class="mt-5">
                    <h5>Có thể bạn đang tìm:</h5>
                    <div class="list-group list-group-flush">
                        <a href="<?= Helpers::url('courses') ?>" class="list-group-item list-group-item-action">
                            <i class="bi bi-book me-2"></i>
                            Danh sách khóa học
                        </a>
                        <a href="<?= Helpers::url('dashboard') ?>" class="list-group-item list-group-item-action">
                            <i class="bi bi-speedometer2 me-2"></i>
                            Bảng điều khiển
                        </a>
                        <a href="<?= Helpers::url('forum') ?>" class="list-group-item list-group-item-action">
                            <i class="bi bi-chat-dots me-2"></i>
                            Diễn đàn
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>