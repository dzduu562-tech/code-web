<?php
$content = '
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0"><i class="fas fa-key me-2"></i>Đổi mật khẩu</h4>
                </div>
                <div class="card-body">
                    ' . ($error ? '<div class="alert alert-danger">' . $error . '</div>' : '') . '
                    ' . ($success ? '<div class="alert alert-success">' . $success . '</div>' : '') . '
                    
                    <form method="POST" action="/change-password">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mật khẩu hiện tại *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Mật khẩu mới *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                            </div>
                            <div class="form-text">Mật khẩu phải có ít nhất 6 ký tự</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="/profile" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Đổi mật khẩu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
';

include __DIR__ . '/../layouts/main.php';
?>