# PheLi - Quick Setup Guide
# Hướng dẫn cài đặt nhanh

## 🚀 Cài đặt lần đầu

### Windows
1. Cài đặt [Docker Desktop](https://www.docker.com/products/docker-desktop)
2. Mở PowerShell/Command Prompt trong thư mục dự án
3. Chạy: `setup.bat`
4. Chọn option `1` (Cài đặt mới)
5. Đợi khoảng 2-3 phút
6. Truy cập: http://localhost:8000

### Linux/Mac
1. Cài đặt Docker và Docker Compose
2. Mở Terminal trong thư mục dự án
3. Chạy: `bash setup.sh`
4. Chọn option `1` (Cài đặt mới)
5. Đợi khoảng 2-3 phút
6. Truy cập: http://localhost:8000

---

## 🔄 Cập nhật code mới

Khi có code mới được push lên repository:

### Windows
1. Pull code mới nhất: `git pull`
2. Mở PowerShell/Command Prompt trong thư mục dự án
3. Chạy: `setup.bat`
4. Chọn option `2` (Cập nhật code)
5. Script sẽ tự động:
   - Rebuild Docker containers
   - Cập nhật dependencies
   - Chạy migrations mới
   - Clear cache
   - Optimize ứng dụng

### Linux/Mac
1. Pull code mới nhất: `git pull`
2. Mở Terminal trong thư mục dự án
3. Chạy: `bash setup.sh`
4. Chọn option `2` (Cập nhật code)
5. Quá trình tự động như trên

---

## 📍 Thông tin truy cập

### Ứng dụng web
- URL: http://localhost:8000
- Email: `delivery@staff.com`
- Password: `password`

### phpMyAdmin (Quản lý database)
- URL: http://localhost:8080
- Server: `db`
- Username: `pheli_user`
- Password: `password`

---

## 🛠️ Các lệnh thường dùng

### Xem logs (theo dõi lỗi)
```bash
docker-compose logs -f app
```

### Dừng ứng dụng
```bash
docker-compose down
```

### Khởi động lại
```bash
docker-compose up -d
```

### Vào container để chạy lệnh Laravel
```bash
docker-compose exec app bash
# Sau đó có thể chạy: php artisan migrate, php artisan db:seed, v.v.
```

### Xem trạng thái containers
```bash
docker-compose ps
```

---

## ⚠️ Xử lý sự cố

### Port 8000 hoặc 3306 đã được sử dụng
Sửa file `docker-compose.yml`:
```yaml
webserver:
  ports:
    - "8001:80"  # Đổi từ 8000 thành 8001
```

### Database connection error
1. Chờ thêm 30 giây để MySQL khởi động hoàn tất
2. Chạy lại: `docker-compose restart`

### Clear toàn bộ và cài lại từ đầu
```bash
# Windows
docker-compose down -v
setup.bat
# Chọn option 1

# Linux/Mac
docker-compose down -v
bash setup.sh
# Chọn option 1
```

### Lỗi permission (Linux/Mac)
```bash
sudo chown -R $USER:$USER .
chmod +x setup.sh
```

---

## 📋 Quy trình làm việc hàng ngày

1. **Sáng**: Pull code mới và cập nhật
   ```bash
   git pull

   # Sau đó chạy:
   # Windows: setup.bat → chọn 2
   # Linux/Mac: bash setup.sh → chọn 2
   ```

2. **Làm việc**: Containers đang chạy ở background
   - Viết code bình thường
   - Laravel tự động reload (volume mounted)
   - Xem logs nếu cần: `docker-compose logs -f app`

3. **Tối**: Dừng containers (tiết kiệm tài nguyên)
   ```bash
   docker-compose down
   ```

4. **Ngày hôm sau**: Khởi động lại
   ```bash
   # Windows: setup.bat → chọn 3
   # Linux/Mac: bash setup.sh → chọn 3
   ```

---

## 💡 Tips

### Không cần rebuild mỗi lần thay đổi code
- Code của bạn được mount vào container qua volumes
- Chỉ cần save file, Laravel sẽ tự động nhận thay đổi
- Chỉ rebuild khi:
  - Thay đổi Dockerfile
  - Thay đổi docker-compose.yml
  - Cài package mới qua composer

### Backup database trước khi cập nhật lớn
```bash
docker-compose exec db mysqldump -u pheli_user -ppassword pheli > backup.sql
```

### Restore database
```bash
docker-compose exec -T db mysql -u pheli_user -ppassword pheli < backup.sql
```

---

## 📚 Tài liệu chi tiết

Xem file `DOCKER.md` để biết thêm thông tin chi tiết về:
- Cấu trúc Docker
- Các lệnh nâng cao
- Troubleshooting chi tiết
- Cấu hình tùy chỉnh
