// E-Learning Platform - Main JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Password confirmation validation
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    
    if (password && confirmPassword) {
        function validatePassword() {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Mật khẩu xác nhận không khớp');
            } else {
                confirmPassword.setCustomValidity('');
            }
        }
        
        password.addEventListener('change', validatePassword);
        confirmPassword.addEventListener('keyup', validatePassword);
    }

    // File upload preview
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('image-preview');
                    if (preview) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Mark lesson as completed
    const markCompletedBtn = document.getElementById('mark-completed-btn');
    if (markCompletedBtn) {
        markCompletedBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const lessonId = this.dataset.lessonId;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            if (!lessonId) return;
            
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';
            
            fetch('/lesson/mark-completed', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `lesson_id=${lessonId}&csrf_token=${csrfToken}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.innerHTML = '<i class="fas fa-check me-2"></i>Đã hoàn thành';
                    this.classList.remove('btn-primary');
                    this.classList.add('btn-success');
                    
                    // Update progress bar if exists
                    const progressBar = document.querySelector('.progress-bar');
                    if (progressBar && data.progress) {
                        progressBar.style.width = data.progress + '%';
                        progressBar.textContent = data.progress + '%';
                    }
                } else {
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-check me-2"></i>Đánh dấu hoàn thành';
                    alert('Có lỗi xảy ra: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-check me-2"></i>Đánh dấu hoàn thành';
                alert('Có lỗi xảy ra khi đánh dấu hoàn thành');
            });
        });
    }

    // Quiz functionality
    const quizForm = document.getElementById('quiz-form');
    if (quizForm) {
        const options = document.querySelectorAll('.quiz-option');
        options.forEach(option => {
            option.addEventListener('click', function() {
                const questionId = this.dataset.questionId;
                const optionId = this.dataset.optionId;
                
                // Remove previous selection for this question
                const questionOptions = document.querySelectorAll(`[data-question-id="${questionId}"]`);
                questionOptions.forEach(opt => opt.classList.remove('selected'));
                
                // Add selection to clicked option
                this.classList.add('selected');
                
                // Update hidden input
                const hiddenInput = document.querySelector(`input[name="answers[${questionId}]"]`);
                if (hiddenInput) {
                    hiddenInput.value = optionId;
                }
            });
        });

        // Quiz timer
        const timeLimit = parseInt(document.getElementById('time-limit')?.textContent);
        if (timeLimit && timeLimit > 0) {
            let timeLeft = timeLimit * 60; // Convert to seconds
            const timerElement = document.getElementById('quiz-timer');
            
            const timer = setInterval(() => {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                
                if (timerElement) {
                    timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                }
                
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    alert('Hết thời gian làm bài!');
                    quizForm.submit();
                }
                
                timeLeft--;
            }, 1000);
        }
    }

    // Forum thread creation
    const forumForm = document.getElementById('forum-form');
    if (forumForm) {
        const contentTextarea = document.getElementById('content');
        if (contentTextarea) {
            // Simple markdown-like formatting
            contentTextarea.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    e.preventDefault();
                    const start = this.selectionStart;
                    const end = this.selectionEnd;
                    const value = this.value;
                    this.value = value.substring(0, start) + '    ' + value.substring(end);
                    this.selectionStart = this.selectionEnd = start + 4;
                }
            });
        }
    }

    // Assignment submission
    const assignmentForm = document.getElementById('assignment-form');
    if (assignmentForm) {
        const fileInput = document.getElementById('file');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const fileSize = file.size / 1024 / 1024; // Convert to MB
                    const maxSize = 50; // 50MB limit
                    
                    if (fileSize > maxSize) {
                        alert(`File quá lớn! Kích thước tối đa là ${maxSize}MB.`);
                        this.value = '';
                        return;
                    }
                    
                    const fileName = document.getElementById('file-name');
                    if (fileName) {
                        fileName.textContent = file.name;
                        fileName.style.display = 'block';
                    }
                }
            });
        }
    }

    // Search functionality
    const searchInput = document.getElementById('search');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Auto-submit search form after 500ms of no typing
                const form = this.closest('form');
                if (form) {
                    form.submit();
                }
            }, 500);
        });
    }

    // Notification management
    const notificationLinks = document.querySelectorAll('.notification-item a');
    notificationLinks.forEach(link => {
        link.addEventListener('click', function() {
            const notificationId = this.dataset.notificationId;
            if (notificationId) {
                markNotificationAsRead(notificationId);
            }
        });
    });

    // Mark all notifications as read
    const markAllReadBtn = document.getElementById('mark-all-read');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `csrf_token=${csrfToken}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI
                    const unreadNotifications = document.querySelectorAll('.notification-item:not(.read)');
                    unreadNotifications.forEach(notification => {
                        notification.classList.add('read');
                        notification.classList.remove('fw-bold');
                    });
                    
                    // Update badge
                    const badge = document.getElementById('notification-badge');
                    if (badge) {
                        badge.style.display = 'none';
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    // Course enrollment
    const enrollBtn = document.getElementById('enroll-btn');
    if (enrollBtn) {
        enrollBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const courseId = this.dataset.courseId;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            if (!courseId) return;
            
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang đăng ký...';
            
            fetch('/course/enroll', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `course_id=${courseId}&csrf_token=${csrfToken}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.innerHTML = '<i class="fas fa-check me-2"></i>Đã đăng ký';
                    this.classList.remove('btn-primary');
                    this.classList.add('btn-success');
                    this.disabled = true;
                } else {
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-user-plus me-2"></i>Đăng ký khóa học';
                    alert('Có lỗi xảy ra: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-user-plus me-2"></i>Đăng ký khóa học';
                alert('Có lỗi xảy ra khi đăng ký khóa học');
            });
        });
    }

    // Dark mode toggle
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
        });

        // Load saved dark mode preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
        }
    }

    // Smooth scrolling for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
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

    // Lazy loading for images
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));
});

// Utility functions
function markNotificationAsRead(notificationId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/notifications/mark-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `notification_id=${notificationId}&csrf_token=${csrfToken}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI
            const notification = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notification) {
                notification.classList.add('read');
                notification.classList.remove('fw-bold');
            }
        }
    })
    .catch(error => console.error('Error:', error));
}

function showLoading(element) {
    element.disabled = true;
    element.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';
}

function hideLoading(element, originalText) {
    element.disabled = false;
    element.innerHTML = originalText;
}

function showAlert(message, type = 'info') {
    const alertContainer = document.getElementById('alert-container') || document.body;
    const alertId = 'alert-' + Date.now();
    
    const alertHtml = `
        <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    alertContainer.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        const alert = document.getElementById(alertId);
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 5000);
}

// AJAX helper function
function ajaxRequest(url, options = {}) {
    const defaultOptions = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };
    
    const mergedOptions = { ...defaultOptions, ...options };
    
    return fetch(url, mergedOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        });
}

// Form helper functions
function serializeForm(form) {
    const formData = new FormData(form);
    const params = new URLSearchParams();
    
    for (const [key, value] of formData.entries()) {
        params.append(key, value);
    }
    
    return params.toString();
}

function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    return isValid;
}

// Export functions for global use
window.ElearningApp = {
    markNotificationAsRead,
    showLoading,
    hideLoading,
    showAlert,
    ajaxRequest,
    serializeForm,
    validateForm
};