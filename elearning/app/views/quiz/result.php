<?php $pageTitle = 'Kết quả - ' . Helpers::escape($quiz['title']); ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="quiz-result">
        <h1>Kết quả: <?php echo Helpers::escape($quiz['title']); ?></h1>
        
        <div class="result-summary">
            <div class="score-display">
                <div class="score-circle <?php echo $attempt['score'] >= 70 ? 'pass' : 'fail'; ?>">
                    <span class="score-value"><?php echo round($attempt['score'], 1); ?>%</span>
                </div>
                
                <div class="score-details">
                    <p><strong>Số câu đúng:</strong> <?php echo $attempt['correct_answers']; ?>/<?php echo $attempt['total_questions']; ?></p>
                    <p><strong>Thời gian hoàn thành:</strong> <?php echo Helpers::formatDate($attempt['finished_at']); ?></p>
                    
                    <?php if ($attempt['score'] >= 70): ?>
                        <p class="text-success">✅ Chúc mừng! Bạn đã đạt yêu cầu.</p>
                    <?php else: ?>
                        <p class="text-warning">Bạn cần cố gắng thêm. Hãy làm lại để cải thiện điểm số!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="result-actions">
            <a href="/elearning/public/index.php?route=quiz&id=<?php echo $quiz['id']; ?>" class="btn btn-primary">Làm lại</a>
            <a href="/elearning/public/index.php?route=lesson&id=<?php echo $quiz['lesson_id']; ?>" class="btn btn-outline">Về bài học</a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
