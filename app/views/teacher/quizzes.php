<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/teacher/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Quản lý Quiz</h2>
                <a href="<?= BASE_URL ?>/teacher/createQuiz" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tạo quiz mới
                </a>
            </div>

            <!-- Stats -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-clipboard-check text-primary" style="font-size: 2.5rem;"></i>
                            <h3 class="mt-2 fw-bold">0</h3>
                            <p class="text-muted mb-0">Tổng Quiz</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-people text-success" style="font-size: 2.5rem;"></i>
                            <h3 class="mt-2 fw-bold">0</h3>
                            <p class="text-muted mb-0">Lượt làm bài</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-graph-up text-warning" style="font-size: 2.5rem;"></i>
                            <h3 class="mt-2 fw-bold">0%</h3>
                            <p class="text-muted mb-0">Điểm TB</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quiz List -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <?php if (empty($quizzes)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-clipboard2-x text-muted" style="font-size: 5rem;"></i>
                            <h5 class="mt-3 text-muted">Chưa có quiz nào</h5>
                            <p class="text-muted">Tạo quiz đầu tiên để kiểm tra học sinh!</p>
                            <a href="<?= BASE_URL ?>/teacher/createQuiz" class="btn btn-primary mt-3">
                                <i class="bi bi-plus-circle"></i> Tạo Quiz
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Quiz</th>
                                        <th>Khóa học</th>
                                        <th>Câu hỏi</th>
                                        <th>Lượt làm</th>
                                        <th>Điểm TB</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($quizzes as $quiz): ?>
                                        <tr>
                                            <td>
                                                <strong><?= e($quiz['title']) ?></strong><br>
                                                <small class="text-muted"><?= $quiz['time_limit'] ?> phút</small>
                                            </td>
                                            <td><?= e($quiz['course_title']) ?></td>
                                            <td><?= $quiz['question_count'] ?? 0 ?> câu</td>
                                            <td><?= $quiz['attempt_count'] ?? 0 ?> lượt</td>
                                            <td>
                                                <span class="text-muted">-</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= BASE_URL ?>/teacher/manageQuiz/<?= $quiz['id'] ?>" 
                                                       class="btn btn-outline-primary" 
                                                       title="Quản lý câu hỏi">
                                                        <i class="bi bi-list-ul"></i>
                                                    </a>
                                                    <a href="<?= BASE_URL ?>/teacher/editQuiz/<?= $quiz['id'] ?>" 
                                                       class="btn btn-outline-success" 
                                                       title="Sửa">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="<?= BASE_URL ?>/teacher/deleteQuiz/<?= $quiz['id'] ?>" 
                                                       class="btn btn-outline-danger"
                                                       onclick="return confirm('Xóa quiz và tất cả câu hỏi?')" 
                                                       title="Xóa">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
