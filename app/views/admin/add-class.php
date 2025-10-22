<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/admin/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="mb-4">
                <h2 class="fw-bold">Thêm lớp học mới</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/classes">Lớp học</a></li>
                        <li class="breadcrumb-item active">Thêm mới</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <form method="POST" action="<?= BASE_URL ?>/admin/addClass">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">Tên lớp <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="name" 
                                               class="form-control form-control-lg" 
                                               placeholder="Ví dụ: 10A1"
                                               required>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">Mã lớp <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="code" 
                                               class="form-control form-control-lg" 
                                               placeholder="Ví dụ: 10A1-2024"
                                               required>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">Khối <span class="text-danger">*</span></label>
                                        <select name="grade_level" class="form-select" required>
                                            <option value="">-- Chọn khối --</option>
                                            <option value="10">Lớp 10</option>
                                            <option value="11">Lớp 11</option>
                                            <option value="12">Lớp 12</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">Năm học <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="academic_year" 
                                               class="form-control" 
                                               value="<?= date('Y') . '-' . (date('Y') + 1) ?>"
                                               placeholder="2024-2025"
                                               required>
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <label class="form-label fw-bold">Giáo viên chủ nhiệm</label>
                                        <select name="teacher_id" class="form-select">
                                            <option value="">-- Chọn GVCN --</option>
                                            <?php if (!empty($teachers)): ?>
                                                <?php foreach ($teachers as $teacher): ?>
                                                    <option value="<?= $teacher['id'] ?>">
                                                        <?= e($teacher['full_name']) ?> - <?= e($teacher['email']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <div class="col-12 mb-4">
                                        <label class="form-label fw-bold">Mô tả lớp học</label>
                                        <textarea name="description" 
                                                  class="form-control" 
                                                  rows="4"
                                                  placeholder="Mô tả về lớp học, đặc điểm, mục tiêu..."></textarea>
                                    </div>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between">
                                    <a href="<?= BASE_URL ?>/admin/classes" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left"></i> Quay lại
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-check-circle"></i> Tạo lớp học
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="mb-0 fw-bold">
                                <i class="bi bi-info-circle text-info"></i> Hướng dẫn
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled small">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Tên lớp ngắn gọn: 10A1, 11B2
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Mã lớp duy nhất trong năm học
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Chọn GVCN phù hợp
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                    Có thể thêm học sinh sau
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
