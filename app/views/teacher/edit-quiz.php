<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/teacher/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="mb-4">
                <h2 class="fw-bold">Sửa Quiz</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/teacher/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/teacher/quizzes">Quiz</a></li>
                        <li class="breadcrumb-item active">Sửa</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <form method="POST" action="<?= BASE_URL ?>/teacher/editQuiz/<?= $quiz['id'] ?>">
                                <!-- Tiêu đề -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Tiêu đề Quiz <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="title" 
                                           class="form-control form-control-lg" 
                                           value="<?= e($quiz['title']) ?>"
                                           required>
                                </div>

                                <!-- Mô tả -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Mô tả Quiz</label>
                                    <textarea name="description" 
                                              class="form-control" 
                                              rows="4"><?= e($quiz['description']) ?></textarea>
                                </div>

                                <div class="row">
                                    <!-- Thời gian -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">Thời gian (phút)</label>
                                        <input type="number" 
                                               name="time_limit" 
                                               class="form-control" 
                                               value="<?= $quiz['time_limit'] ?>"
                                               min="1" 
                                               max="300">
                                    </div>

                                    <!-- Điểm đạt -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">Điểm đạt (%)</label>
                                        <input type="number" 
                                               class="form-control" 
                                               min="0" 
                                               max="100">
                                    </div>

                                    <!-- Số lần thử -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">Số lần làm</label>
                                        <input type="number" 
                                               name="max_attempts" 
                                               class="form-control" 
                                               value="<?= $quiz['max_attempts'] ?>"
                                               min="1" 
                                               max="10">
                                    </div>

                                </div>


                                <hr>

                                <div class="d-flex justify-content-between">
                                    <a href="<?= BASE_URL ?>/teacher/quizzes" class="btn btn-outline-secondary">
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
                            <h6 class="mb-0 fw-bold">Thông tin Quiz</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Số câu hỏi:</strong> <?= $quiz['question_count'] ?? 0 ?></p>
                            <p class="mb-2"><strong>Lượt làm:</strong> <?= $quiz['attempt_count'] ?? 0 ?></p>
                            <p class="mb-2"><strong>Khóa học:</strong> <?= e($quiz['course_title'] ?? 'N/A') ?></p>
                            <p class="mb-0"><strong>Ngày tạo:</strong> <?= formatDate($quiz['created_at']) ?></p>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="mb-0 fw-bold">Thao tác</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="<?= BASE_URL ?>/teacher/manageQuiz/<?= $quiz['id'] ?>" 
                                   class="btn btn-success">
                                    <i class="bi bi-list-ul"></i> Quản lý câu hỏi
                                </a>
                                <a href="<?= BASE_URL ?>/teacher/deleteQuiz/<?= $quiz['id'] ?>" 
                                   class="btn btn-danger"
                                   onclick="return confirm('Xóa quiz này và tất cả câu hỏi?')">
                                    <i class="bi bi-trash"></i> Xóa Quiz
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
