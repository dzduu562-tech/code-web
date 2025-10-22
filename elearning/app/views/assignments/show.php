<?php $pageTitle = Helpers::escape($assignment['title']); ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1><?php echo Helpers::escape($assignment['title']); ?></h1>
        <p class="text-muted">Khóa học: <?php echo Helpers::escape($assignment['course_title']); ?></p>
    </div>
    
    <div class="assignment-detail">
        <div class="assignment-info">
            <h3>Mô tả</h3>
            <p><?php echo nl2br(Helpers::escape($assignment['description'] ?? '')); ?></p>
            
            <?php if ($assignment['due_at']): ?>
                <p><strong>Hạn nộp:</strong> <?php echo Helpers::formatDate($assignment['due_at']); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="submission-section">
            <h3>Nộp bài</h3>
            
            <?php if ($submission): ?>
                <div class="submission-info">
                    <p><strong>Đã nộp:</strong> <?php echo Helpers::formatDate($submission['submitted_at']); ?></p>
                    
                    <?php if ($submission['file_path']): ?>
                        <p><strong>File:</strong> <a href="/elearning/public/<?php echo Helpers::escape($submission['file_path']); ?>" download>Tải xuống</a></p>
                    <?php endif; ?>
                    
                    <?php if ($submission['url_submission']): ?>
                        <p><strong>URL:</strong> <a href="<?php echo Helpers::escape($submission['url_submission']); ?>" target="_blank"><?php echo Helpers::escape($submission['url_submission']); ?></a></p>
                    <?php endif; ?>
                    
                    <?php if ($submission['note']): ?>
                        <p><strong>Ghi chú:</strong> <?php echo nl2br(Helpers::escape($submission['note'])); ?></p>
                    <?php endif; ?>
                    
                    <?php if ($submission['graded_at']): ?>
                        <div class="grade-info">
                            <h4>Kết quả</h4>
                            <p class="score-large"><?php echo $submission['score']; ?>/10</p>
                            <?php if ($submission['feedback']): ?>
                                <p><strong>Nhận xét:</strong> <?php echo nl2br(Helpers::escape($submission['feedback'])); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Bài làm đang chờ được chấm điểm</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <form action="/elearning/public/index.php?route=assignments/submit" method="POST" enctype="multipart/form-data" class="form">
                    <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
                    <input type="hidden" name="assignment_id" value="<?php echo $assignment['id']; ?>">
                    
                    <div class="form-group">
                        <label for="file">Nộp file</label>
                        <input type="file" id="file" name="file" class="form-control">
                        <small class="form-text">Tối đa 50MB</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="url_submission">Hoặc nộp URL</label>
                        <input type="url" id="url_submission" name="url_submission" class="form-control" placeholder="https://...">
                    </div>
                    
                    <div class="form-group">
                        <label for="note">Ghi chú</label>
                        <textarea id="note" name="note" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Nộp bài</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
