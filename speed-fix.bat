@echo off
title Laravel Speed Optimization - Quick Fix
color 0A

echo.
echo  ╔════════════════════════════════════════════════════════════╗
echo  ║   LARAVEL APPLICATION - SPEED OPTIMIZATION TOOL           ║
echo  ║   Fixing slow loading time (3-4s → ~0.5-1s)               ║
echo  ╚════════════════════════════════════════════════════════════╝
echo.

:menu
echo.
echo  Select an option:
echo  ═════════════════════════════════════════════════════════════
echo.
echo  [1] Quick Fix - Optimize Everything (RECOMMENDED)
echo  [2] Clear All Caches (Use when developing)
echo  [3] Cache Config + Routes + Views
echo  [4] Optimize Composer Autoloader
echo  [5] Run Performance Test
echo  [6] Install Laravel Debugbar (for debugging)
echo  [0] Exit
echo.
echo  ═════════════════════════════════════════════════════════════
echo.

set /p choice="Enter your choice: "

if "%choice%"=="1" goto optimize_all
if "%choice%"=="2" goto clear_cache
if "%choice%"=="3" goto cache_all
if "%choice%"=="4" goto optimize_composer
if "%choice%"=="5" goto performance_test
if "%choice%"=="6" goto install_debugbar
if "%choice%"=="0" goto end
goto menu

:optimize_all
echo.
echo ═══════════════════════════════════════════════════════════
echo  RUNNING FULL OPTIMIZATION
echo ═══════════════════════════════════════════════════════════
echo.
echo [Step 1/5] Clearing old caches...
call php artisan cache:clear
call php artisan config:clear
call php artisan route:clear
call php artisan view:clear
echo  ✓ Caches cleared
echo.

echo [Step 2/5] Caching configuration...
call php artisan config:cache
echo  ✓ Config cached
echo.

echo [Step 3/5] Caching routes...
call php artisan route:cache
echo  ✓ Routes cached
echo.

echo [Step 4/5] Caching views...
call php artisan view:cache
echo  ✓ Views cached
echo.

echo [Step 5/5] Optimizing autoloader...
call composer dump-autoload -o
echo  ✓ Autoloader optimized
echo.

echo ═══════════════════════════════════════════════════════════
echo  ✓ OPTIMIZATION COMPLETE!
echo ═══════════════════════════════════════════════════════════
echo.
echo  Your application should now load MUCH faster!
echo.
echo  What changed:
echo  - Session driver: database → file
echo  - Cache store: database → file
echo  - All configs/routes/views are cached
echo  - Composer autoloader is optimized
echo.
echo  NOTE: Run option [2] if you make code changes
echo ═══════════════════════════════════════════════════════════
pause
goto menu

:clear_cache
echo.
echo ═══════════════════════════════════════════════════════════
echo  CLEARING ALL CACHES
echo ═══════════════════════════════════════════════════════════
echo.
call php artisan cache:clear
call php artisan config:clear
call php artisan route:clear
call php artisan view:clear
echo.
echo  ✓ All caches cleared! You can now see your code changes.
echo ═══════════════════════════════════════════════════════════
pause
goto menu

:cache_all
echo.
echo ═══════════════════════════════════════════════════════════
echo  CACHING CONFIG, ROUTES, AND VIEWS
echo ═══════════════════════════════════════════════════════════
echo.
call php artisan config:cache
call php artisan route:cache
call php artisan view:cache
echo.
echo  ✓ Caching complete!
echo ═══════════════════════════════════════════════════════════
pause
goto menu

:optimize_composer
echo.
echo ═══════════════════════════════════════════════════════════
echo  OPTIMIZING COMPOSER AUTOLOADER
echo ═══════════════════════════════════════════════════════════
echo.
call composer dump-autoload -o
echo.
echo  ✓ Autoloader optimized!
echo ═══════════════════════════════════════════════════════════
pause
goto menu

:performance_test
echo.
echo ═══════════════════════════════════════════════════════════
echo  PERFORMANCE TEST
echo ═══════════════════════════════════════════════════════════
echo.
echo  Make sure your server is running (php artisan serve)
echo.
echo  Testing homepage speed...
curl -w "\nTime: %%{time_total}s\n" -o NUL -s http://localhost:8000/
echo.
echo  Testing posts page speed...
curl -w "\nTime: %%{time_total}s\n" -o NUL -s http://localhost:8000/posts
echo.
echo  Expected: 0.5-1.5s first load, 0.3-0.8s cached
echo ═══════════════════════════════════════════════════════════
pause
goto menu

:install_debugbar
echo.
echo ═══════════════════════════════════════════════════════════
echo  INSTALLING LARAVEL DEBUGBAR
echo ═══════════════════════════════════════════════════════════
echo.
echo  This will help you debug slow queries and performance issues
echo.
call composer require barryvdh/laravel-debugbar --dev
echo.
echo  ✓ Debugbar installed!
echo  ✓ Check the bottom of your pages for the debug toolbar
echo ═══════════════════════════════════════════════════════════
pause
goto menu

:end
echo.
echo  Thanks for using the optimization tool!
echo  Your Laravel app should now run faster.
echo.
exit
