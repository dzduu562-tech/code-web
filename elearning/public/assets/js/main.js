/**
 * E-Learning Platform - Main JavaScript
 * Handles theme switching, AJAX requests, notifications, and UI interactions
 */

// ===== Theme Management =====
class ThemeManager {
    constructor() {
        this.init();
    }

    init() {
        // Load saved theme or use system preference
        const savedTheme = localStorage.getItem('theme');
        const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        const theme = savedTheme || systemTheme;
        
        this.setTheme(theme);
        this.setupToggleButton();
        this.watchSystemTheme();
    }

    setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        this.updateToggleIcon(theme);
    }

    updateToggleIcon(theme) {
        const icon = document.getElementById('theme-icon');
        if (icon) {
            icon.className = theme === 'dark' ? 'bi bi-moon-fill' : 'bi bi-sun-fill';
        }
    }

    setupToggleButton() {
        const toggleBtn = document.getElementById('theme-toggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                const currentTheme = document.documentElement.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                this.setTheme(newTheme);
            });
        }
    }

    watchSystemTheme() {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('theme')) {
                this.setTheme(e.matches ? 'dark' : 'light');
            }
        });
    }
}

// ===== AJAX Utility =====
class AjaxManager {
    static async request(url, options = {}) {
        const defaultOptions = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        };

        const config = { ...defaultOptions, ...options };

        try {
            const response = await fetch(url, config);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return await response.json();
            } else {
                return await response.text();
            }
        } catch (error) {
            console.error('AJAX request failed:', error);
            throw error;
        }
    }

    static async get(url) {
        return this.request(url);
    }

    static async post(url, data) {
        return this.request(url, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    static async postForm(url, formData) {
        return this.request(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
    }
}

// ===== Notification Manager =====
class NotificationManager {
    constructor() {
        this.container = document.getElementById('notification-list');
        this.badge = document.getElementById('notification-badge');
        this.init();
    }

    init() {
        if (this.container) {
            this.loadNotifications();
            // Auto-refresh every 30 seconds
            setInterval(() => this.loadNotifications(), 30000);
        }
    }

    async loadNotifications() {
        try {
            const data = await AjaxManager.get('/elearning/public/index.php?route=api/notifications&limit=5');
            this.updateNotificationList(data.notifications);
            this.updateBadge(data.unread_count);
        } catch (error) {
            console.error('Failed to load notifications:', error);
        }
    }

    updateNotificationList(notifications) {
        if (!this.container) return;

        if (notifications.length === 0) {
            this.container.innerHTML = '<li><span class="dropdown-item-text text-muted">Không có thông báo mới</span></li>';
            return;
        }

        this.container.innerHTML = notifications.map(notification => `
            <li>
                <div class="notification-item ${notification.is_read ? '' : 'unread'}" data-id="${notification.id}">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${this.escapeHtml(notification.title)}</h6>
                            <p class="mb-1 small text-muted">${this.escapeHtml(notification.message)}</p>
                            <small class="text-muted">${this.timeAgo(notification.created_at)}</small>
                        </div>
                        ${!notification.is_read ? '<div class="ms-2"><span class="badge bg-primary rounded-pill">Mới</span></div>' : ''}
                    </div>
                </div>
            </li>
        `).join('');

        // Add click handlers to mark as read
        this.container.querySelectorAll('.notification-item.unread').forEach(item => {
            item.addEventListener('click', () => {
                this.markAsRead(item.dataset.id);
            });
        });
    }

    updateBadge(count) {
        if (!this.badge) return;

        if (count > 0) {
            this.badge.textContent = count > 99 ? '99+' : count;
            this.badge.style.display = 'block';
        } else {
            this.badge.style.display = 'none';
        }
    }

    async markAsRead(notificationId) {
        try {
            const formData = new FormData();
            formData.append('action', 'mark_read');
            formData.append('notification_id', notificationId);

            await AjaxManager.postForm('/elearning/public/index.php?route=notifications', formData);
            this.loadNotifications(); // Refresh notifications
        } catch (error) {
            console.error('Failed to mark notification as read:', error);
        }
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    timeAgo(dateString) {
        const now = new Date();
        const date = new Date(dateString);
        const diffInSeconds = Math.floor((now - date) / 1000);

        if (diffInSeconds < 60) return 'Vừa xong';
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} phút trước`;
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} giờ trước`;
        if (diffInSeconds < 2592000) return `${Math.floor(diffInSeconds / 86400)} ngày trước`;
        if (diffInSeconds < 31104000) return `${Math.floor(diffInSeconds / 2592000)} tháng trước`;
        return `${Math.floor(diffInSeconds / 31104000)} năm trước`;
    }
}

// ===== Form Enhancement =====
class FormManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupFormValidation();
        this.setupAjaxForms();
        this.setupFileUploads();
    }

    setupFormValidation() {
        // Add Bootstrap validation classes
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });

        // Real-time validation for specific inputs
        document.querySelectorAll('input[type="email"]').forEach(input => {
            input.addEventListener('blur', () => {
                this.validateEmail(input);
            });
        });

        document.querySelectorAll('input[type="password"]').forEach(input => {
            input.addEventListener('input', () => {
                this.validatePassword(input);
            });
        });
    }

    validateEmail(input) {
        const email = input.value.trim();
        const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        
        if (email && !isValid) {
            input.classList.add('is-invalid');
            this.showFieldError(input, 'Email không hợp lệ');
        } else {
            input.classList.remove('is-invalid');
            this.hideFieldError(input);
        }
    }

    validatePassword(input) {
        const password = input.value;
        const minLength = 6;
        
        if (password && password.length < minLength) {
            input.classList.add('is-invalid');
            this.showFieldError(input, `Mật khẩu phải có ít nhất ${minLength} ký tự`);
        } else {
            input.classList.remove('is-invalid');
            this.hideFieldError(input);
        }
    }

    showFieldError(input, message) {
        let errorDiv = input.parentNode.querySelector('.invalid-feedback');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            input.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
    }

    hideFieldError(input) {
        const errorDiv = input.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    setupAjaxForms() {
        document.querySelectorAll('form[data-ajax="true"]').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');
                
                // Disable submit button and show loading
                if (submitBtn) {
                    submitBtn.disabled = true;
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';
                    
                    try {
                        const response = await AjaxManager.postForm(form.action, formData);
                        this.handleAjaxResponse(response, form);
                    } catch (error) {
                        this.showAlert('Có lỗi xảy ra. Vui lòng thử lại.', 'danger');
                    } finally {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                }
            });
        });
    }

    handleAjaxResponse(response, form) {
        if (typeof response === 'string') {
            try {
                response = JSON.parse(response);
            } catch (e) {
                this.showAlert('Phản hồi không hợp lệ từ máy chủ', 'danger');
                return;
            }
        }

        if (response.success) {
            this.showAlert(response.message || 'Thành công!', 'success');
            if (response.redirect) {
                setTimeout(() => {
                    window.location.href = response.redirect;
                }, 1500);
            }
        } else {
            this.showAlert(response.message || 'Có lỗi xảy ra', 'danger');
        }
    }

    setupFileUploads() {
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', (e) => {
                this.validateFileUpload(e.target);
            });
        });
    }

    validateFileUpload(input) {
        const files = input.files;
        const maxSize = 50 * 1024 * 1024; // 50MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 
                             'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

        for (let file of files) {
            if (file.size > maxSize) {
                this.showAlert('File quá lớn. Kích thước tối đa là 50MB.', 'warning');
                input.value = '';
                return;
            }

            if (!allowedTypes.includes(file.type)) {
                this.showAlert('Loại file không được hỗ trợ.', 'warning');
                input.value = '';
                return;
            }
        }
    }

    showAlert(message, type = 'info') {
        const alertContainer = document.getElementById('alert-container') || this.createAlertContainer();
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        alertContainer.appendChild(alertDiv);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    createAlertContainer() {
        const container = document.createElement('div');
        container.id = 'alert-container';
        container.className = 'position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
        return container;
    }
}

