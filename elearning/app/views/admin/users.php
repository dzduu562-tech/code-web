<h2>Người dùng</h2>
<table class="table table-sm">
  <thead><tr><th>ID</th><th>Tên</th><th>Email</th><th>Vai trò</th><th>Tạo lúc</th></tr></thead>
  <tbody>
    <?php foreach ($users as $u): ?>
      <tr>
        <td><?=$u['id']?></td>
        <td><?=Helpers::e($u['name'])?></td>
        <td><?=Helpers::e($u['email'])?></td>
        <td><?=Helpers::e($u['role'])?></td>
        <td><?=Helpers::e($u['created_at'])?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
