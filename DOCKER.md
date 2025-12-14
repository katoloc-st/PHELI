# Hướng dẫn sử dụng Docker cho dự án Phế Liệu

## Yêu cầu hệ thống
- Docker Desktop (Windows/Mac) hoặc Docker Engine (Linux)
- Docker Compose

## Cài đặt và chạy dự án

### 1. Chuẩn bị môi trường

Sao chép file cấu hình môi trường:
```bash
cp .env.docker .env
```

### 2. Khởi động Docker containers

```bash
docker-compose up -d
```

Lệnh này sẽ khởi động các services:
- **app**: PHP 8.2-FPM (Laravel application)
- **webserver**: Nginx (port 8000)
- **db**: MySQL 8.0 (port 3306)
- **phpmyadmin**: phpMyAdmin (port 8080)

### 3. Cài đặt dependencies và khởi tạo database

```bash
# Vào container PHP
docker-compose exec app bash

# Trong container, chạy các lệnh sau:
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link

# Thoát khỏi container
exit
```

### 4. Truy cập ứng dụng

- **Trang web chính**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8080
  - Server: `db`
  - Username: `phelieu_user`
  - Password: `password`

## Các lệnh Docker hữu ích

### Xem logs
```bash
# Xem tất cả logs
docker-compose logs

# Xem logs của service cụ thể
docker-compose logs app
docker-compose logs webserver
docker-compose logs db
```

### Khởi động lại services
```bash
# Khởi động lại tất cả
docker-compose restart

# Khởi động lại service cụ thể
docker-compose restart app
```

### Dừng containers
```bash
# Dừng tất cả containers
docker-compose down

# Dừng và xóa volumes (dữ liệu database)
docker-compose down -v
```

### Chạy lệnh Laravel
```bash
# Chạy migration
docker-compose exec app php artisan migrate

# Chạy seeder
docker-compose exec app php artisan db:seed

# Tạo controller
docker-compose exec app php artisan make:controller TenController

# Clear cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear
```

### Truy cập MySQL từ terminal
```bash
docker-compose exec db mysql -u pheli_user -p pheli
# Password: password
```

### Composer commands
```bash
# Install package
docker-compose exec app composer require package-name

# Update dependencies
docker-compose exec app composer update
```

## Cấu trúc thư mục Docker

```
docker/
├── nginx/
│   └── conf.d/
│       └── app.conf          # Cấu hình Nginx
├── php/
│   └── local.ini             # Cấu hình PHP
└── mysql/
    └── my.cnf                # Cấu hình MySQL
```

## Thông tin đăng nhập mặc định

### Database
- Host: `localhost` (từ máy host) hoặc `db` (từ container)
- Port: `3306`
- Database: `pheli`
- Username: `pheli_user`
- Password: `password`
- Root password: `root_password`

### phpMyAdmin
- URL: http://localhost:8080
- Server: `db`
- Username: `pheli_user` hoặc `root`
- Password: `password` hoặc `root_password`

## Troubleshooting

### Port đã được sử dụng
Nếu port 8000, 3306, hoặc 8080 đã được sử dụng, bạn có thể thay đổi trong file `docker-compose.yml`:
```yaml
ports:
  - "8001:80"  # Thay vì 8000:80
```

### Permission issues
```bash
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chmod -R 755 /var/www/html/storage
```

### Rebuild containers
```bash
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

## Dừng và xóa hoàn toàn

```bash
# Dừng và xóa containers, networks
docker-compose down

# Dừng và xóa containers, networks, volumes (bao gồm database)
docker-compose down -v

# Xóa tất cả images
docker-compose down --rmi all
```
