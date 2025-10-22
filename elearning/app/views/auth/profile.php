<?php
$content = '
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-user me-2"></i>Thông tin cá nhân</h4>
                </div>
                <div class="card-body">
                    ' . ($error ? '<div class="alert alert-danger">' . $error . '</div>' : '') . '
                    ' . ($success ? '<div class="alert alert-success">' . $success . '</div>' : '') . '
                    
                    <form method="POST" action="/profile" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                <div class="avatar-container">
                                    ' . ($user['avatar'] ? 
                                        '<img src="' . htmlspecialchars($user['avatar']) . '" alt="Avatar" class="avatar-img rounded-circle mb-3">' :
                                        '<div class="avatar-placeholder rounded-circle mb-3"><i class="fas fa-user fa-3x"></i></div>'
                                    ) . '
                                    <div>
                                        <label for="avatar" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-camera me-1"></i>Đổi ảnh đại diện
                                        </label>
                                        <input type="file" class="form-control d-none" id="avatar" name="avatar" accept="image/*">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Họ và tên *</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="' . htmlspecialchars($old['name']) . '" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="' . htmlspecialchars($old['email']) . '" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Vai trò</label>
                                    <input type="text" class="form-control" value="' . ucfirst($user['role']) . '" readonly>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Ngày tham gia</label>
                                    <input type="text" class="form-control" value="' . Helpers::formatDate($user['created_at']) . '" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="/dashboard" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Cập nhật thông tin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-container {
    position: relative;
}

.avatar-img {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border: 3px solid #dee2e6;
}

.avatar-placeholder {
    width: 150px;
    height: 150px;
    background-color: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    border: 3px solid #dee2e6;
}
</style>
';

include __DIR__ . '/../layouts/main.php';
?>