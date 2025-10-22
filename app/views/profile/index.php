<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <!-- Profile Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img src="<?= $user['avatar'] ?: ASSETS_URL . '/images/default-avatar.png' ?>" 
                             alt="Avatar" 
                             class="rounded-circle" 
                             width="150" 
                             height="150"
                             style="object-fit: cover; border: 5px solid #f0f0f0;">
                    </div>
                    <h4 class="fw-bold"><?= e($user['full_name'] ?? 'User') ?></h4>
                    <p class="text-muted"><?= e($user['email'] ?? '') ?></p>
                    <span class="badge bg-primary mb-3">
                        <?php
                        $roleNames = [
                            'admin' => 'Quản trị viên',
                            'teacher' => 'Giáo viên',
                            'student' => 'Học sinh'
                        ];
                        echo $roleNames[$user['role']] ?? $user['role'];
                        ?>
                    </span>

                    <?php if ($user['role'] === 'student' && isset($user['profile']['level'])): ?>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <small>Level <?= $user['profile']['level'] ?></small>
                                <small><?= number_format($user['profile']['total_xp'] ?? 0) ?> XP</small>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" style="width: 65%"></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Profile Info -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">Thông tin cá nhân</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>/profile/edit">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Họ và tên</label>
                                <input type="text" 
                                       name="full_name" 
                                       class="form-control" 
                                       value="<?= e($user['full_name'] ?? '') ?>"
                                       required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" 
                                       class="form-control" 
                                       value="<?= e($user['email'] ?? '') ?>"
                                       disabled>
                                <small class="text-muted">Email không thể thay đổi</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số điện thoại</label>
                                <input type="tel" 
                                       name="phone" 
                                       class="form-control" 
                                       value="<?= e($user['phone'] ?? '') ?>"
                                       placeholder="0123456789">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Vai trò</label>
                                <input type="text" 
                                       class="form-control" 
                                       value="<?= $roleNames[$user['role']] ?? $user['role'] ?>"
                                       disabled>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Địa chỉ</label>
                                <textarea name="address" 
                                          class="form-control" 
                                          rows="3"
                                          placeholder="Nhập địa chỉ của bạn"><?= e($user['address'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                                <i class="bi bi-arrow-left"></i> Quay lại
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
