<h3>Bài nộp của tôi</h3>
<table class="table table-striped">
  <thead><tr><th>Bài tập</th><th>Ghi chú</th><th>Điểm</th><th>Thời gian</th></tr></thead>
  <tbody>
  <?php foreach ($submissions as $s): ?>
    <tr>
      <td><?= Core\Helpers::e($s['assignment_title']) ?></td>
      <td><?= Core\Helpers::e($s['note']) ?></td>
      <td><?= Core\Helpers::e($s['score']) ?></td>
      <td><?= Core\Helpers::e($s['created_at']) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
