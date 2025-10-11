<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/admin/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <h2 class="fw-bold mb-4">Cài đặt hệ thống</h2>

            <form method="POST" action="<?= BASE_URL ?>/admin/settings">
                <div class="row">
                    <!-- General Settings -->
                    <div class="col-lg-6 mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0 py-3">
                                <h5 class="mb-0 fw-bold">Cài đặt chung</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Tên website</label>
                                    <input type="text" 
                                           name="site_name" 
                                           class="form-control" 
                                           value="<?= $settings['site_name'] ?? 'E-Learning Platform' ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mô tả</label>
                                    <textarea name="site_description" 
                                              class="form-control" 
                                              rows="3"><?= $settings['site_description'] ?? '' ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Năm học</label>
                                    <input type="text" 
                                           name="academic_year" 
                                           class="form-control" 
                                           value="<?= $settings['academic_year'] ?? '2024-2025' ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Múi giờ</label>
                                    <select name="timezone" class="form-select">
                                        <option value="Asia/Ho_Chi_Minh" selected>Asia/Ho_Chi_Minh</option>
                                        <option value="Asia/Bangkok">Asia/Bangkok</option>
                                        <option value="UTC">UTC</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Settings -->
                    <div class="col-lg-6 mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0 py-3">
                                <h5 class="mb-0 fw-bold">Cài đặt hệ thống</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Số items mỗi trang</label>
                                    <input type="number" 
                                           name="items_per_page" 
                                           class="form-control" 
                                           value="<?= $settings['items_per_page'] ?? 10 ?>"
                                           min="5" 
                                           max="100">
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="allow_registration" 
                                           id="allowReg"
                                           <?= ($settings['allow_registration'] ?? '1') == '1' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="allowReg">
                                        Cho phép đăng ký tài khoản
                                    </label>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="require_email_verification" 
                                           id="emailVerif"
                                           <?= ($settings['require_email_verification'] ?? '0') == '1' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="emailVerif">
                                        Yêu cầu xác thực email
                                    </label>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Ngôn ngữ mặc định</label>
                                    <select name="language" class="form-select">
                                        <option value="vi" selected>Tiếng Việt</option>
                                        <option value="en">English</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-save"></i> Lưu cài đặt
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
