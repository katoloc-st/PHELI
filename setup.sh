#!/bin/bash

# PheLi - Script tự động cài đặt và cập nhật với Docker
# Sử dụng: bash setup.sh

echo "=========================================="
echo "  PheLi - Hệ thống quản lý phế liệu"
echo "  Cài đặt và cập nhật tự động"
echo "=========================================="
echo ""

# Kiểm tra Docker đã cài đặt chưa
if ! command -v docker &> /dev/null; then
    echo "❌ Docker chưa được cài đặt!"
    echo "Vui lòng cài đặt Docker Desktop từ: https://www.docker.com/products/docker-desktop"
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose chưa được cài đặt!"
    echo "Vui lòng cài đặt Docker Compose"
    exit 1
fi

echo "✅ Docker và Docker Compose đã sẵn sàng"
echo ""

# Hỏi người dùng muốn làm gì
echo "Bạn muốn làm gì?"
echo "1) Cài đặt mới (lần đầu tiên)"
echo "2) Cập nhật code mới nhất và khởi động lại"
echo "3) Chỉ khởi động lại containers"
echo ""
read -p "Chọn (1/2/3): " choice

case $choice in
    1)
        echo ""
        echo "📦 Bắt đầu cài đặt mới..."
        echo ""

        # Tạo file .env nếu chưa có
        if [ ! -f ".env" ]; then
            echo "📝 Tạo file .env..."
            cp .env.docker .env
        fi

        # Dừng và xóa containers cũ (nếu có)
        echo "🧹 Dọn dẹp containers cũ..."
        docker-compose down -v

        # Build và khởi động containers
        echo "🏗️  Build và khởi động Docker containers..."
        docker-compose up -d --build

        # Chờ MySQL khởi động hoàn tất
        echo "⏳ Chờ database khởi động (30 giây)..."
        sleep 30

        # Cài đặt dependencies
        echo "📚 Cài đặt PHP dependencies..."
        docker-compose exec -T app composer install --no-interaction --prefer-dist

        # Generate key
        echo "🔑 Generate application key..."
        docker-compose exec -T app php artisan key:generate

        # Chạy migrations và seeders
        echo "🗄️  Chạy database migrations và seeders..."
        docker-compose exec -T app php artisan migrate --force
        docker-compose exec -T app php artisan db:seed --force

        # Tạo symbolic link cho storage
        echo "🔗 Tạo storage link..."
        docker-compose exec -T app php artisan storage:link

        # Clear cache
        echo "🧹 Clear cache..."
        docker-compose exec -T app php artisan cache:clear
        docker-compose exec -T app php artisan config:clear
        docker-compose exec -T app php artisan view:clear

        echo ""
        echo "✅ Cài đặt hoàn tất!"
        ;;

    2)
        echo ""
        echo "🔄 Bắt đầu cập nhật..."
        echo ""
        echo "ℹ️  Đảm bảo bạn đã pull code mới nhất (git pull)"
        echo ""

        # Dừng containers
        echo "🛑 Dừng containers..."
        docker-compose down

        # Rebuild containers với code mới
        echo "🏗️  Rebuild containers với code mới..."
        docker-compose up -d --build

        # Chờ services khởi động
        echo "⏳ Chờ services khởi động (20 giây)..."
        sleep 20

        # Cập nhật dependencies
        echo "📚 Cập nhật dependencies..."
        docker-compose exec -T app composer install --no-interaction --prefer-dist

        # Chạy migrations mới (nếu có)
        echo "🗄️  Chạy migrations mới..."
        docker-compose exec -T app php artisan migrate --force

        # Clear cache
        echo "🧹 Clear cache..."
        docker-compose exec -T app php artisan cache:clear
        docker-compose exec -T app php artisan config:clear
        docker-compose exec -T app php artisan view:clear
        docker-compose exec -T app php artisan route:clear

        # Optimize
        echo "⚡ Optimize application..."
        docker-compose exec -T app php artisan optimize

        echo ""
        echo "✅ Cập nhật hoàn tất!"
        ;;

    3)
        echo ""
        echo "🔄 Khởi động lại containers..."
        docker-compose restart
        echo "✅ Hoàn tất!"
        ;;

    *)
        echo "❌ Lựa chọn không hợp lệ!"
        exit 1
        ;;
esac

echo ""
echo "=========================================="
echo "  🎉 Hoàn thành!"
echo "=========================================="
echo ""
echo "📍 Truy cập ứng dụng tại:"
echo "   🌐 Website: http://localhost:8000"
echo "   💾 phpMyAdmin: http://localhost:8080"
echo ""
echo "👤 Tài khoản đăng nhập:"
echo "   📧 Email: delivery@staff.com"
echo "   🔐 Password: password"
echo ""
echo "📚 Các lệnh hữu ích:"
echo "   - Xem logs: docker-compose logs -f app"
echo "   - Dừng app: docker-compose down"
echo "   - Khởi động: docker-compose up -d"
echo "   - Vào container: docker-compose exec app bash"
echo ""
echo "❓ Cần trợ giúp? Xem file DOCKER.md"
echo "=========================================="
