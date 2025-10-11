<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/admin/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="mb-4">
                <h2 class="fw-bold">Thêm môn học mới</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/subjects">Môn học</a></li>
                        <li class="breadcrumb-item active">Thêm mới</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <form method="POST" action="<?= BASE_URL ?>/admin/addSubject">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Tên môn học <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="name" 
                                           class="form-control form-control-lg" 
                                           placeholder="Ví dụ: Toán học"
                                           required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Mã môn học <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="code" 
                                           class="form-control" 
                                           placeholder="Ví dụ: MATH"
                                           required>
                                    <small class="text-muted">Viết hoa, không dấu, không khoảng trắng</small>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Mô tả</label>
                                    <textarea name="description" 
                                              class="form-control" 
                                              rows="3"
                                              placeholder="Mô tả về môn học..."></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Màu sắc</label>
                                        <input type="color" 
                                               name="color" 
                                               class="form-control form-control-color" 
                                               value="#3B82F6">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Icon (Bootstrap Icons)</label>
                                        <select name="icon" class="form-select">
                                            <option value="book">📚 book (Sách)</option>
                                            <option value="calculator">🔢 calculator (Máy tính)</option>
                                            <option value="globe">🌍 globe (Địa cầu)</option>
                                            <option value="flask">🧪 flask (Ống nghiệm)</option>
                                            <option value="palette">🎨 palette (Bảng màu)</option>
                                            <option value="music-note">🎵 music-note (Nốt nhạc)</option>
                                            <option value="chat-dots">💬 chat-dots (Chat)</option>
                                            <option value="code-slash">💻 code-slash (Lập trình)</option>
                                            <option value="piggy-bank">🐷 piggy-bank (Tiết kiệm)</option>
                                            <option value="graph-up">📈 graph-up (Biểu đồ)</option>
                                        </select>
                                    </div>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between">
                                    <a href="<?= BASE_URL ?>/admin/subjects" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left"></i> Quay lại
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-check-circle"></i> Tạo môn học
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="mb-0 fw-bold">Xem trước</h6>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3" style="font-size: 5rem; color: #3B82F6;">
                                <i class="bi bi-book"></i>
                            </div>
                            <h4 class="fw-bold">Tên môn học</h4>
                            <p class="text-muted">Mô tả môn học sẽ hiển thị ở đây</p>
                            <span class="badge bg-success">Hoạt động</span>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mt-3">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="mb-0 fw-bold">Danh sách môn học phổ biến</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-2">📚 Toán học - MATH</li>
                                <li class="mb-2">📝 Ngữ văn - LITERATURE</li>
                                <li class="mb-2">🌍 Địa lý - GEOGRAPHY</li>
                                <li class="mb-2">📜 Lịch sử - HISTORY</li>
                                <li class="mb-2">🧪 Hóa học - CHEMISTRY</li>
                                <li class="mb-2">⚡ Vật lý - PHYSICS</li>
                                <li class="mb-2">🌱 Sinh học - BIOLOGY</li>
                                <li class="mb-2">🇬🇧 Tiếng Anh - ENGLISH</li>
                                <li class="mb-2">💻 Tin học - INFORMATICS</li>
                                <li class="mb-2">⚽ Thể dục - PE</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
