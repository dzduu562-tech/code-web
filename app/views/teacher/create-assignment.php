<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/teacher/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="mb-4">
                <h2 class="fw-bold">Tạo bài tập mới</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/teacher/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/teacher/assignments">Bài tập</a></li>
                        <li class="breadcrumb-item active">Tạo mới</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <form method="POST" action="<?= BASE_URL ?>/teacher/createAssignment">
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
                                    <label class="form-label fw-bold">Tiêu đề bài tập <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="title" 
                                           class="form-control form-control-lg" 
                                           placeholder="Ví dụ: Bài tập về phương trình bậc 2"
                                           required>
                                </div>

                                <!-- Mô tả -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Mô tả bài tập</label>
                                    <textarea name="description" 
                                              class="form-control" 
                                              rows="4"
                                              placeholder="Mô tả tổng quan về bài tập..."></textarea>
                                </div>

                                <!-- Hướng dẫn -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Hướng dẫn chi tiết</label>
                                    <textarea name="instructions" 
                                              class="form-control" 
                                              rows="6"
                                              placeholder="Hướng dẫn chi tiết cách làm bài..."></textarea>
                                </div>

                                <div class="row">
                                    <!-- Loại bài tập -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Loại bài tập</label>
                                        <select name="type" class="form-select">
                                            <option value="essay">Tự luận</option>
                                            <option value="file_upload">Upload file</option>
                                            <option value="text">Văn bản ngắn</option>
                                            <option value="code">Lập trình</option>
                                        </select>
                                    </div>

                                    <!-- Điểm tối đa -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Điểm tối đa</label>
                                        <input type="number" 
                                               name="max_score" 
                                               class="form-control" 
                                               value="100"
                                               min="0" 
                                               max="1000">
                                    </div>

                                    <!-- Hạn nộp -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Hạn nộp bài</label>
                                        <input type="datetime-local" 
                                               name="due_date" 
                                               class="form-control">
                                    </div>

                                    <!-- Cho phép nộp muộn -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold d-block">Tùy chọn</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   name="allow_late" 
                                                   id="allowLate">
                                            <label class="form-check-label" for="allowLate">
                                                Cho phép nộp muộn
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between">
                                    <a href="<?= BASE_URL ?>/teacher/assignments" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left"></i> Hủy
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-check-circle"></i> Tạo bài tập
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
                                <i class="bi bi-lightbulb text-warning"></i> Mẹo tạo bài tập hay
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled small">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Tiêu đề rõ ràng, dễ hiểu
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Hướng dẫn chi tiết từng bước
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Đặt deadline hợp lý
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Điểm số phù hợp với độ khó
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Kèm ví dụ minh họa
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="mb-0 fw-bold">
                                <i class="bi bi-info-circle text-info"></i> Loại bài tập
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="small mb-2"><strong>Tự luận:</strong> Câu trả lời dạng văn bản dài</p>
                            <p class="small mb-2"><strong>Upload file:</strong> Nộp file Word, PDF, hình ảnh</p>
                            <p class="small mb-2"><strong>Văn bản ngắn:</strong> Trả lời ngắn gọn</p>
                            <p class="small mb-0"><strong>Lập trình:</strong> Code, thuật toán</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
