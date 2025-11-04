@echo off
echo Optimizing Laravel Application for Better Performance...
echo.

echo [1/6] Clearing all caches...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo.

echo [2/6] Caching configuration...
php artisan config:cache
echo.

echo [3/6] Caching routes...
php artisan route:cache
echo.

echo [4/6] Caching views...
php artisan view:cache
echo.

echo [5/6] Optimizing Composer autoloader...
composer dump-autoload -o
echo.

echo [6/6] Optimization complete!
echo.
echo Your application should now load much faster.
echo Note: When you make changes to routes or config, run this script again.
pause
