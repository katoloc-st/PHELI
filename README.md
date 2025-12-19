<p align="center">
  <a href="https://www.uit.edu.vn/" title="Trường Đại học Công nghệ Thông tin" style="border: none;">
    <img src="https://i.imgur.com/WmMnSRt.png" alt="Trường Đại học Công nghệ Thông tin | University of Information Technology">
  </a>
</p>

<h1 align="center"><b>PHÁT TRIỂN ỨNG DỤNG WEB - IS207</b></h1>

## GIỚI THIỆU MÔN HỌC

-    **Tên môn học:** Phát triển ứng dụng Web
-    **Mã môn học:** IS207
-    **Mã lớp:** IS207.Q13
-    **Năm học:** HK1 (2025 - 2026)
-    **Giảng viên:** ThS.Tạ Việt Phương
-    **Email:** phuongtv@uit.edu.vn

## GIỚI THIỆU ĐỒ ÁN

-    **Đề tài:** Sàn thương mại điện tử thu gom phế liệu
-    **Repository:** [PHÁT TRIỂN ỨNG DỤNG WEB - IS207](https://github.com/katoloc-st/PHELI)
-    **Behance:** [PHELI Website](https://www.figma.com/design/Gkc1QX4mMjm3p8lA0ic0Bh/phelieu?node-id=0-1&t=TTDZTyqTCnSwcqnX-1)
-    **Trang giới thiệu nhóm:** [PHELI](https://sites.google.com/gm.uit.edu.vn/pheli/)

## CÔNG NGHỆ SỬ DỤNG

-    **Backend:** [PHP](https://www.php.net/), [Laravel](https://laravel.com/)
-    **Frontend:** [HTML](https://developer.mozilla.org/en-US/docs/Web/HTML), [CSS](https://developer.mozilla.org/en-US/docs/Web/CSS), [JavaScript](https://www.javascript.com/), [ReactJS](https://reactjs.org/), [Redux](https://redux.js.org/), [Ant Design](https://ant.design/), [Tailwind CSS](https://tailwindcss.com/)
-    **Database:** [MySQL](https://www.mysql.com/)

## THÀNH VIÊN NHÓM

| STT | MSSV     | Họ và Tên            | GitHub                            | Email                  |
| :-- | :------- | :------------------- | :-------------------------------- | :--------------------- |
| 1   | 23520859 | Nguyễn Phúc Lộc        | https://github.com/katoloc-st     | 23520859@gm.uit.edu.vn |
| 2   | 23520462 | Nguyễn Minh Hiển   | https://github.com/minhhien041205       | 23520462@gm.uit.edu.vn |
| 3   | 23520843 | Võ Ngọc Hoàng Lân | https://github.com/VoNgocHoangLanUIT          | 23520843@gm.uit.edu.vn |
| 4   | 23521273 | Võ Hồ Trung Quân  | https://github.com/Quandtzzzz | 23521273@gm.uit.edu.vn |

## Cài đặt

**Windows:**
```bash
setup.bat
```

**Linux/Mac:**
```bash
bash setup.sh
```

Chọn option `1` để cài đặt mới.

Script sẽ tự động:
- ✅ Khởi động Docker containers
- ✅ Cài đặt dependencies
- ✅ Setup database và seed dữ liệu
- ✅ Clear cache và optimize

📖 **Xem thêm**: 
- [Quick Start Guide](QUICKSTART.md) - Hướng dẫn nhanh
- [Docker Guide](DOCKER.md) - Hướng dẫn chi tiết Docker

Truy cập: http://localhost:8000

## Cập nhật code mới

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
php artisan migrate:fresh --seed
php artisan migrate

# Chạy seeder
php artisan db:seed

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
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

