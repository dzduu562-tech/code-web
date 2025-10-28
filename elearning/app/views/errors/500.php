<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lỗi hệ thống - E-Learning Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="error-page">
                    <div class="error-icon mb-4">
                        <i class="bi bi-exclamation-octagon display-1 text-danger"></i>
                    </div>
                    
                    <h1 class="display-4 fw-bold text-danger mb-3">500</h1>
                    <h2 class="h4 mb-4">Lỗi máy chủ nội bộ</h2>
                    
                    <p class="text-muted mb-4">
                        Đã xảy ra lỗi không mong muốn. Chúng tôi đang khắc phục sự cố này.
                    </p>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Vui lòng thử lại sau vài phút hoặc liên hệ với quản trị viên.
                    </div>
                    
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="/" class="btn btn-primary">
                            <i class="bi bi-house me-2"></i>
                            Về trang chủ
                        </a>
                        
                        <button onclick="window.location.reload()" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-2"></i>
                            Thử lại
                        </button>
                    </div>
                    
                    <div class="mt-5">
                        <small class="text-muted">
                            Mã lỗi: <?= uniqid() ?><br>
                            Thời gian: <?= date('Y-m-d H:i:s') ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>