<?php $pageTitle = 'Tạo Quiz mới'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Tạo Quiz mới</h1>
    </div>
    
    <div class="form-container">
        <form action="/elearning/public/index.php?route=quiz/create&lesson_id=<?php echo $_GET['lesson_id']; ?>" method="POST" class="form" id="quizForm">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
            
            <div class="form-group">
                <label for="title">Tiêu đề Quiz *</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea id="description" name="description" class="form-control" rows="3"></textarea>
            </div>
            
            <h3>Câu hỏi</h3>
            <div id="questionsContainer">
                <!-- Questions will be added here by JavaScript -->
            </div>
            
            <button type="button" class="btn btn-outline" onclick="addQuestion()">+ Thêm câu hỏi</button>
            
            <div class="form-actions">
                <a href="/elearning/public/index.php?route=lesson&id=<?php echo $_GET['lesson_id']; ?>" class="btn btn-outline">Hủy</a>
                <button type="submit" class="btn btn-primary">Tạo Quiz</button>
            </div>
        </form>
    </div>
</div>

<script>
let questionCount = 0;

function addQuestion() {
    questionCount++;
    const container = document.getElementById('questionsContainer');
    const questionDiv = document.createElement('div');
    questionDiv.className = 'question-builder';
    questionDiv.innerHTML = `
        <h4>Câu hỏi ${questionCount}</h4>
        <input type="text" name="questions[${questionCount}][text]" class="form-control" placeholder="Nội dung câu hỏi" required>
        <div class="options-builder">
            <label><input type="checkbox" name="questions[${questionCount}][options][0][is_correct]"> <input type="text" name="questions[${questionCount}][options][0][text]" placeholder="Đáp án A" required></label>
            <label><input type="checkbox" name="questions[${questionCount}][options][1][is_correct]"> <input type="text" name="questions[${questionCount}][options][1][text]" placeholder="Đáp án B" required></label>
            <label><input type="checkbox" name="questions[${questionCount}][options][2][is_correct]"> <input type="text" name="questions[${questionCount}][options][2][text]" placeholder="Đáp án C" required></label>
            <label><input type="checkbox" name="questions[${questionCount}][options][3][is_correct]"> <input type="text" name="questions[${questionCount}][options][3][text]" placeholder="Đáp án D" required></label>
        </div>
    `;
    container.appendChild(questionDiv);
}

// Add first question by default
addQuestion();
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
