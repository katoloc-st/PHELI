# Hướng dẫn tối ưu hóa tốc độ Laravel Application

## Vấn đề đã khắc phục

Website của bạn load chậm 3-4s do các nguyên nhân sau:

### 1. Session & Cache sử dụng Database (CHẬM)
- **Trước:** Mỗi request phải query database để lấy session/cache
- **Sau:** Chuyển sang file-based, nhanh hơn nhiều cho localhost

### 2. Debug Mode bật (GÂY OVERHEAD)
- APP_DEBUG=true làm chậm application do ghi log chi tiết
- Giữ nguyên vì đang dev, nhưng lưu ý tắt khi deploy production

### 3. Không có Cache cho Config/Routes/Views
- **Trước:** Laravel compile lại mỗi request
- **Sau:** Đã cache tất cả → giảm thời gian compile

### 4. Load External Resources chậm
- **Trước:** Google Fonts & Font Awesome block rendering
- **Sau:** Sử dụng preconnect và async loading

### 5. Scripts blocking render
- **Trước:** JavaScript load đồng bộ, block trang
- **Sau:** Thêm defer attribute

## Các thay đổi đã thực hiện

### File .env
```env
SESSION_DRIVER=file        # Đổi từ database → file (NHANH HƠN)
CACHE_STORE=file           # Đổi từ database → file (NHANH HƠN)
QUEUE_CONNECTION=sync      # Đổi từ database → sync (ÍT DÙNG HƠN)
```

### File app/Providers/AppServiceProvider.php
```php
public function boot(): void
{
    // Tắt query logging khi develop local
    if (app()->environment('local')) {
        \DB::connection()->disableQueryLog();
    }
}
```

### File resources/views/layouts/head.blade.php
- Thêm preconnect cho external domains
- Async load Google Fonts & Font Awesome
- Tối ưu thứ tự load CSS

### File resources/views/layouts/script.blade.php
- Thêm defer cho tất cả scripts
- Scripts sẽ không block rendering

### Laravel Cache Commands đã chạy
```bash
php artisan config:cache  # Cache configuration
php artisan route:cache   # Cache routes
php artisan view:cache    # Cache Blade templates
composer dump-autoload -o # Optimize autoloader
```

## Scripts hỗ trợ

### optimize.bat
Chạy file này để tối ưu application:
```bash
optimize.bat
```
Nội dung:
- Clear tất cả cache
- Cache config, routes, views
- Optimize Composer autoloader

**Lưu ý:** Mỗi khi thay đổi routes, config, hoặc .env → chạy lại script này!

### clear-cache.bat
Chạy file này khi đang develop và cần clear cache:
```bash
clear-cache.bat
```
Nội dung:
- Clear all caches để thấy thay đổi code ngay lập tức

## Kết quả mong đợi

- **Trước:** 3-4 giây/trang
- **Sau:** ~0.5-1 giây/trang (giảm 70-80%)

## Các tối ưu BỔ SUNG có thể làm

### 1. Tối ưu Database Queries
Kiểm tra N+1 query problem:
```bash
composer require barryvdh/laravel-debugbar --dev
```

### 2. Lazy Load Images
Thêm vào các thẻ <img>:
```html
<img src="..." loading="lazy">
```

### 3. Enable OPcache
Trong php.ini:
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.revalidate_freq=0  ; 0 cho dev, 60 cho production
```

### 4. Tối ưu file checkout.blade.php (2222 lines!)
File này quá lớn. Nên tách thành components nhỏ:
```blade
@include('cart.checkout.payment-section')
@include('cart.checkout.shipping-section')
@include('cart.checkout.summary-section')
```

### 5. Minify CSS & JavaScript
```bash
npm install
npm run build
```

### 6. Sử dụng CDN cho static assets
Upload images, CSS, JS lên CDN (Cloudflare, AWS S3)

### 7. Enable Gzip Compression
Trong .htaccess hoặc nginx config

## Monitoring Performance

### Kiểm tra thời gian load
Mở Developer Tools (F12) → Network tab → Reload trang
- Xem thời gian load của từng request
- Xem file nào load chậm nhất

### Laravel Telescope (Optional)
```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```
Truy cập: http://localhost:8000/telescope

## Lưu ý quan trọng

### Khi develop (thay đổi code)
```bash
clear-cache.bat    # Clear cache để thấy thay đổi
```

### Khi test performance
```bash
optimize.bat       # Cache everything để test tốc độ thực
```

### Khi deploy production
1. Set APP_DEBUG=false trong .env
2. Chạy optimize.bat
3. Enable OPcache
4. Sử dụng Redis/Memcached thay vì file cache
5. Configure CDN

## Checklist

- [x] Đổi SESSION_DRIVER từ database → file
- [x] Đổi CACHE_STORE từ database → file
- [x] Cache config, routes, views
- [x] Optimize autoloader
- [x] Async load external fonts
- [x] Defer JavaScript loading
- [x] Disable query log trong local
- [ ] Tối ưu database queries (N+1)
- [ ] Tách file checkout.blade.php thành components
- [ ] Enable OPcache
- [ ] Minify assets với Vite
- [ ] Lazy load images

## Hỗ trợ

Nếu vẫn chậm sau khi áp dụng:
1. Check Developer Tools Network tab
2. Xem request nào chậm nhất
3. Cài Laravel Debugbar để debug queries
4. Kiểm tra XAMPP/Laragon có đủ RAM không
5. Restart web server

---
**Tạo bởi:** GitHub Copilot
**Ngày:** 4/11/2025
