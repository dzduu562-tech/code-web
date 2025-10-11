<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/teacher/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <h2 class="fw-bold mb-4">Quản lý học sinh</h2>

            <!-- Filter by Course -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <select class="form-select">
                                <option value="">Tất cả khóa học</option>
                                <option>Toán học lớp 10</option>
                                <option>Tiếng Anh cơ bản</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="Tìm học sinh...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students List -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Học sinh</th>
                                    <th>Email</th>
                                    <th>Khóa học</th>
                                    <th>Tiến độ</th>
                                    <th>Điểm TB</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        Chưa có học sinh nào trong khóa học
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