// ===== UI Enhancements =====
class UIManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupTooltips();
        this.setupPopovers();
        this.setupSmoothScrolling();
        this.setupBackToTop();
        this.setupSearchEnhancements();
        this.setupProgressBars();
    }

    setupTooltips() {
        // Initialize Bootstrap tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(tooltipTriggerEl => {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    setupPopovers() {
        // Initialize Bootstrap popovers
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(popoverTriggerEl => {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    }

    setupSmoothScrolling() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    setupBackToTop() {
        const backToTopBtn = this.createBackToTopButton();
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopBtn.style.display = 'block';
            } else {
                backToTopBtn.style.display = 'none';
            }
        });

        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    createBackToTopButton() {
        const btn = document.createElement('button');
        btn.innerHTML = '<i class="bi bi-arrow-up"></i>';
        btn.className = 'btn btn-primary rounded-circle position-fixed';
        btn.style.cssText = `
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            display: none;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        `;
        btn.setAttribute('title', 'Về đầu trang');
        document.body.appendChild(btn);
        return btn;
    }

    setupSearchEnhancements() {
        const searchInputs = document.querySelectorAll('input[type="search"]');
        
        searchInputs.forEach(input => {
            let searchTimeout;
            
            input.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.handleSearch(e.target);
                }, 300);
            });
        });
    }

    handleSearch(input) {
        const query = input.value.trim();
        if (query.length >= 2) {
            // Implement live search if needed
            console.log('Searching for:', query);
        }
    }

    setupProgressBars() {
        // Animate progress bars when they come into view
        const progressBars = document.querySelectorAll('.progress-bar');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const progressBar = entry.target;
                    const width = progressBar.getAttribute('data-width') || progressBar.style.width;
                    progressBar.style.width = '0%';
                    setTimeout(() => {
                        progressBar.style.width = width;
                    }, 100);
                }
            });
        });

        progressBars.forEach(bar => {
            observer.observe(bar);
        });
    }
}

