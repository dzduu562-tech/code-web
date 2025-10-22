<?php $pageTitle = 'Bài nộp - ' . Helpers::escape($assignment['title']); ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Bài nộp: <?php echo Helpers::escape($assignment['title']); ?></h1>
        <p class="text-muted">Khóa học: <?php echo Helpers::escape($assignment['course_title']); ?></p>
    </div>
    
    <?php if (empty($submissions)): ?>
        <div class="empty-state">
            <p>Chưa có học sinh nào nộp bài</p>
        </div>
    <?php else: ?>
        <div class="submissions-list">
            <?php foreach ($submissions as $sub): ?>
                <div class="submission-card">
                    <div class="submission-header">
                        <h3><?php echo Helpers::escape($sub['student_name']); ?></h3>
                        <span class="submission-date"><?php echo Helpers::formatDate($sub['submitted_at']); ?></span>
                    </div>
                    
                    <div class="submission-content">
                        <?php if ($sub['file_path']): ?>
                            <p><strong>File:</strong> <a href="/elearning/public/<?php echo Helpers::escape($sub['file_path']); ?>" download>Tải xuống</a></p>
                        <?php endif; ?>
                        
                        <?php if ($sub['url_submission']): ?>
                            <p><strong>URL:</strong> <a href="<?php echo Helpers::escape($sub['url_submission']); ?>" target="_blank"><?php echo Helpers::truncate(Helpers::escape($sub['url_submission']), 50); ?></a></p>
                        <?php endif; ?>
                        
                        <?php if ($sub['note']): ?>
                            <p><strong>Ghi chú:</strong> <?php echo nl2br(Helpers::escape($sub['note'])); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($sub['graded_at']): ?>
                        <div class="graded-info">
                            <p><strong>Điểm:</strong> <?php echo $sub['score']; ?>/10</p>
                            <?php if ($sub['feedback']): ?>
                                <p><strong>Nhận xét:</strong> <?php echo nl2br(Helpers::escape($sub['feedback'])); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <form action="/elearning/public/index.php?route=assignments/grade" method="POST" class="grade-form">
                            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
                            <input type="hidden" name="submission_id" value="<?php echo $sub['id']; ?>">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="score_<?php echo $sub['id']; ?>">Điểm (0-10)</label>
                                    <input type="number" id="score_<?php echo $sub['id']; ?>" name="score" class="form-control" min="0" max="10" step="0.5" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="feedback_<?php echo $sub['id']; ?>">Nhận xét</label>
                                    <textarea id="feedback_<?php echo $sub['id']; ?>" name="feedback" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Chấm điểm</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
