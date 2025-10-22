<?php $base = Core\Helpers::baseUrl($this->config); ?>
<h3><?= Core\Helpers::e($assignment['title']) ?> — <?= Core\Helpers::e($assignment['course_title']) ?></h3>
<table class="table table-sm">
  <thead><tr><th>Học sinh</th><th>Ghi chú</th><th>Tệp</th><th>Điểm</th><th>Chấm</th></tr></thead>
  <tbody>
  <?php foreach ($submissions as $s): ?>
    <tr>
      <td><?= Core\Helpers::e($s['student_name']) ?></td>
      <td><?= Core\Helpers::e($s['note']) ?></td>
      <td><?php if ($s['file_path']): ?><a href="<?= $base ?>/uploads/submissions/<?= Core\Helpers::e($s['file_path']) ?>">Tải</a><?php endif; ?></td>
      <td><?= Core\Helpers::e($s['score']) ?></td>
      <td>
        <form method="post" action="<?= $base ?>/index.php?route=/assignment/grade" class="d-flex gap-2">
          <input type="hidden" name="csrf" value="<?= Core\Helpers::csrfToken() ?>">
          <input type="hidden" name="submission_id" value="<?= (int)$s['id'] ?>">
          <input class="form-control form-control-sm" type="number" step="0.01" name="score" value="<?= Core\Helpers::e($s['score']) ?>">
          <button class="btn btn-sm btn-primary">Lưu</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
