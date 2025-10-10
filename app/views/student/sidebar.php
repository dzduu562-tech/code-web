<div class="sidebar bg-white rounded shadow-sm p-3 sticky-top" style="top: 80px;">
    <div class="list-group list-group-flush">
        <a href="<?= BASE_URL ?>/student/dashboard" 
           class="list-group-item list-group-item-action border-0 rounded mb-1 <?= (strpos($_SERVER['REQUEST_URI'], '/student/dashboard') !== false) ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        
        <a href="<?= BASE_URL ?>/student/my-courses" 
           class="list-group-item list-group-item-action border-0 rounded mb-1 <?= (strpos($_SERVER['REQUEST_URI'], '/student/my-courses') !== false || strpos($_SERVER['REQUEST_URI'], '/student/course') !== false) ? 'active' : '' ?>">
            <i class="bi bi-collection"></i> Khóa học của tôi
        </a>
        
        <a href="<?= BASE_URL ?>/courses" 
           class="list-group-item list-group-item-action border-0 rounded mb-1">
            <i class="bi bi-search"></i> Tìm khóa học
        </a>
        
        <a href="<?= BASE_URL ?>/student/progress" 
           class="list-group-item list-group-item-action border-0 rounded mb-1 <?= (strpos($_SERVER['REQUEST_URI'], '/student/progress') !== false) ? 'active' : '' ?>">
            <i class="bi bi-graph-up"></i> Tiến độ học tập
        </a>
        
        <a href="<?= BASE_URL ?>/student/assignments" 
           class="list-group-item list-group-item-action border-0 rounded mb-1">
            <i class="bi bi-file-earmark-text"></i> Bài tập
        </a>
        
        <a href="<?= BASE_URL ?>/student/quizzes" 
           class="list-group-item list-group-item-action border-0 rounded mb-1">
            <i class="bi bi-clipboard-check"></i> Kiểm tra
        </a>
        
        <a href="<?= BASE_URL ?>/student/badges" 
           class="list-group-item list-group-item-action border-0 rounded mb-1 <?= (strpos($_SERVER['REQUEST_URI'], '/student/badges') !== false) ? 'active' : '' ?>">
            <i class="bi bi-award"></i> Huy hiệu
        </a>
        
        <a href="<?= BASE_URL ?>/student/calendar" 
           class="list-group-item list-group-item-action border-0 rounded mb-1">
            <i class="bi bi-calendar"></i> Lịch học
        </a>
        
        <hr class="my-2">
        
        <a href="<?= BASE_URL ?>/profile" 
           class="list-group-item list-group-item-action border-0 rounded mb-1">
            <i class="bi bi-person"></i> Hồ sơ
        </a>
        
        <a href="<?= BASE_URL ?>/settings" 
           class="list-group-item list-group-item-action border-0 rounded mb-1">
            <i class="bi bi-gear"></i> Cài đặt
        </a>
    </div>
</div>

<style>
.list-group-item {
    transition: all 0.3s;
}

.list-group-item.active {
    background-color: var(--bs-primary);
    color: white;
}

.list-group-item:hover:not(.active) {
    background-color: var(--bs-gray-100);
}
</style>
