# Tối ưu PHP.INI cho Laravel (XAMPP/Laragon)

## Tìm file php.ini

### XAMPP
`C:\xampp\php\php.ini`

### Laragon
`C:\laragon\bin\php\php8.x.x\php.ini`

## Các cấu hình cần thay đổi

### 1. Bật OPcache (QUAN TRỌNG NHẤT)
```ini
[opcache]
; Bật OPcache
opcache.enable=1
opcache.enable_cli=1

; Tăng memory cho OPcache
opcache.memory_consumption=128

; Tăng số file được cache
opcache.max_accelerated_files=10000

; Kiểm tra thay đổi file (0 = mỗi request, tốt cho dev)
opcache.revalidate_freq=0

; Validate timestamps (1 = kiểm tra thay đổi, tốt cho dev)
opcache.validate_timestamps=1

; Optimization level
opcache.optimization_level=0x7FFFFFFF

; Save comments for Doctrine, Annotations
opcache.save_comments=1

; Fast shutdown
opcache.fast_shutdown=1
```

### 2. Tăng Memory Limit
```ini
; Tăng memory limit (mặc định thường là 128M)
memory_limit = 256M
```

### 3. Tăng Max Execution Time
```ini
; Tăng thời gian execute (cho dev)
max_execution_time = 300
max_input_time = 300
```

### 4. Tăng Upload Size
```ini
; Tăng kích thước upload
upload_max_filesize = 20M
post_max_size = 25M
```

### 5. Realpath Cache (Quan trọng cho Windows)
```ini
; Tăng realpath cache - giúp Laravel tìm file nhanh hơn
realpath_cache_size = 4096K
realpath_cache_ttl = 600
```

### 6. Output Buffering
```ini
; Bật output buffering
output_buffering = 4096
```

## Cách áp dụng

### Bước 1: Backup php.ini
Copy file php.ini thành php.ini.backup

### Bước 2: Mở php.ini bằng Notepad++ hoặc VS Code

### Bước 3: Tìm và sửa các dòng trên
- Dùng Ctrl+F để tìm từng dòng
- Uncomment (xóa dấu ;) nếu đang bị comment
- Thay đổi giá trị theo hướng dẫn

### Bước 4: Restart Web Server
- **XAMPP:** Stop và Start lại Apache
- **Laragon:** Stop All và Start lại

### Bước 5: Kiểm tra
Tạo file `phpinfo.php` trong public folder:
```php
<?php
phpinfo();
?>
```

Truy cập: http://localhost:8000/phpinfo.php
Tìm kiếm "opcache" để xác nhận đã bật

### Bước 6: Xóa phpinfo.php (BẮT BUỘC)
Xóa file này sau khi kiểm tra xong vì lý do bảo mật!

## Kết quả mong đợi

| Tối ưu | Cải thiện tốc độ |
|--------|------------------|
| OPcache | +50-70% |
| Realpath cache | +10-20% |
| Memory limit | Tránh crash |
| Output buffering | +5-10% |

**Tổng cộng:** Có thể nhanh hơn 60-80% so với không tối ưu!

## Lưu ý Production

Khi deploy lên production, thay đổi:
```ini
opcache.revalidate_freq=60      ; Chỉ kiểm tra mỗi 60s
opcache.validate_timestamps=0   ; Không kiểm tra timestamps
```

## Troubleshooting

### OPcache không hoạt động?
```bash
# Kiểm tra extension
php -m | findstr opcache
```

Nếu không thấy, tìm và uncomment trong php.ini:
```ini
extension=opcache
```

### Vẫn chậm?
1. Kiểm tra Task Manager → RAM usage
2. Kiểm tra MySQL/MariaDB có chạy không
3. Chạy `speed-fix.bat` option [1]
4. Clear browser cache (Ctrl+Shift+Delete)

## Quick Check Script

Tạo file `check-php-config.php`:
```php
<?php
echo "PHP Version: " . PHP_VERSION . "\n";
echo "OPcache Enabled: " . (function_exists('opcache_get_status') && opcache_get_status() ? 'YES' : 'NO') . "\n";
echo "Memory Limit: " . ini_get('memory_limit') . "\n";
echo "Max Execution Time: " . ini_get('max_execution_time') . "s\n";
echo "Upload Max Filesize: " . ini_get('upload_max_filesize') . "\n";
echo "Realpath Cache Size: " . ini_get('realpath_cache_size') . "\n";
?>
```

Chạy: `php check-php-config.php`

---
**Lưu ý:** Các thay đổi này chỉ ảnh hưởng đến PHP, không làm hỏng code Laravel của bạn.
