# PheLi - Hệ thống quản lý phế liệu

## Giới thiệu
Hệ thống quản lý phế liệu được xây dựng bằng Laravel, giúp quản lý các đơn hàng, giao dịch và vận chuyển phế liệu.

## Yêu cầu hệ thống

### Không dùng Docker
- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js & NPM

### Sử dụng Docker (Khuyến nghị)
- Docker Desktop (Windows/Mac) hoặc Docker Engine (Linux)
- Docker Compose

## Cài đặt

### ⚡ Cách nhanh nhất (Khuyến nghị)

**Windows:**
```bash
setup.bat
```

**Linux/Mac:**
```bash
bash setup.sh
```

Chọn option `1` để cài đặt mới, hoặc `2` để cập nhật code mới nhất.

Script sẽ tự động:
- ✅ Khởi động Docker containers
- ✅ Cài đặt dependencies
- ✅ Setup database và seed dữ liệu
- ✅ Clear cache và optimize

📖 **Xem thêm**: 
- [Quick Start Guide](QUICKSTART.md) - Hướng dẫn nhanh
- [Docker Guide](DOCKER.md) - Hướng dẫn chi tiết Docker

---

### Cách 1: Sử dụng Docker (Thủ công)

1. **Clone repository**
```bash
git clone <repository-url>
cd PHELIEU
```

2. **Sao chép file cấu hình**
```bash
cp .env.docker .env
```

3. **Khởi động Docker containers**
```bash
docker-compose up -d
```

4. **Cài đặt và khởi tạo database**
```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
docker-compose exec app php artisan storage:link
```

5. **Truy cập ứng dụng**
- Website: http://localhost:8000
- phpMyAdmin: http://localhost:8080

### Cách 2: Cài đặt thủ công (Không dùng Docker)

1. **Clone repository**
```bash
git clone <repository-url>
cd PheLi
```

2. **Cài đặt dependencies**
```bash
composer install
npm install
```

3. **Cấu hình môi trường**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Cấu hình database**
Chỉnh sửa file `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pheli
DB_USERNAME=root
DB_PASSWORD=your_password
```

5. **Chạy migration và seeder**
```bash
php artisan migrate --seed
php artisan storage:link
```

6. **Build assets**
```bash
npm run build
```

7. **Khởi động server**
```bash
php artisan serve
```

Truy cập: http://localhost:8000

## Cập nhật code mới

Khi có code mới từ repository:

```bash
# 1. Pull code mới nhất
git pull

# 2. Chạy script cập nhật
```

**Windows:**
```bash
setup.bat
# Chọn option 2
```

**Linux/Mac:**
```bash
bash setup.sh
# Chọn option 2
```

Script sẽ tự động rebuild containers, update dependencies và chạy migrations mới.

---

## Tài khoản mặc định

Sau khi chạy seeder, bạn có thể đăng nhập với các tài khoản:

### Nhân viên giao hàng
- Email: `delivery@staff.com`
- Password: `password`

## Tính năng chính

- 🏢 Quản lý người dùng (công ty, đại lý, nhà máy tái chế)
- 📦 Quản lý bài đăng phế liệu
- 🛒 Giỏ hàng và đặt hàng
- 💰 Quản lý đơn hàng và giao dịch
- 🚚 Quản lý vận chuyển cho nhân viên giao hàng
- 📊 Báo cáo và thống kê
- ⭐ Đánh giá và nhận xét

## Các lệnh hữu ích

### Với Docker
```bash
# Xem logs
docker-compose logs app

# Chạy migration
docker-compose exec app php artisan migrate

# Chạy seeder
docker-compose exec app php artisan db:seed

# Clear cache
docker-compose exec app php artisan cache:clear

# Dừng containers
docker-compose down
```

### Không dùng Docker
```bash
# Chạy migration
php artisan migrate

# Chạy seeder
php artisan db:seed

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Troubleshooting

### Lỗi permission (Docker)
```bash
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chmod -R 755 /var/www/html/storage
```

### Port đã được sử dụng
Thay đổi port trong `docker-compose.yml` hoặc dừng service đang chiếm port.

### Database connection error
Kiểm tra cấu hình trong file `.env` và đảm bảo MySQL đang chạy.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

