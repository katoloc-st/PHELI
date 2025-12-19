# 🚀 Hướng dẫn chạy dự án PheLi - CHỈ 3 BƯỚC

## ✅ Yêu cầu
- **Docker Desktop** đã cài và đang chạy (icon màu xanh)

## 📝 Các bước (3 bước duy nhất)

### Bước 1: Clone dự án
```bash
git clone <repository-url>
cd PHELIEU
```

### Bước 2: Chạy setup
**Windows:**
```bash
setup.bat
```

### Bước 3: Chọn 1 và Enter
```
Chon (1/2/3): 1
```

## ⏱️ Chờ 5-10 phút... XOng!

✅ Website: **http://localhost:8000**  
✅ phpMyAdmin: **http://localhost:8080**

## 🔑 Tài khoản đăng nhập
- Email: `delivery@staff.com`
- Password: `password`

---

## 🆘 Nếu có lỗi

### Docker chưa chạy?
→ Mở **Docker Desktop** và chờ icon màu xanh

### Website báo lỗi?
```bash
docker-compose restart
```

### Muốn cài lại từ đầu?
```bash
docker-compose down -v
setup.bat
# Chọn 1
```

---

## 📌 LƯU Ý QUAN TRỌNG

### ✅ BẠN KHÔNG CẦN:
- ❌ Cài PHP
- ❌ Cài MySQL
- ❌ Cài Composer
- ❌ Sửa file `.env` (tự động tạo)
- ❌ Chạy `composer install`
- ❌ Chạy `php artisan migrate`

### ✅ CHỈ CẦN:
1. Docker Desktop đang chạy
2. Chạy `setup.bat`
3. Chọn 1
4. Chờ xong

---

## 🔄 Các lệnh hữu ích

### Dừng ứng dụng
```bash
docker-compose down
```

### Khởi động lại
```bash
docker-compose up -d
```

### Cập nhật code mới từ Git
```bash
git pull
setup.bat
# Chọn 2
```

### Xem logs nếu có lỗi
```bash
docker-compose logs -f app
```

---

## 🎯 TÓM TẮT

```bash
# CHỈ CẦN 3 LỆNH NÀY:
git clone <repo-url>
cd PHELIEU
setup.bat    # Chọn 1
```

**XONG!** 🎉

Mọi thứ khác (database, dependencies, migrations, seeders) đều tự động!
