/**
 * E-Learning Platform - Main JavaScript
 * Enhanced user experience with modern features
 */

(function() {
    'use strict';

    // ========================================
    // DOM READY
    // ========================================
    document.addEventListener('DOMContentLoaded', function() {
        initializeApp();
    });

    // ========================================
    // INITIALIZE APP
    // ========================================
    function initializeApp() {
        initTooltips();
        initPopovers();
        initFormValidation();
        initAjaxForms();
        autoHideAlerts();
        initPasswordToggle();
        initFileUpload();
        loadNotifications();
    }

    // ========================================
    // BOOTSTRAP TOOLTIPS
    // ========================================
    function initTooltips() {
        const tooltipTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="tooltip"]')
        );
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // ========================================
    // BOOTSTRAP POPOVERS
    // ========================================
    function initPopovers() {
        const popoverTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="popover"]')
        );
        popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    }

    // ========================================
    // FORM VALIDATION
    // ========================================
    function initFormValidation() {
        const forms = document.querySelectorAll('.needs-validation');
        
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }

    // ========================================
    // AJAX FORMS
    // ========================================
    function initAjaxForms() {
        const ajaxForms = document.querySelectorAll('.ajax-form');
        
        ajaxForms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                const submitBtn = form.querySelector('[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                // Disable button and show loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';
                
                fetch(form.action, {
                    method: form.method,
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        if (data.redirect) {
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1000);
                        }
                    } else {
                        showAlert('danger', data.message);
                    }
                })
                .catch(error => {
                    showAlert('danger', 'Có lỗi xảy ra. Vui lòng thử lại!');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            });
        });
    }

    // ========================================
    // AUTO HIDE ALERTS
    // ========================================
    function autoHideAlerts() {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        
        alerts.forEach(function(alert) {
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    }

    // ========================================
    // PASSWORD TOGGLE
    // ========================================
    function initPasswordToggle() {
        const toggleButtons = document.querySelectorAll('.toggle-password');
        
        toggleButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });
        });
    }

    // ========================================
    // FILE UPLOAD PREVIEW
    // ========================================
    function initFileUpload() {
        const fileInputs = document.querySelectorAll('input[type="file"]');
        
        fileInputs.forEach(function(input) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const preview = document.getElementById(input.dataset.preview);
                
                if (file && preview) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        if (file.type.startsWith('image/')) {
                            preview.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded" alt="Preview">`;
                        } else {
                            preview.innerHTML = `<p class="text-muted">${file.name} (${formatFileSize(file.size)})</p>`;
                        }
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
        });
    }

    // ========================================
    // LOAD NOTIFICATIONS
    // ========================================
    function loadNotifications() {
        const notificationBadge = document.getElementById('notification-count');
        
        if (notificationBadge) {
            // Simulate loading notifications (replace with actual API call)
            setTimeout(() => {
                const count = 0; // Replace with actual count from API
                notificationBadge.textContent = count;
                notificationBadge.style.display = count > 0 ? 'inline-block' : 'none';
            }, 1000);
        }
    }

    // ========================================
    // UTILITY FUNCTIONS
    // ========================================
    
    /**
     * Show alert message
     */
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        const container = document.querySelector('.container') || document.body;
        container.insertAdjacentHTML('afterbegin', alertHtml);
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            const alert = container.querySelector('.alert');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    }

    /**
     * Format file size
     */
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    /**
     * Debounce function
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // ========================================
    // GLOBAL FUNCTIONS (Accessible from HTML)
    // ========================================
    
    window.confirmDelete = function(message = 'Bạn có chắc chắn muốn xóa?') {
        return confirm(message);
    };

    window.copyToClipboard = function(text) {
        navigator.clipboard.writeText(text).then(() => {
            showAlert('success', 'Đã sao chép vào clipboard!');
        });
    };

})();

// ========================================
// QUIZ AUTO-SAVE (for quiz pages)
// ========================================
if (document.getElementById('quiz-form')) {
    const quizForm = document.getElementById('quiz-form');
    const inputs = quizForm.querySelectorAll('input[type="radio"], input[type="checkbox"], textarea');
    
    inputs.forEach(input => {
        input.addEventListener('change', debounce(() => {
            saveQuizProgress();
        }, 1000));
    });
    
    function saveQuizProgress() {
        const formData = new FormData(quizForm);
        
        fetch('/api/quiz/save-progress', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Quiz progress saved');
            }
        })
        .catch(error => console.error('Error saving quiz progress:', error));
    }
    
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }
}
