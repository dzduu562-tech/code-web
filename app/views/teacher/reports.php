<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/teacher/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <h2 class="fw-bold mb-4">Báo cáo & Thống kê</h2>

            <!-- Filter -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Chọn khóa học</label>
                            <select class="form-select">
                                <option value="">Tất cả khóa học</option>
                                <option>Toán học lớp 10</option>
                                <option>Tiếng Anh cơ bản</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Từ ngày</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Đến ngày</label>
                            <input type="date" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold">Tiến độ học tập của lớp</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center py-5">
                                <i class="bi bi-bar-chart text-muted" style="font-size: 5rem;"></i>
                                <p class="text-muted mt-3">Biểu đồ sẽ hiển thị ở đây</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold">Top 5 học sinh</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center py-4 text-muted">
                                Chưa có dữ liệu
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
