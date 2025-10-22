<?php $base = Core\Helpers::baseUrl($this->config); ?>
<h2>Bài tập</h2>
<table class="table table-striped align-middle">
  <thead><tr><th>Khóa học</th><th>Tiêu đề</th><th>Hạn nộp</th><th>Hành động</th></tr></thead>
  <tbody>
  <?php foreach ($assignments as $a): ?>
    <tr>
      <td><?= Core\Helpers::e($a['course_title']) ?></td>
      <td><?= Core\Helpers::e($a['title']) ?></td>
      <td><?= Core\Helpers::e($a['due_at']) ?></td>
      <td>
        <?php if (($role ?? '')==='student'): ?>
          <form method="post" action="<?= $base ?>/index.php?route=/assignment/submit" enctype="multipart/form-data" class="d-flex gap-2">
            <input type="hidden" name="csrf" value="<?= Core\Helpers::csrfToken() ?>">
            <input type="hidden" name="assignment_id" value="<?= (int)$a['id'] ?>">
            <input class="form-control form-control-sm" type="file" name="file" aria-label="Tệp nộp">
            <input class="form-control form-control-sm" type="text" name="note" placeholder="Ghi chú">
            <button class="btn btn-sm btn-primary">Nộp</button>
          </form>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
