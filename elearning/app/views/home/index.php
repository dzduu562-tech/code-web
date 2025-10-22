<div class="p-5 mb-4 bg-light rounded-3">
  <div class="container-fluid py-5">
    <h1 class="display-5 fw-bold">Nền tảng E-Learning nhẹ</h1>
    <p class="col-md-8 fs-5">Học trực tuyến đơn giản, hiện đại. Phù hợp XAMPP/Windows, dễ triển khai tại nhà trường.</p>
    <a class="btn btn-primary btn-lg" href="<?= Core\Helpers::baseUrl($this->config); ?>/index.php?route=/courses">Bắt đầu học</a>
  </div>
</div>
<div class="row text-center g-3">
  <div class="col"><div class="card p-3"><div class="h2">👩‍🎓 <?= (int)$stats['students'] ?></div><div>Học sinh</div></div></div>
  <div class="col"><div class="card p-3"><div class="h2">👨‍🏫 <?= (int)$stats['teachers'] ?></div><div>Giáo viên</div></div></div>
  <div class="col"><div class="card p-3"><div class="h2">📚 <?= (int)$stats['courses'] ?></div><div>Khóa học</div></div></div>
</div>
