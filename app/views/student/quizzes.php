<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/student/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Kiểm tra & Quiz</h2>
                <div>
                    <button class="btn btn-outline-primary">
                        <i class="bi bi-funnel"></i> Lọc
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-clipboard-check text-primary" style="font-size: 3rem;"></i>
                            <h3 class="mt-3 fw-bold">0</h3>
                            <p class="text-muted mb-0">Quiz đã làm</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-hourglass-split text-warning" style="font-size: 3rem;"></i>
                            <h3 class="mt-3 fw-bold">0</h3>
                            <p class="text-muted mb-0">Đang chờ làm</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-graph-up text-success" style="font-size: 3rem;"></i>
                            <h3 class="mt-3 fw-bold">0%</h3>
                            <p class="text-muted mb-0">Điểm TB</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quiz List -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">Danh sách Quiz</h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="bi bi-clipboard2-x text-muted" style="font-size: 5rem;"></i>
                        <h5 class="mt-3 text-muted">Chưa có quiz nào</h5>
                        <p class="text-muted">Giáo viên sẽ tạo quiz sớm thôi!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
