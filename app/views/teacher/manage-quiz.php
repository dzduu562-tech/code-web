<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/teacher/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="mb-4">
                <h2 class="fw-bold"><?= e($quiz['title']) ?></h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/teacher/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/teacher/quizzes">Quiz</a></li>
                        <li class="breadcrumb-item active">Quản lý</li>
                    </ol>
                </nav>
            </div>

            <!-- Quiz Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="bi bi-clock text-primary" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2"><strong><?= $quiz['time_limit'] ?> phút</strong></p>
                                <small class="text-muted">Thời gian</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="bi bi-trophy text-warning" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2"><strong><?= $quiz['pass_score'] ?>%</strong></p>
                                <small class="text-muted">Điểm đạt</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="bi bi-list-ol text-success" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2"><strong><?= count($questions) ?> câu</strong></p>
                                <small class="text-muted">Câu hỏi</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="bi bi-people text-info" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2"><strong><?= $quiz['attempt_count'] ?? 0 ?></strong></p>
                                <small class="text-muted">Lượt làm</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="d-flex justify-content-between mb-4">
                <div>
                    <a href="<?= BASE_URL ?>/teacher/editQuiz/<?= $quiz['id'] ?>" class="btn btn-outline-primary">
                        <i class="bi bi-pencil"></i> Sửa Quiz
                    </a>
                    <a href="<?= BASE_URL ?>/teacher/quizzes" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
                    <i class="bi bi-plus-circle"></i> Thêm câu hỏi
                </button>
            </div>

            <!-- Questions List -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">Danh sách câu hỏi</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($questions)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox text-muted" style="font-size: 5rem;"></i>
                            <h5 class="mt-3 text-muted">Chưa có câu hỏi nào</h5>
                            <p class="text-muted">Thêm câu hỏi đầu tiên cho quiz!</p>
                            <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
                                <i class="bi bi-plus-circle"></i> Thêm câu hỏi
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($questions as $index => $question): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="badge bg-primary me-2">Câu <?= $index + 1 ?></span>
                                                <span class="badge bg-info me-2">
                                                    <?php
                                                    $types = [
                                                        'multiple_choice' => 'Trắc nghiệm',
                                                        'true_false' => 'Đúng/Sai',
                                                        'short_answer' => 'Trả lời ngắn'
                                                    ];
                                                    echo $types[$question['question_type']] ?? $question['question_type'];
                                                    ?>
                                                </span>
                                                <span class="badge bg-warning text-dark"><?= $question['points'] ?> điểm</span>
                                            </div>
                                            <h6 class="mb-2"><?= e($question['question_text']) ?></h6>
                                            
                                            <?php if ($question['question_type'] == 'multiple_choice' && $question['options']): ?>
                                                <?php $options = json_decode($question['options'], true); ?>
                                                <div class="ms-3">
                                                    <?php foreach ($options as $key => $option): ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" disabled 
                                                                   <?= $question['correct_answer'] == $key ? 'checked' : '' ?>>
                                                            <label class="form-check-label small <?= $question['correct_answer'] == $key ? 'text-success fw-bold' : '' ?>">
                                                                <?= e($option) ?>
                                                                <?= $question['correct_answer'] == $key ? '✓' : '' ?>
                                                            </label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php elseif ($question['question_type'] == 'true_false'): ?>
                                                <p class="text-success mb-0 ms-3">
                                                    <strong>Đáp án:</strong> <?= $question['correct_answer'] == '1' ? 'Đúng ✓' : 'Sai ✓' ?>
                                                </p>
                                            <?php else: ?>
                                                <p class="text-success mb-0 ms-3">
                                                    <strong>Đáp án:</strong> <?= e($question['correct_answer']) ?>
                                                </p>
                                            <?php endif; ?>
                                            
                                            <?php if ($question['explanation']): ?>
                                                <p class="text-muted small mt-2 mb-0">
                                                    <i class="bi bi-lightbulb"></i> <strong>Giải thích:</strong> <?= e($question['explanation']) ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="ms-3">
                                            <a href="<?= BASE_URL ?>/teacher/deleteQuestion/<?= $question['id'] ?>" 
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Xóa câu hỏi này?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Question Modal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Thêm câu hỏi mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/teacher/addQuestion/<?= $quiz['id'] ?>">
                <div class="modal-body">
                    <!-- Loại câu hỏi -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Loại câu hỏi</label>
                        <select name="question_type" id="questionType" class="form-select" required>
                            <option value="multiple_choice">Trắc nghiệm (4 đáp án)</option>
                            <option value="true_false">Đúng/Sai</option>
                            <option value="short_answer">Trả lời ngắn</option>
                        </select>
                    </div>

                    <!-- Câu hỏi -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Câu hỏi <span class="text-danger">*</span></label>
                        <textarea name="question_text" class="form-control" rows="3" required></textarea>
                    </div>

                    <!-- Options (Multiple Choice) -->
                    <div id="multipleChoiceOptions" class="mb-3">
                        <label class="form-label fw-bold">Đáp án</label>
                        <input type="text" name="option_a" class="form-control mb-2" placeholder="A. Đáp án thứ nhất">
                        <input type="text" name="option_b" class="form-control mb-2" placeholder="B. Đáp án thứ hai">
                        <input type="text" name="option_c" class="form-control mb-2" placeholder="C. Đáp án thứ ba">
                        <input type="text" name="option_d" class="form-control mb-2" placeholder="D. Đáp án thứ tư">
                        <label class="form-label fw-bold mt-2">Đáp án đúng</label>
                        <select name="correct_answer_mc" class="form-select">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>

                    <!-- True/False -->
                    <div id="trueFalseOptions" class="mb-3" style="display:none;">
                        <label class="form-label fw-bold">Đáp án đúng</label>
                        <select name="correct_answer_tf" class="form-select">
                            <option value="1">Đúng</option>
                            <option value="0">Sai</option>
                        </select>
                    </div>

                    <!-- Short Answer -->
                    <div id="shortAnswerOptions" class="mb-3" style="display:none;">
                        <label class="form-label fw-bold">Đáp án đúng</label>
                        <input type="text" name="correct_answer_sa" class="form-control" placeholder="Nhập đáp án">
                    </div>

                    <!-- Điểm -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Điểm</label>
                        <input type="number" name="points" class="form-control" value="10" min="1" max="100">
                    </div>

                    <!-- Giải thích -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Giải thích (tuỳ chọn)</label>
                        <textarea name="explanation" class="form-control" rows="2" placeholder="Giải thích đáp án đúng..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Thêm câu hỏi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('questionType').addEventListener('change', function() {
    const type = this.value;
    document.getElementById('multipleChoiceOptions').style.display = type === 'multiple_choice' ? 'block' : 'none';
    document.getElementById('trueFalseOptions').style.display = type === 'true_false' ? 'block' : 'none';
    document.getElementById('shortAnswerOptions').style.display = type === 'short_answer' ? 'block' : 'none';
});
</script>

<?php require_once APP . '/views/layouts/footer.php'; ?>
