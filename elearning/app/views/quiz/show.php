<?php $pageTitle = Helpers::escape($quiz['title']); ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1><?php echo Helpers::escape($quiz['title']); ?></h1>
        <?php if ($quiz['description']): ?>
            <p><?php echo Helpers::escape($quiz['description']); ?></p>
        <?php endif; ?>
    </div>
    
    <div class="quiz-info">
        <p><strong>Số câu hỏi:</strong> <?php echo count($questions); ?></p>
        
        <?php if (!empty($attempts)): ?>
            <div class="attempts-history">
                <h3>Lịch sử làm bài</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Lần</th>
                            <th>Điểm</th>
                            <th>Đúng/Tổng</th>
                            <th>Thời gian</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attempts as $index => $attempt): ?>
                            <tr>
                                <td>Lần <?php echo count($attempts) - $index; ?></td>
                                <td><strong><?php echo round($attempt['score'], 1); ?>%</strong></td>
                                <td><?php echo $attempt['correct_answers']; ?>/<?php echo $attempt['total_questions']; ?></td>
                                <td><?php echo Helpers::formatDate($attempt['finished_at']); ?></td>
                                <td><a href="/elearning/public/index.php?route=quiz/result&id=<?php echo $attempt['id']; ?>" class="btn btn-sm">Xem</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
        <?php if (Auth::isStudent()): ?>
            <form action="/elearning/public/index.php?route=quiz/start" method="POST">
                <input type="hidden" name="quiz_id" value="<?php echo $quiz['id']; ?>">
                <button type="submit" class="btn btn-primary btn-lg">Bắt đầu làm bài</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
