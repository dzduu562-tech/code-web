<h2>Quản trị</h2>
<div class="row g-3 mb-3">
  <div class="col-md-3"><div class="card p-3">Người dùng: <strong><?=$stats['users']?></strong></div></div>
  <div class="col-md-3"><div class="card p-3">Khóa học: <strong><?=$stats['courses']?></strong></div></div>
  <div class="col-md-3"><div class="card p-3">Chủ đề: <strong><?=$stats['threads']?></strong></div></div>
</div>
<h5>Người dùng mới</h5>
<table class="table table-sm"><thead><tr><th>Email</th><th>Vai trò</th><th>Tạo lúc</th></tr></thead>
<tbody>
<?php foreach ($recent as $r): ?>
<tr><td><?=Helpers::e($r['email'])?></td><td><?=Helpers::e($r['role'])?></td><td><?=Helpers::e($r['created_at'])?></td></tr>
<?php endforeach; ?>
</tbody></table>
