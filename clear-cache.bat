@echo off
echo Clearing all Laravel caches for development...
echo.

php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo.
echo All caches cleared! Now you can make changes to your code.
pause
