<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/teacher/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="mb-4">
                <h2 class="fw-bold">Tạo Quiz mới</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/teacher/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/teacher/quizzes">Quiz</a></li>
                        <li class="breadcrumb-item active">Tạo mới</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <form method="POST" action="<?= BASE_URL ?>/teacher/createQuiz">
                                <!-- Chọn khóa học -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Chọn khóa học <span class="text-danger">*</span></label>
                                    <select name="course_id" class="form-select" required>
                                        <option value="">-- Chọn khóa học --</option>
                                        <?php if (!empty($courses)): ?>
                                            <?php foreach ($courses as $course): ?>
                                                <option value="<?= $course['id'] ?>">
                                                    <?= e($course['title']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <!-- Tiêu đề -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Tiêu đề Quiz <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="title" 
                                           class="form-control form-control-lg" 
                                           placeholder="Ví dụ: Kiểm tra giữa kỳ - Chương 1"
                                           required>
                                </div>

                                <!-- Mô tả -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Mô tả Quiz</label>
                                    <textarea name="description" 
                                              class="form-control" 
                                              rows="4"
                                              placeholder="Mô tả nội dung và mục đích của quiz..."></textarea>
                                </div>

                                <div class="row">
                                    <!-- Thời gian -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">Thời gian (phút)</label>
                                        <input type="number" 
                                               name="time_limit" 
                                               class="form-control" 
                                               value="30"
                                               min="1" 
                                               max="300">
                                    </div>

                                    <!-- Điểm đạt -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">Điểm đạt (%)</label>
                                        <input type="number" 
                                               name="pass_score" 
                                               class="form-control" 
                                               value="70"
                                               min="0" 
                                               max="100">
                                    </div>

                                    <!-- Số lần thử -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-bold">Số lần làm</label>
                                        <input type="number" 
                                               name="max_attempts" 
                                               class="form-control" 
                                               value="1"
                                               min="1" 
                                               max="10">
                                    </div>

                                    <!-- Thời gian mở -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Mở từ</label>
                                        <input type="datetime-local" 
                                               name="available_from" 
                                               class="form-control">
                                    </div>

                                    <!-- Thời gian đóng -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Đóng lúc</label>
                                        <input type="datetime-local" 
                                               name="available_to" 
                                               class="form-control">
                                    </div>
                                </div>

                                <!-- Tùy chọn -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Tùy chọn Quiz</label>
                                    
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="shuffle_questions" 
                                               id="shuffle"
                                               checked>
                                        <label class="form-check-label" for="shuffle">
                                            Xáo trộn câu hỏi
                                        </label>
                                    </div>

                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="show_results" 
                                               id="showResults"
                                               checked>
                                        <label class="form-check-label" for="showResults">
                                            Hiển thị kết quả ngay sau khi nộp
                                        </label>
                                    </div>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between">
                                    <a href="<?= BASE_URL ?>/teacher/quizzes" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left"></i> Hủy
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-check-circle"></i> Tạo Quiz
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Tips -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="mb-0 fw-bold">
                                <i class="bi bi-lightbulb text-warning"></i> Mẹo tạo Quiz hay
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled small">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Câu hỏi rõ ràng, không gây nhầm lẫn
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Đáp án đúng duy nhất (trắc nghiệm)
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Thời gian phù hợp với số câu
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Xáo trộn để tránh gian lận
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    10-30 câu là tối ưu
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Lưu ý:</strong> Sau khi tạo Quiz, bạn có thể thêm câu hỏi trong trang quản lý Quiz.
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="mb-0 fw-bold">Các bước tiếp theo</h6>
                        </div>
                        <div class="card-body">
                            <ol class="small mb-0 ps-3">
                                <li class="mb-2">Tạo Quiz</li>
                                <li class="mb-2">Thêm câu hỏi</li>
                                <li class="mb-2">Xem trước</li>
                                <li>Xuất bản cho học sinh</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
