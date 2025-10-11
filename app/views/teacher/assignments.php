<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/teacher/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Quản lý bài tập</h2>
                <a href="<?= BASE_URL ?>/teacher/createAssignment" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tạo bài tập mới
                </a>
            </div>

            <!-- Stats -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-file-earmark-text text-primary" style="font-size: 2.5rem;"></i>
                            <h3 class="mt-2 fw-bold">0</h3>
                            <p class="text-muted mb-0">Tổng bài tập</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-hourglass-split text-warning" style="font-size: 2.5rem;"></i>
                            <h3 class="mt-2 fw-bold">0</h3>
                            <p class="text-muted mb-0">Chờ chấm</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-check-circle text-success" style="font-size: 2.5rem;"></i>
                            <h3 class="mt-2 fw-bold">0</h3>
                            <p class="text-muted mb-0">Đã chấm</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignments List -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 5rem;"></i>
                        <h5 class="mt-3 text-muted">Chưa có bài tập nào</h5>
                        <p class="text-muted">Tạo bài tập đầu tiên cho học sinh!</p>
                        <a href="<?= BASE_URL ?>/teacher/createAssignment" class="btn btn-primary mt-3">
                            <i class="bi bi-plus-circle"></i> Tạo bài tập
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
