<?php $role = $role ?? 'student'; ?>
<h2 class="mb-3">Bảng điều khiển</h2>
<?php if ($role === 'teacher'): ?>
  <h5>Khóa học của tôi</h5>
  <ul class="list-group mb-3">
    <?php foreach ($items as $c): ?>
      <li class="list-group-item"><a href="<?= Core\Helpers::baseUrl($this->config); ?>/index.php?route=/course&id=<?= (int)$c['id'] ?>"><?= Core\Helpers::e($c['title']) ?></a></li>
    <?php endforeach; ?>
  </ul>
<?php elseif ($role === 'admin'): ?>
  <h5>Người dùng mới</h5>
  <ul class="list-group mb-3">
    <?php foreach ($items as $u): ?>
      <li class="list-group-item"><?= Core\Helpers::e($u['name']) ?> (<?= Core\Helpers::e($u['email']) ?>) - <?= Core\Helpers::e($u['role']) ?></li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <h5>Khóa học đã đăng ký</h5>
  <ul class="list-group mb-3">
    <?php foreach ($items as $c): ?>
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <a href="<?= Core\Helpers::baseUrl($this->config); ?>/index.php?route=/course&id=<?= (int)$c['id'] ?>"><?= Core\Helpers::e($c['title']) ?></a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
