<a class="btn btn-secondary btn-sm" href="index.php?route=/teacher/courses">← Khóa học của tôi</a>
<h2 class="mt-2"><?=Helpers::e($course['title'])?></h2>
<div class="row g-4 mt-1">
  <div class="col-lg-8">
    <?php foreach ($chapters as $ch): ?>
      <div class="card mb-3">
        <div class="card-body">
          <h5>Chương <?=$ch['position']?>: <?=Helpers::e($ch['title'])?></h5>
          <ul>
            <?php foreach (($lessonsByChapter[$ch['id']] ?? []) as $ls): ?>
              <li>
                <a href="index.php?route=/lesson&id=<?=$ls['id']?>"><?=Helpers::e($ls['title'])?></a>
                <form class="d-inline" method="post" action="index.php?route=/teacher/lesson/delete">
                  <input type="hidden" name="csrf" value="<?=Helpers::csrfToken()?>">
                  <input type="hidden" name="lesson_id" value="<?=$ls['id']?>">
                  <button class="btn btn-sm btn-link text-danger">Xóa</button>
                </form>
              </li>
            <?php endforeach; ?>
          </ul>
          <form class="row g-2" method="post" action="index.php?route=/teacher/lesson/save">
            <input type="hidden" name="csrf" value="<?=Helpers::csrfToken()?>">
            <input type="hidden" name="chapter_id" value="<?=$ch['id']?>">
            <div class="col-md-4">
              <input name="title" class="form-control" placeholder="Tiêu đề bài">
            </div>
            <div class="col-md-4">
              <input name="video_url" class="form-control" placeholder="Link video (YouTube)">
            </div>
            <div class="col-md-2">
              <input name="position" class="form-control" type="number" placeholder="#" value="1">
            </div>
            <div class="col-12">
              <textarea name="content_html" class="form-control" rows="3" placeholder="Nội dung HTML cơ bản"></textarea>
            </div>
            <div class="col-12">
              <button class="btn btn-sm btn-primary">+ Thêm bài</button>
            </div>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="col-lg-4">
    <div class="card">
      <div class="card-body">
        <h5>Thêm chương</h5>
        <form class="row g-2" method="post" action="index.php?route=/teacher/chapter/save">
          <input type="hidden" name="csrf" value="<?=Helpers::csrfToken()?>">
          <input type="hidden" name="course_id" value="<?=$course['id']?>">
          <div class="col-8">
            <input class="form-control" name="title" placeholder="Tiêu đề chương">
          </div>
          <div class="col-4">
            <input class="form-control" name="position" type="number" value="1">
          </div>
          <div class="col-12">
            <button class="btn btn-sm btn-secondary">+ Thêm chương</button>
          </div>
        </form>
      </div>
    </div>
    <div class="card mt-3">
      <div class="card-body">
        <h5>Tải tài liệu</h5>
        <form class="row g-2" method="post" enctype="multipart/form-data" action="index.php?route=/teacher/resource/upload">
          <input type="hidden" name="csrf" value="<?=Helpers::csrfToken()?>">
          <input type="hidden" name="course_id" value="<?=$course['id']?>">
          <div class="col-12">
            <select class="form-select" name="lesson_id" required>
              <option value="">Chọn bài học</option>
              <?php foreach ($chapters as $ch): foreach (($lessonsByChapter[$ch['id']] ?? []) as $ls): ?>
                <option value="<?=$ls['id']?>">Chương <?=$ch['position']?> - <?=$ls['title']?></option>
              <?php endforeach; endforeach; ?>
            </select>
          </div>
          <div class="col-12">
            <input class="form-control" type="file" name="file" required>
          </div>
          <div class="col-12">
            <button class="btn btn-sm btn-secondary">Tải lên</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
