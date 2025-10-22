<?php
$content = '
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
                        <h2 class="card-title">Đăng ký tài khoản</h2>
                        <p class="text-muted">Tạo tài khoản để bắt đầu học tập</p>
                    </div>
                    
                    ' . ($error ? '<div class="alert alert-danger">' . $error . '</div>' : '') . '
                    
                    <form method="POST" action="/register">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Họ và tên *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="' . htmlspecialchars($old['name']) . '" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="' . htmlspecialchars($old['email']) . '" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Mật khẩu *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="form-text">Mật khẩu phải có ít nhất 6 ký tự</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">Xác nhận mật khẩu *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Vai trò *</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Chọn vai trò</option>
                                <option value="student" ' . ($old['role'] === 'student' ? 'selected' : '') . '>Học sinh</option>
                                <option value="teacher" ' . ($old['role'] === 'teacher' ? 'selected' : '') . '>Giáo viên</option>
                            </select>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Đăng ký
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="mb-0">Đã có tài khoản? 
                            <a href="/login" class="text-decoration-none">Đăng nhập ngay</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
';

include __DIR__ . '/../layouts/main.php';
?>