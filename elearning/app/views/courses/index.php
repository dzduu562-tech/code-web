<?php
$content = '
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-book me-2"></i>Khóa học</h2>
                ' . ($this->auth->isTeacher() ? 
                    '<a href="/course/create" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tạo khóa học mới
                    </a>' : ''
                ) . '
            </div>
        </div>
    </div>
    
    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="/courses" class="row g-3">
                        <div class="col-md-6">
                            <label for="search" class="form-label">Tìm kiếm</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="' . htmlspecialchars($search_query) . '" placeholder="Tìm kiếm khóa học...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="subject" class="form-label">Môn học</label>
                            <select class="form-select" id="subject" name="subject">
                                <option value="">Tất cả môn học</option>
                                ' . implode('', array_map(function($subject) use ($current_subject) {
                                    return '<option value="' . htmlspecialchars($subject['subject']) . '"' . 
                                           ($current_subject === $subject['subject'] ? ' selected' : '') . '>' . 
                                           htmlspecialchars($subject['subject']) . '</option>';
                                }, $subjects)) . '
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Tìm kiếm
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Courses Grid -->
    <div class="row g-4">
        ' . (empty($courses) ? 
            '<div class="col-12 text-center py-5">
                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Không tìm thấy khóa học nào</h4>
                <p class="text-muted">Hãy thử tìm kiếm với từ khóa khác hoặc chọn môn học khác</p>
            </div>' :
            implode('', array_map(function($course) {
                return '
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        ' . ($course['thumbnail'] ? 
                            '<img src="' . htmlspecialchars($course['thumbnail']) . '" class="card-img-top" alt="' . htmlspecialchars($course['title']) . '" style="height: 200px; object-fit: cover;">' :
                            '<div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-book fa-3x text-muted"></i>
                            </div>'
                        ) . '
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">' . htmlspecialchars($course['title']) . '</h5>
                            <p class="card-text text-muted">' . Helpers::truncate(strip_tags($course['description']), 100) . '</p>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>' . htmlspecialchars($course['teacher_name']) . '
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-tag me-1"></i>' . htmlspecialchars($course['subject']) . '
                                    </small>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>' . Helpers::formatDate($course['created_at'], 'd/m/Y') . '
                                    </small>
                                    <span class="badge bg-success">Đã xuất bản</span>
                                </div>
                                <a href="/course?id=' . $course['id'] . '" class="btn btn-primary w-100">
                                    <i class="fas fa-eye me-2"></i>Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                </div>';
            }, $courses))
        ) . '
    </div>
    
    <!-- Pagination -->
    ' . ($pagination ? '
    <div class="row mt-5">
        <div class="col-12">
            <nav aria-label="Course pagination">
                <ul class="pagination justify-content-center">
                    ' . implode('', array_map(function($link) {
                        return '<li class="page-item' . (strpos($link['class'], 'active') !== false ? ' active' : '') . '">
                            <a class="' . $link['class'] . '" href="' . $link['url'] . '">' . $link['text'] . '</a>
                        </li>';
                    }, Helpers::generatePaginationLinks($pagination, '/courses'))) . '
                </ul>
            </nav>
        </div>
    </div>' : '') . '
</div>

<style>
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
';

include __DIR__ . '/../layouts/main.php';
?>