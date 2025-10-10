    <!-- Footer -->
    <footer class="bg-white border-top py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><?= SITE_NAME ?></h5>
                    <p class="text-muted">Hệ thống học tập trực tuyến hiện đại</p>
                    <p class="text-muted small">© <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved.</p>
                </div>
                <div class="col-md-3">
                    <h6>Liên kết</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?= BASE_URL ?>/home" class="text-decoration-none">Trang chủ</a></li>
                        <li><a href="<?= BASE_URL ?>/courses" class="text-decoration-none">Khóa học</a></li>
                        <li><a href="<?= BASE_URL ?>/about" class="text-decoration-none">Giới thiệu</a></li>
                        <li><a href="<?= BASE_URL ?>/contact" class="text-decoration-none">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Hỗ trợ</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-decoration-none">Hướng dẫn sử dụng</a></li>
                        <li><a href="#" class="text-decoration-none">Câu hỏi thường gặp</a></li>
                        <li><a href="#" class="text-decoration-none">Chính sách</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (optional) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= ASSETS_URL ?>/js/main.js"></script>
    
    <!-- Theme Toggle Script -->
    <script>
        // Load saved theme preference
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        
        // Theme toggle function
        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // Update icon
            const icon = document.querySelector('#theme-toggle i');
            if (icon) {
                icon.className = newTheme === 'light' ? 'bi bi-moon-fill' : 'bi bi-sun-fill';
            }
        }
        
        // Initialize theme toggle button
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('theme-toggle');
            if (themeToggle) {
                const icon = themeToggle.querySelector('i');
                if (icon) {
                    icon.className = savedTheme === 'light' ? 'bi bi-moon-fill' : 'bi bi-sun-fill';
                }
                themeToggle.addEventListener('click', toggleTheme);
            }
        });
    </script>
</body>
</html>
