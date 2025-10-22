<?php
$title = 'Trang không tìm thấy';
$content = '
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="error-page">
                <h1 class="display-1 text-primary">404</h1>
                <h2 class="mb-4">Trang không tìm thấy</h2>
                <p class="lead mb-4">Xin lỗi, trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.</p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="/" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>Về trang chủ
                    </a>
                    <a href="javascript:history.back()" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
';

include __DIR__ . '/../layouts/main.php';
?>