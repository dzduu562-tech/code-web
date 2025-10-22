#!/bin/bash

# E-Learning Platform - Push to GitHub Script
# Chạy script này sau khi đã tạo repository trên GitHub

echo "🚀 E-Learning Platform - GitHub Push Script"
echo "============================================="

# Kiểm tra xem đã có remote chưa
if git remote get-url origin 2>/dev/null; then
    echo "✅ Remote origin đã tồn tại:"
    git remote get-url origin
    echo ""
    read -p "Bạn có muốn thay đổi URL không? (y/n): " change_url
    if [ "$change_url" = "y" ] || [ "$change_url" = "Y" ]; then
        read -p "Nhập URL GitHub repository mới: " github_url
        git remote set-url origin "$github_url"
        echo "✅ Đã cập nhật remote URL"
    fi
else
    echo "📝 Chưa có remote origin. Vui lòng nhập URL GitHub repository:"
    echo "Ví dụ: https://github.com/username/elearning-platform.git"
    read -p "URL: " github_url
    
    if [ -z "$github_url" ]; then
        echo "❌ Lỗi: URL không được để trống!"
        exit 1
    fi
    
    git remote add origin "$github_url"
    echo "✅ Đã thêm remote origin: $github_url"
fi

echo ""
echo "📊 Kiểm tra trạng thái repository..."
git status

echo ""
echo "🔄 Đổi tên branch từ master sang main..."
git branch -M main

echo ""
echo "📤 Đang push code lên GitHub..."
if git push -u origin main; then
    echo ""
    echo "🎉 THÀNH CÔNG!"
    echo "✅ Code đã được push lên GitHub repository"
    echo "🌐 Bạn có thể xem tại: $(git remote get-url origin | sed 's/\.git$//')"
    echo ""
    echo "📋 Thông tin repository:"
    echo "   - Branch: main"
    echo "   - Files: $(git ls-files | wc -l) files"
    echo "   - Commits: $(git rev-list --count HEAD)"
    echo ""
    echo "🔗 Để clone về máy khác, sử dụng lệnh:"
    echo "   git clone $(git remote get-url origin)"
else
    echo ""
    echo "❌ LỖI khi push!"
    echo "Có thể do:"
    echo "1. URL repository không đúng"
    echo "2. Không có quyền truy cập repository"
    echo "3. Repository đã có nội dung (cần force push)"
    echo ""
    echo "💡 Thử force push (cẩn thận - sẽ ghi đè):"
    echo "   git push -f origin main"
fi