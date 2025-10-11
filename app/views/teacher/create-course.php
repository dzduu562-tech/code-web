<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/teacher/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="mb-4">
                <h2 class="fw-bold">Tạo khóa học mới</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/teacher/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/teacher/courses">Khóa học</a></li>
                        <li class="breadcrumb-item active">Tạo mới</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <form method="POST" action="<?= BASE_URL ?>/teacher/createCourse" enctype="multipart/form-data">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Tiêu đề khóa học <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="title" 
                                           class="form-control form-control-lg" 
                                           placeholder="Ví dụ: Toán học lớp 10 - Đại số"
                                           required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Mô tả khóa học</label>
                                    <textarea name="description" 
                                              class="form-control" 
                                              rows="5"
                                              placeholder="Mô tả chi tiết về khóa học..."></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-bold">Môn học</label>
                                        <select name="subject_id" class="form-select">
                                            <option value="">-- Chọn môn học (tùy chọn) --</option>
                                            <?php if (!empty($subjects)): ?>
                                                <?php foreach ($subjects as $subject): ?>
                                                    <option value="<?= $subject['id'] ?>">
                                                        <?= e($subject['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between">
                                    <a href="<?= BASE_URL ?>/teacher/courses" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left"></i> Hủy
                                    </a>
                                    <div>
                                        <button type="submit" name="status" value="draft" class="btn btn-outline-primary me-2">
                                            <i class="bi bi-save"></i> Lưu nháp
                                        </button>
                                        <button type="submit" name="status" value="published" class="btn btn-primary">
                                            <i class="bi bi-check-circle"></i> Tạo và xuất bản
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="mb-0 fw-bold">Hướng dẫn</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled small">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Tiêu đề nên ngắn gọn, rõ ràng
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Mô tả chi tiết nội dung khóa học
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Chọn môn học phù hợp
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Upload ảnh đại diện chất lượng cao
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
