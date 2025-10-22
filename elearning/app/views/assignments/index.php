<div class="d-flex justify-content-between align-items-center">
  <h2>Bài tập</h2>
</div>
<table class="table table-sm align-middle">
  <thead><tr><th>Khóa học</th><th>Tiêu đề</th><th>Hạn nộp</th><th>Hành động</th></tr></thead>
  <tbody>
    <?php foreach ($assignments as $a): ?>
      <tr>
        <td><?=Helpers::e($a['course_title'])?></td>
        <td><?=Helpers::e($a['title'])?></td>
        <td><?=Helpers::e($a['due_at'])?></td>
        <td>
          <?php if ($user['role']==='student'): ?>
            <form class="d-flex gap-2" method="post" enctype="multipart/form-data" action="index.php?route=/assignments/submit">
              <input type="hidden" name="csrf" value="<?=Helpers::csrfToken()?>">
              <input type="hidden" name="assignment_id" value="<?=$a['id']?>">
              <input class="form-control form-control-sm" type="file" name="file">
              <input class="form-control form-control-sm" type="text" name="note" placeholder="Ghi chú">
              <button class="btn btn-sm btn-primary">Nộp</button>
            </form>
          <?php else: ?>
            <?php /* Teacher could link to grading list in a fuller version */ ?>
            <span class="text-muted">Quản lý chấm điểm trong phiên bản đầy đủ</span>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
