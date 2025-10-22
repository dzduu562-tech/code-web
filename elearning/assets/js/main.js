// E-Learning Platform - Main JavaScript

// Theme Toggle
const themeToggle = document.getElementById('themeToggle');
const html = document.documentElement;

// Load saved theme
const savedTheme = localStorage.getItem('theme') || 'light';
html.setAttribute('data-theme', savedTheme);
updateThemeIcon(savedTheme);

if (themeToggle) {
    themeToggle.addEventListener('click', () => {
        const currentTheme = html.getAttribute('data-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        
        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
    });
}

function updateThemeIcon(theme) {
    const icon = document.querySelector('.theme-icon');
    if (icon) {
        icon.textContent = theme === 'light' ? '🌙' : '☀️';
    }
}

// Mobile Navigation Toggle
const navToggle = document.getElementById('navToggle');
const navMenu = document.getElementById('navMenu');

if (navToggle) {
    navToggle.addEventListener('click', () => {
        navMenu.classList.toggle('active');
    });
}

// Notification System
const notificationBtn = document.getElementById('notificationBtn');
const notificationDropdown = document.getElementById('notificationDropdown');
const notificationBadge = document.getElementById('notificationBadge');
const notificationList = document.getElementById('notificationList');
const markAllReadBtn = document.getElementById('markAllRead');

if (notificationBtn) {
    notificationBtn.addEventListener('click', () => {
        notificationDropdown.classList.toggle('show');
        loadNotifications();
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
            notificationDropdown.classList.remove('show');
        }
    });
}

if (markAllReadBtn) {
    markAllReadBtn.addEventListener('click', () => {
        markAllNotificationsRead();
    });
}

function loadNotifications() {
    fetch('/elearning/public/index.php?route=notifications/unread')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                displayNotifications(data.notifications);
                updateNotificationBadge(data.unread_count);
            }
        })
        .catch(err => console.error('Error loading notifications:', err));
}

function displayNotifications(notifications) {
    if (!notificationList) return;
    
    if (notifications.length === 0) {
        notificationList.innerHTML = '<p class="text-center text-muted">Không có thông báo mới</p>';
        return;
    }
    
    notificationList.innerHTML = '<ul class="notification-items">' +
        notifications.map(notif => `
            <li class="${notif.is_read ? 'read' : 'unread'}" data-id="${notif.id}">
                <strong>${escapeHtml(notif.title)}</strong>
                <p>${escapeHtml(notif.message)}</p>
                <small>${timeAgo(notif.created_at)}</small>
                ${!notif.is_read ? '<button class="btn-text" onclick="markNotificationRead(' + notif.id + ')">Đánh dấu đã đọc</button>' : ''}
            </li>
        `).join('') +
        '</ul>';
}

function updateNotificationBadge(count) {
    if (!notificationBadge) return;
    
    if (count > 0) {
        notificationBadge.textContent = count > 99 ? '99+' : count;
        notificationBadge.style.display = 'flex';
    } else {
        notificationBadge.style.display = 'none';
    }
}

function markNotificationRead(id) {
    const formData = new FormData();
    formData.append('id', id);
    
    fetch('/elearning/public/index.php?route=notifications/mark-read', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            loadNotifications();
        }
    })
    .catch(err => console.error('Error marking notification as read:', err));
}

function markAllNotificationsRead() {
    fetch('/elearning/public/index.php?route=notifications/mark-all-read', {
        method: 'POST'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            loadNotifications();
        }
    })
    .catch(err => console.error('Error marking all notifications as read:', err));
}

// Auto-refresh notifications every 30 seconds
setInterval(() => {
    if (notificationBtn) {
        loadNotifications();
    }
}, 30000);

// Initial load
if (notificationBtn) {
    loadNotifications();
}

// Utility Functions
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function timeAgo(timestamp) {
    const now = new Date();
    const past = new Date(timestamp);
    const seconds = Math.floor((now - past) / 1000);
    
    if (seconds < 60) return seconds + ' giây trước';
    if (seconds < 3600) return Math.floor(seconds / 60) + ' phút trước';
    if (seconds < 86400) return Math.floor(seconds / 3600) + ' giờ trước';
    if (seconds < 2592000) return Math.floor(seconds / 86400) + ' ngày trước';
    
    return past.toLocaleDateString('vi-VN');
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', () => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});
