<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/student/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Lịch học</h2>
                <div>
                    <button class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Thêm sự kiện
                    </button>
                </div>
            </div>

            <!-- Calendar View Switcher -->
            <div class="btn-group mb-4" role="group">
                <button type="button" class="btn btn-outline-primary active">
                    <i class="bi bi-calendar-month"></i> Tháng
                </button>
                <button type="button" class="btn btn-outline-primary">
                    <i class="bi bi-calendar-week"></i> Tuần
                </button>
                <button type="button" class="btn btn-outline-primary">
                    <i class="bi bi-calendar-day"></i> Ngày
                </button>
            </div>

            <!-- Upcoming Events -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-calendar3"></i> Tháng <?= date('m/Y') ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center py-5">
                                <i class="bi bi-calendar-x text-muted" style="font-size: 5rem;"></i>
                                <h5 class="mt-3 text-muted">Chưa có sự kiện nào</h5>
                                <p class="text-muted">Lịch học sẽ được cập nhật sớm</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-primary text-white py-3">
                            <h6 class="mb-0">
                                <i class="bi bi-bell"></i> Sự kiện sắp tới
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small text-center">Không có sự kiện nào</p>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-warning text-white py-3">
                            <h6 class="mb-0">
                                <i class="bi bi-clock"></i> Deadline gần nhất
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small text-center">Không có deadline nào</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
