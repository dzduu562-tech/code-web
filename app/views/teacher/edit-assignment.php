<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/teacher/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="mb-4">
                <h2 class="fw-bold">Sửa bài tập</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/teacher/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/teacher/assignments">Bài tập</a></li>
                        <li class="breadcrumb-item active">Sửa</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <form method="POST" action="<?= BASE_URL ?>/teacher/editAssignment/<?= $assignment['id'] ?>">
                                <!-- Tiêu đề -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Tiêu đề bài tập <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="title" 
                                           class="form-control form-control-lg" 
                                           value="<?= e($assignment['title']) ?>"
                                           required>
                                </div>

                                <!-- Mô tả -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Mô tả bài tập</label>
                                    <textarea name="description" 
                                              class="form-control" 
                                              rows="4"><?= e($assignment['description']) ?></textarea>
                                </div>

                                <!-- Hướng dẫn -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Hướng dẫn chi tiết</label>
                                    <textarea name="instructions" 
                                              class="form-control" 
                                              rows="6"><?= e($assignment['instructions']) ?></textarea>
                                </div>

                                <div class="row">
                                    <!-- Điểm tối đa -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Điểm tối đa</label>
                                        <input type="number" 
                                               name="max_score" 
                                               class="form-control" 
                                               value="<?= $assignment['max_score'] ?>"
                                               min="0" 
                                               max="1000">
                                    </div>

                                    <!-- Hạn nộp -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Hạn nộp bài</label>
                                        <input type="datetime-local" 
                                               name="due_date" 
                                               class="form-control"
                                               value="<?= $assignment['due_date'] ? date('Y-m-d\TH:i', strtotime($assignment['due_date'])) : '' ?>">
                                    </div>

                                </div>

                                <hr>

                                <div class="d-flex justify-content-between">
                                    <a href="<?= BASE_URL ?>/teacher/assignments" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left"></i> Quay lại
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-save"></i> Lưu thay đổi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="mb-0 fw-bold">Thông tin bài tập</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Khóa học:</strong> <?= e($assignment['course_title'] ?? 'N/A') ?></p>
                            <p class="mb-2"><strong>Bài nộp:</strong> <?= $assignment['total_submissions'] ?? 0 ?></p>
                            <p class="mb-0"><strong>Ngày tạo:</strong> <?= formatDate($assignment['created_at']) ?></p>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="mb-0 fw-bold">Thao tác</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="<?= BASE_URL ?>/teacher/deleteAssignment/<?= $assignment['id'] ?>" 
                                   class="btn btn-danger"
                                   onclick="return confirm('Xóa bài tập này?')">
                                    <i class="bi bi-trash"></i> Xóa bài tập
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
