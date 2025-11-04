@echo off
title PHP Configuration Checker
color 0B

echo.
echo ╔═══════════════════════════════════════════════════════════╗
echo ║         PHP CONFIGURATION CHECKER FOR LARAVEL            ║
echo ╚═══════════════════════════════════════════════════════════╝
echo.

echo Checking PHP configuration...
echo.

echo ═══════════════════════════════════════════════════════════
echo PHP VERSION:
php -v | findstr "PHP"
echo.

echo ═══════════════════════════════════════════════════════════
echo OPCACHE STATUS:
php -r "echo 'Enabled: ' . (function_exists('opcache_get_status') ? 'YES' : 'NO') . PHP_EOL;"
php -r "if (function_exists('opcache_get_status')) { $status = opcache_get_status(); echo 'Memory Used: ' . number_format($status['memory_usage']['used_memory']/1024/1024, 2) . ' MB' . PHP_EOL; }"
echo.

echo ═══════════════════════════════════════════════════════════
echo MEMORY & LIMITS:
php -r "echo 'Memory Limit: ' . ini_get('memory_limit') . PHP_EOL;"
php -r "echo 'Max Execution Time: ' . ini_get('max_execution_time') . 's' . PHP_EOL;"
php -r "echo 'Upload Max Filesize: ' . ini_get('upload_max_filesize') . PHP_EOL;"
php -r "echo 'Post Max Size: ' . ini_get('post_max_size') . PHP_EOL;"
echo.

echo ═══════════════════════════════════════════════════════════
echo REALPATH CACHE (Important for Windows):
php -r "echo 'Realpath Cache Size: ' . ini_get('realpath_cache_size') . PHP_EOL;"
php -r "echo 'Realpath Cache TTL: ' . ini_get('realpath_cache_ttl') . 's' . PHP_EOL;"
echo.

echo ═══════════════════════════════════════════════════════════
echo LOADED EXTENSIONS (Key ones):
php -m | findstr /I "pdo mysql opcache mbstring openssl tokenizer xml ctype json bcmath"
echo.

echo ═══════════════════════════════════════════════════════════
echo PHP.INI LOCATION:
php -r "echo php_ini_loaded_file() . PHP_EOL;"
echo.

echo ═══════════════════════════════════════════════════════════
echo RECOMMENDATIONS:
echo.
echo  For BETTER PERFORMANCE, ensure:
echo  ✓ OPcache is ENABLED
echo  ✓ Memory Limit is at least 256M
echo  ✓ Realpath Cache Size is 4096K or higher
echo.
echo  Check PHP-OPTIMIZATION.md for detailed instructions
echo ═══════════════════════════════════════════════════════════
echo.

pause
