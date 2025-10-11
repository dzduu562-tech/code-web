<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/admin/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <h2 class="fw-bold mb-4">Sao lưu & Khôi phục</h2>

            <div class="row">
                <!-- Backup Database -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-cloud-download text-primary"></i> Sao lưu Database
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Tạo bản sao lưu toàn bộ dữ liệu hệ thống</p>
                            
                            <div class="d-grid gap-2">
                                <a href="<?= BASE_URL ?>/admin/backupDatabase" 
                                   class="btn btn-primary btn-lg"
                                   onclick="return confirm('Bạn muốn sao lưu database?')">
                                    <i class="bi bi-database-down"></i> Sao lưu ngay
                                </a>
                            </div>

                            <hr>

                            <div class="small">
                                <p class="mb-2"><strong>Lần sao lưu cuối:</strong></p>
                                <p class="text-muted mb-2">
                                    <i class="bi bi-calendar"></i> <?= date('d/m/Y H:i:s') ?>
                                </p>
                                <p class="mb-0"><strong>Kích thước:</strong> ~2.5 MB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Export Excel -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-file-earmark-excel text-success"></i> Export Excel
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Xuất dữ liệu ra file Excel</p>
                            
                            <div class="d-grid gap-2 mb-3">
                                <a href="<?= BASE_URL ?>/admin/exportUsers" 
                                   class="btn btn-outline-success">
                                    <i class="bi bi-people"></i> Export Người dùng
                                </a>
                                <a href="<?= BASE_URL ?>/admin/exportCourses" 
                                   class="btn btn-outline-success">
                                    <i class="bi bi-book"></i> Export Khóa học
                                </a>
                                <a href="<?= BASE_URL ?>/admin/exportStudents" 
                                   class="btn btn-outline-success">
                                    <i class="bi bi-person-badge"></i> Export Học sinh
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Backups -->
                <div class="col-lg-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold">Lịch sử sao lưu</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Thời gian</th>
                                            <th>Loại</th>
                                            <th>Kích thước</th>
                                            <th>Người tạo</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                Chưa có bản sao lưu nào
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