// ===== Course Interactions =====
class CourseManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupEnrollmentButtons();
        this.setupLessonCompletion();
        this.setupQuizInteractions();
    }

    setupEnrollmentButtons() {
        document.querySelectorAll('.btn-enroll').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                
                const courseId = btn.dataset.courseId;
                if (!courseId) return;

                btn.disabled = true;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';

                try {
                    const formData = new FormData();
                    formData.append('course_id', courseId);

                    const response = await AjaxManager.postForm('/elearning/public/index.php?route=course/enroll', formData);
                    
                    if (response.success) {
                        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Đã đăng ký';
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-success');
                        
                        // Show success message
                        new FormManager().showAlert(response.message, 'success');
                        
                        // Reload page after 2 seconds
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        new FormManager().showAlert(response.message, 'danger');
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }
                } catch (error) {
                    new FormManager().showAlert('Có lỗi xảy ra khi đăng ký khóa học', 'danger');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            });
        });
    }

    setupLessonCompletion() {
        document.querySelectorAll('.btn-complete-lesson').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                
                const lessonId = btn.dataset.lessonId;
                if (!lessonId) return;

                try {
                    const formData = new FormData();
                    formData.append('lesson_id', lessonId);

                    const response = await AjaxManager.postForm('/elearning/public/index.php?route=lesson/complete', formData);
                    
                    if (response.success) {
                        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Đã hoàn thành';
                        btn.classList.remove('btn-outline-success');
                        btn.classList.add('btn-success');
                        btn.disabled = true;
                        
                        // Update progress bar if exists
                        this.updateProgress(response.progress);
                    }
                } catch (error) {
                    console.error('Failed to mark lesson as complete:', error);
                }
            });
        });
    }

    setupQuizInteractions() {
        // Quiz timer
        const quizTimer = document.getElementById('quiz-timer');
        if (quizTimer) {
            const timeLimit = parseInt(quizTimer.dataset.timeLimit) * 60; // Convert to seconds
            this.startQuizTimer(quizTimer, timeLimit);
        }

        // Auto-save quiz answers
        document.querySelectorAll('input[name^="answers"]').forEach(input => {
            input.addEventListener('change', () => {
                this.autoSaveQuizAnswer(input);
            });
        });
    }

    startQuizTimer(timerElement, timeLimit) {
        let timeLeft = timeLimit;
        
        const timer = setInterval(() => {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 300) { // 5 minutes warning
                timerElement.classList.add('text-warning');
            }
            
            if (timeLeft <= 60) { // 1 minute warning
                timerElement.classList.remove('text-warning');
                timerElement.classList.add('text-danger');
            }
            
            if (timeLeft <= 0) {
                clearInterval(timer);
                this.autoSubmitQuiz();
            }
            
            timeLeft--;
        }, 1000);
    }

    autoSaveQuizAnswer(input) {
        // Save answer to localStorage for recovery
        const quizId = input.closest('form').dataset.quizId;
        const answers = JSON.parse(localStorage.getItem(`quiz_${quizId}_answers`) || '{}');
        
        if (input.type === 'radio' || input.type === 'checkbox') {
            if (input.checked) {
                if (input.type === 'radio') {
                    answers[input.name] = input.value;
                } else {
                    if (!answers[input.name]) answers[input.name] = [];
                    if (!answers[input.name].includes(input.value)) {
                        answers[input.name].push(input.value);
                    }
                }
            } else if (input.type === 'checkbox') {
                if (answers[input.name]) {
                    answers[input.name] = answers[input.name].filter(val => val !== input.value);
                }
            }
        }
        
        localStorage.setItem(`quiz_${quizId}_answers`, JSON.stringify(answers));
    }

    autoSubmitQuiz() {
        const quizForm = document.querySelector('form[data-quiz-id]');
        if (quizForm) {
            new FormManager().showAlert('Hết thời gian! Bài làm sẽ được tự động nộp.', 'warning');
            setTimeout(() => {
                quizForm.submit();
            }, 2000);
        }
    }

    updateProgress(progress) {
        const progressBar = document.querySelector('.course-progress .progress-bar');
        if (progressBar) {
            progressBar.style.width = `${progress}%`;
            progressBar.textContent = `${progress}%`;
        }
    }
}

// ===== Global Functions =====
window.loadNotifications = function() {
    if (window.notificationManager) {
        window.notificationManager.loadNotifications();
    }
};

// ===== Initialize Everything =====
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all managers
    window.themeManager = new ThemeManager();
    window.formManager = new FormManager();
    window.uiManager = new UIManager();
    window.courseManager = new CourseManager();
    
    // Initialize notification manager only for logged-in users
    if (document.getElementById('notification-list')) {
        window.notificationManager = new NotificationManager();
    }
    
    console.log('E-Learning Platform initialized successfully');
});

// ===== Export for global access =====
window.ELearning = {
    ThemeManager,
    AjaxManager,
    NotificationManager,
    FormManager,
    UIManager,
    CourseManager
};