<h2>Bảng tin</h2>
<?php if ($user['role']==='admin'): ?>
  <div class="row g-3">
    <div class="col-md-3"><div class="card p-3">Người dùng: <strong><?=$stats['users']?></strong></div></div>
    <div class="col-md-3"><div class="card p-3">Khóa học: <strong><?=$stats['courses']?></strong></div></div>
    <div class="col-md-3"><div class="card p-3">Học sinh: <strong><?=$stats['students']?></strong></div></div>
  </div>
<?php elseif ($user['role']==='teacher'): ?>
  <p>Khóa học gần đây:</p>
  <ul>
    <?php foreach (($courses ?? []) as $c): ?>
      <li><a href="index.php?route=/course&id=<?=$c['id']?>"><?=$c['title']?></a></li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p>Khóa học đã ghi danh:</p>
  <ul>
    <?php foreach (($courses ?? []) as $c): ?>
      <li><a href="index.php?route=/course&id=<?=$c['id']?>"><?=$c['title']?></a></li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
