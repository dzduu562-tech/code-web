<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/admin/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <h2 class="fw-bold mb-4">Báo cáo & Thống kê</h2>

            <!-- Stats Overview -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-book-fill text-primary" style="font-size: 2.5rem;"></i>
                            <h3 class="mt-2 fw-bold"><?= $stats['total_courses'] ?? 0 ?></h3>
                            <p class="text-muted mb-0">Khóa học</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-journal-text text-success" style="font-size: 2.5rem;"></i>
                            <h3 class="mt-2 fw-bold"><?= $stats['total_lessons'] ?? 0 ?></h3>
                            <p class="text-muted mb-0">Bài giảng</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-clipboard-check text-warning" style="font-size: 2.5rem;"></i>
                            <h3 class="mt-2 fw-bold"><?= $stats['total_quizzes'] ?? 0 ?></h3>
                            <p class="text-muted mb-0">Quiz</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-file-earmark-text text-info" style="font-size: 2.5rem;"></i>
                            <h3 class="mt-2 fw-bold"><?= $stats['total_assignments'] ?? 0 ?></h3>
                            <p class="text-muted mb-0">Bài tập</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Placeholder -->
            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold">Biểu đồ tăng trưởng</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center py-5">
                                <i class="bi bi-graph-up text-muted" style="font-size: 5rem;"></i>
                                <p class="text-muted mt-3">Biểu đồ sẽ được hiển thị ở đây</p>
                                <small class="text-muted">Tích hợp Chart.js để hiển thị biểu đồ chi tiết</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold">Export báo cáo</h5>
                        </div>
                        <div class="card-body">
                            <button class="btn btn-outline-success w-100 mb-2">
                                <i class="bi bi-file-excel"></i> Export Excel
                            </button>
                            <button class="btn btn-outline-danger w-100 mb-2">
                                <i class="bi bi-file-pdf"></i> Export PDF
                            </button>
                            <button class="btn btn-outline-primary w-100">
                                <i class="bi bi-printer"></i> In báo cáo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
