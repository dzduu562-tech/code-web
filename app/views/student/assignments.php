<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/student/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Bài tập</h2>
                <div>
                    <button class="btn btn-outline-primary">
                        <i class="bi bi-funnel"></i> Lọc
                    </button>
                </div>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#pending">
                        <i class="bi bi-clock"></i> Chưa nộp (0)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#submitted">
                        <i class="bi bi-check-circle"></i> Đã nộp (0)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#graded">
                        <i class="bi bi-star"></i> Đã chấm (0)
                    </a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Pending Tab -->
                <div class="tab-pane fade show active" id="pending">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 5rem;"></i>
                                <h5 class="mt-3 text-muted">Không có bài tập nào chưa nộp</h5>
                                <p class="text-muted">Bạn đã hoàn thành tất cả bài tập!</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submitted Tab -->
                <div class="tab-pane fade" id="submitted">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="text-center py-5">
                                <i class="bi bi-check-circle text-muted" style="font-size: 5rem;"></i>
                                <h5 class="mt-3 text-muted">Chưa có bài tập nào đã nộp</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graded Tab -->
                <div class="tab-pane fade" id="graded">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="text-center py-5">
                                <i class="bi bi-star text-muted" style="font-size: 5rem;"></i>
                                <h5 class="mt-3 text-muted">Chưa có bài tập nào được chấm</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
