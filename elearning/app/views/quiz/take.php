<?php $pageTitle = 'Làm bài: ' . Helpers::escape($quiz['title']); ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="quiz-take">
        <h1><?php echo Helpers::escape($quiz['title']); ?></h1>
        
        <form action="/elearning/public/index.php?route=quiz/submit" method="POST" class="quiz-form">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
            <input type="hidden" name="attempt_id" value="<?php echo $attempt['id']; ?>">
            
            <?php foreach ($questions as $index => $question): ?>
                <div class="question-card">
                    <h3>Câu <?php echo $index + 1; ?>: <?php echo Helpers::escape($question['text']); ?></h3>
                    
                    <div class="options-list">
                        <?php foreach ($question['options'] as $option): ?>
                            <label class="option-item">
                                <input 
                                    type="radio" 
                                    name="answers[<?php echo $question['id']; ?>]" 
                                    value="<?php echo $option['id']; ?>"
                                    required
                                >
                                <span><?php echo Helpers::escape($option['text']); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div class="quiz-actions">
                <button type="submit" class="btn btn-primary btn-lg">Nộp bài</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
