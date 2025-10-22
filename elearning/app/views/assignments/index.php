<?php $pageTitle = 'Bài tập'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Bài tập của tôi</h1>
    </div>
    
    <?php if (empty($assignments)): ?>
        <div class="empty-state">
            <p>Chưa có bài tập nào</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Bài tập</th>
                        <th>Khóa học</th>
                        <th>Hạn nộp</th>
                        <th>Trạng thái</th>
                        <th>Điểm</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assignments as $assignment): ?>
                        <tr>
                            <td><?php echo Helpers::escape($assignment['title']); ?></td>
                            <td><?php echo Helpers::escape($assignment['course_title']); ?></td>
                            <td><?php echo $assignment['due_at'] ? Helpers::formatDate($assignment['due_at']) : '-'; ?></td>
                            <td>
                                <?php if (isset($assignment['submission_id'])): ?>
                                    <?php if ($assignment['graded_at']): ?>
                                        <span class="badge badge-success">Đã chấm</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Đã nộp</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge">Chưa nộp</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (isset($assignment['score'])): ?>
                                    <strong><?php echo $assignment['score']; ?>/10</strong>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/elearning/public/index.php?route=assignment&id=<?php echo $assignment['id']; ?>" class="btn btn-sm">Xem</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
