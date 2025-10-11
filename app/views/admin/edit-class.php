<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/admin/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="mb-4">
                <h2 class="fw-bold">Sửa lớp học</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/classes">Lớp học</a></li>
                        <li class="breadcrumb-item active">Sửa</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <form method="POST" action="<?= BASE_URL ?>/admin/editClass/<?= $class['id'] ?>">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">Tên lớp <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="name" 
                                               class="form-control form-control-lg" 
                                               value="<?= e($class['name']) ?>"
                                               required>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">Mã lớp <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="code" 
                                               class="form-control form-control-lg" 
                                               value="<?= e($class['code']) ?>"
                                               required>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">Khối <span class="text-danger">*</span></label>
                                        <select name="grade_level" class="form-select" required>
                                            <option value="">-- Chọn khối --</option>
                                            <option value="10" <?= $class['grade_level'] == 10 ? 'selected' : '' ?>>Lớp 10</option>
                                            <option value="11" <?= $class['grade_level'] == 11 ? 'selected' : '' ?>>Lớp 11</option>
                                            <option value="12" <?= $class['grade_level'] == 12 ? 'selected' : '' ?>>Lớp 12</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">Năm học <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="academic_year" 
                                               class="form-control" 
                                               value="<?= e($class['academic_year']) ?>"
                                               required>
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <label class="form-label fw-bold">Giáo viên chủ nhiệm</label>
                                        <select name="teacher_id" class="form-select">
                                            <option value="">-- Chọn GVCN --</option>
                                            <?php if (!empty($teachers)): ?>
                                                <?php foreach ($teachers as $teacher): ?>
                                                    <option value="<?= $teacher['id'] ?>" 
                                                            <?= $class['teacher_id'] == $teacher['id'] ? 'selected' : '' ?>>
                                                        <?= e($teacher['full_name']) ?> - <?= e($teacher['email']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold">Trạng thái</label>
                                        <select name="status" class="form-select">
                                            <option value="active" <?= $class['status'] == 'active' ? 'selected' : '' ?>>Hoạt động</option>
                                            <option value="inactive" <?= $class['status'] == 'inactive' ? 'selected' : '' ?>>Không hoạt động</option>
                                        </select>
                                    </div>

                                    <div class="col-12 mb-4">
                                        <label class="form-label fw-bold">Mô tả lớp học</label>
                                        <textarea name="description" 
                                                  class="form-control" 
                                                  rows="4"><?= e($class['description']) ?></textarea>
                                    </div>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between">
                                    <a href="<?= BASE_URL ?>/admin/classes" class="btn btn-outline-secondary">
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
                            <h6 class="mb-0 fw-bold">Thông tin lớp</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Số học sinh:</strong> <?= $class['student_count'] ?? 0 ?></p>
                            <p class="mb-2"><strong>Ngày tạo:</strong> <?= formatDate($class['created_at']) ?></p>
                            <p class="mb-0"><strong>Cập nhật:</strong> <?= formatDate($class['updated_at']) ?></p>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Lưu ý:</strong> Thay đổi thông tin lớp có thể ảnh hưởng đến học sinh trong lớp.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
