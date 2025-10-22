<div class="d-flex justify-content-between align-items-center">
  <div>
    <a class="btn btn-secondary btn-sm" href="index.php?route=/course&id=<?=$lesson['course_id']?>">← Quay lại</a>
    <h3 class="mt-2"><?=Helpers::e($lesson['title'])?></h3>
  </div>
</div>
<hr>
<div class="row g-4">
  <div class="col-lg-8">
    <article>
      <?=$lesson['content_html']?>
      <?php if (!empty($lesson['video_url'])): ?>
        <div class="ratio ratio-16x9 my-3">
          <iframe src="<?=Helpers::e($lesson['video_url'])?>" title="Video" allowfullscreen></iframe>
        </div>
      <?php endif; ?>
    </article>

    <section class="mt-4">
      <h5>Tài liệu</h5>
      <ul>
        <?php foreach ($resources as $r): ?>
          <li><a href="<?=Helpers::e($r['file_path'])?>" download><?=Helpers::e($r['file_name'])?></a></li>
        <?php endforeach; ?>
      </ul>
    </section>

    <?php if ($quiz): ?>
    <section class="mt-4">
      <h5>Trắc nghiệm: <?=Helpers::e($quiz['title'])?></h5>
      <form method="post" action="index.php?route=/quiz/submit">
        <input type="hidden" name="csrf" value="<?=Helpers::csrfToken()?>">
        <input type="hidden" name="quiz_id" value="<?=$quiz['id']?>">
        <input type="hidden" name="lesson_id" value="<?=$lesson['id']?>">
        <?php
        $pdo = DB::getConnection();
        $qs = $pdo->prepare('SELECT * FROM questions WHERE quiz_id=?');
        $qs->execute([$quiz['id']]);
        $questions = $qs->fetchAll();
        foreach ($questions as $q):
          $optsStmt = $pdo->prepare('SELECT * FROM options WHERE question_id=?');
          $optsStmt->execute([$q['id']]);
          $opts = $optsStmt->fetchAll();
        ?>
          <div class="mb-3">
            <div class="fw-semibold"><?=Helpers::e($q['text'])?></div>
            <?php foreach ($opts as $op): ?>
              <div class="form-check">
                <input class="form-check-input" required type="radio" name="answers[<?=$q['id']?>]" value="<?=$op['id']?>" id="op<?=$op['id']?>">
                <label class="form-check-label" for="op<?=$op['id']?>"><?=Helpers::e($op['text'])?></label>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
        <button class="btn btn-primary">Nộp bài</button>
      </form>
    </section>
    <?php endif; ?>

    <section class="mt-4">
      <h5>Thảo luận</h5>
      <form class="mb-3" method="post" action="index.php?route=/forum/create">
        <input type="hidden" name="csrf" value="<?=Helpers::csrfToken()?>">
        <input type="hidden" name="course_id" value="<?=$lesson['course_id']?>">
        <input type="hidden" name="lesson_id" value="<?=$lesson['id']?>">
        <div class="input-group">
          <input class="form-control" name="title" placeholder="Đặt câu hỏi...">
          <button class="btn btn-secondary">Tạo chủ đề</button>
        </div>
      </form>
    </section>
  </div>
  <div class="col-lg-4">
    <button id="markDoneBtn" data-lesson="<?=$lesson['id']?>" class="btn btn-success w-100">Đánh dấu đã học</button>
    <div class="mt-3"><strong>Tiến độ khóa học:</strong> <span id="progressText">-</span></div>
  </div>
</div>
<script>
  document.getElementById('markDoneBtn').addEventListener('click', async () => {
    const csrf = '<?=Helpers::csrfToken()?>';
    const lessonId = document.getElementById('markDoneBtn').dataset.lesson;
    const form = new FormData(); form.append('csrf', csrf); form.append('lesson_id', lessonId);
    const res = await fetch('index.php?route=/lesson/mark', { method: 'POST', body: form });
    if (res.ok) { const data = await res.json(); document.getElementById('progressText').textContent = data.progress + '%'; }
  });
</script>
