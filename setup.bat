@echo off
setlocal enabledelayedexpansion

:: PheLi - Script tu dong cai dat va cap nhat voi Docker

echo ==========================================
echo   PheLi - He thong quan ly phe lieu
echo   Cai dat va cap nhat tu dong
echo ==========================================
echo.

:: Kiem tra Docker
docker --version >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Docker chua duoc cai dat!
    echo Vui long cai dat Docker Desktop: https://www.docker.com/products/docker-desktop
    pause
    exit /b 1
)

docker-compose --version >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Docker Compose chua duoc cai dat!
    pause
    exit /b 1
)

echo [OK] Docker va Docker Compose da san sang

:: Kiem tra Docker daemon
docker ps >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Docker Desktop chua khoi dong!
    echo.
    echo Vui long:
    echo 1. Mo Docker Desktop
    echo 2. Cho icon tray chuyen mau xanh
    echo 3. Chay lai script nay
    pause
    exit /b 1
)

echo [OK] Docker daemon dang chay
echo.

:: Menu
echo Ban muon lam gi?
echo 1) Cai dat moi
echo 2) Cap nhat code
echo 3) Khoi dong lai
echo.
set /p choice="Chon (1/2/3): "

if "%choice%"=="1" goto install
if "%choice%"=="2" goto update
if "%choice%"=="3" goto restart
echo [ERROR] Lua chon khong hop le!
pause
exit /b 1

:install
echo.
echo [INSTALL] Bat dau cai dat...

if not exist ".env" (
    echo [STEP] Tao file .env tu template...
    copy .env.docker .env
) else (
    echo [INFO] File .env da ton tai, giu nguyen
)

docker-compose down -v
docker-compose up -d --build

echo [WAIT] Cho database khoi dong...
timeout /t 30 /nobreak >nul

docker-compose exec -T app composer install --no-interaction
docker-compose exec -T app php artisan key:generate
docker-compose exec -T app php artisan migrate --force
docker-compose exec -T app php artisan db:seed --force
docker-compose exec -T app rm -f public/storage
docker-compose exec -T app php artisan storage:link
docker-compose exec -T app php artisan cache:clear
docker-compose exec -T app php artisan config:clear
docker-compose exec -T app php artisan view:clear

echo [STEP] Kiem tra Redis...
docker-compose exec redis redis-cli ping >nul 2>&1
if errorlevel 1 (
    echo [WARNING] Redis chua khoi dong, cache se dung file
) else (
    echo [OK] Redis dang hoat dong - Cache su dung RAM
)

if not exist ".env" (
    echo [WARNING] Khong tim thay .env, tao tu template...
    copy .env.docker .env
)

echo.
echo [SUCCESS] Cai dat hoan tat!
goto finish

:update
echo.
echo [UPDATE] Bat dau cap nhat...

docker-compose down
docker-compose up -d --build

echo [WAIT] Cho services khoi dong...
timeout /t 20 /nobreak >nul

docker-compose exec -T app composer install --no-interaction
docker-compose exec -T app php artisan migrate --force
docker-compose exec -T app php artisan cache:clear
docker-compose exec -T app php artisan config:clear
docker-compose exec -T app php artisan view:clear
docker-compose exec -T app php artisan route:clear
docker-compose exec -T app php artisan optimize

echo.
echo [SUCCESS] Cap nhat hoan tat!
goto finish

:restart
echo.
echo [RESTART] Khoi dong lai...
docker-compose restart
echo [SUCCESS] Hoan tat!
goto finish

:finish
echo.
echo ==========================================
echo   HOAN THANH!
echo ==========================================
echo.
echo Website: http://localhost:8000
echo phpMyAdmin: http://localhost:8080
echo.
echo Tai khoan:
echo Email: delivery@staff.com
echo Password: password
echo.
echo Lenh huu ich:
echo - Xem logs: docker-compose logs -f app
echo - Dung app: docker-compose down
echo - Khoi dong: docker-compose up -d
echo.
pause
