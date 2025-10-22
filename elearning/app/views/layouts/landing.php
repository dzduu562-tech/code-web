<?php Auth::startSecureSession(); $base='index.php'; ?>
<section class="py-5 text-center bg-body-tertiary rounded-3">
  <div class="container">
    <h1 class="display-5 fw-bold">Nền tảng E-Learning nhẹ</h1>
    <p class="lead">Đơn giản • Hiện đại • Dễ dùng • Chạy tốt trên XAMPP</p>
    <a class="btn btn-primary btn-lg" href="<?=$base?>?route=/register">Bắt đầu học</a>
  </div>
</section>
<section class="container py-5">
  <div class="row text-center g-4">
    <div class="col">
      <div class="card p-3"><div class="fs-2">🎓</div><div><strong id="statStudents">0</strong> Học sinh</div></div>
    </div>
    <div class="col">
      <div class="card p-3"><div class="fs-2">👨‍🏫</div><div><strong id="statTeachers">0</strong> Giáo viên</div></div>
    </div>
    <div class="col">
      <div class="card p-3"><div class="fs-2">📚</div><div><strong id="statCourses">0</strong> Khóa học</div></div>
    </div>
  </div>
</section>
<script>
(async function(){
  try{const r=await fetch('index.php?route=/stats'); if(r.ok){const d=await r.json();
    document.getElementById('statStudents').textContent=d.students;
    document.getElementById('statTeachers').textContent=d.teachers;
    document.getElementById('statCourses').textContent=d.courses;
  }}catch(e){}
})();
</script>
