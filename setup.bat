@echo off
setlocal enabledelayedexpansion

:: PheLi - Script tu dong cai dat va cap nhat voi Docker
:: Su dung: setup.bat

echo ==========================================
echo   PheLi - He thong quan ly phe lieu
echo   Cai dat va cap nhat tu dong
echo ==========================================
echo.

:: Kiem tra Docker da cai dat chua
docker --version >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Docker chua duoc cai dat!
    echo Vui long cai dat Docker Desktop tu: https://www.docker.com/products/docker-desktop
    pause
    exit /b 1
)

docker-compose --version >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Docker Compose chua duoc cai dat!
    echo Vui long cai dat Docker Compose
    pause
    exit /b 1
)

echo [OK] Docker va Docker Compose da san sang
echo.

:: Hoi nguoi dung muon lam gi
echo Ban muon lam gi?
echo 1) Cai dat moi (lan dau tien)
echo 2) Cap nhat code moi nhat va khoi dong lai
echo 3) Chi khoi dong lai containers
echo.
set /p choice="Chon (1/2/3): "

if "%choice%"=="1" goto install
if "%choice%"=="2" goto update
if "%choice%"=="3" goto restart
goto invalid

:install
echo.
echo [INSTALL] Bat dau cai dat moi...
echo.

:: Tao file .env neu chua co
if not exist ".env" (
    echo [STEP] Tao file .env...
    copy .env.docker .env
)

:: Dung va xoa containers cu (neu co)
echo [STEP] Don dep containers cu...
docker-compose down -v

:: Build va khoi dong containers
echo [STEP] Build va khoi dong Docker containers...
docker-compose up -d --build

:: Cho MySQL khoi dong hoan tat
echo [WAIT] Cho database khoi dong (30 giay)...
timeout /t 30 /nobreak >nul

:: Cai dat dependencies
echo [STEP] Cai dat PHP dependencies...
docker-compose exec -T app composer install --no-interaction --prefer-dist

:: Generate key
echo [STEP] Generate application key...
docker-compose exec -T app php artisan key:generate

:: Chay migrations va seeders
echo [STEP] Chay database migrations va seeders...
docker-compose exec -T app php artisan migrate --force
docker-compose exec -T app php artisan db:seed --force

:: Tao symbolic link cho storage
echo [STEP] Tao storage link...
docker-compose exec -T app php artisan storage:link

:: Clear cache
echo [STEP] Clear cache...
docker-compose exec -T app php artisan cache:clear
docker-compose exec -T app php artisan config:clear
docker-compose exec -T app php artisan view:clear

echo.
echo [SUCCESS] Cai dat hoan tat!
goto finish

:update
echo.
echo [UPDATE] Bat dau cap nhat...
echo.
echo [INFO] Dam bao ban da pull code moi nhat (git pull)
echo.

:: Dung containers
echo [STEP] Dung containers...
docker-compose down

:: Rebuild containers voi code moi
echo [STEP] Rebuild containers voi code moi...
docker-compose up -d --build

:: Cho services khoi dong
echo [WAIT] Cho services khoi dong (20 giay)...
timeout /t 20 /nobreak >nul

:: Cap nhat dependencies
echo [STEP] Cap nhat dependencies...
docker-compose exec -T app composer install --no-interaction --prefer-dist

:: Chay migrations moi (neu co)
echo [STEP] Chay migrations moi...
docker-compose exec -T app php artisan migrate --force

:: Clear cache
echo [STEP] Clear cache...
docker-compose exec -T app php artisan cache:clear
docker-compose exec -T app php artisan config:clear
docker-compose exec -T app php artisan view:clear
docker-compose exec -T app php artisan route:clear

:: Optimize
echo [STEP] Optimize application...
docker-compose exec -T app php artisan optimize

echo.
echo [SUCCESS] Cap nhat hoan tat!
goto finish

:restart
echo.
echo [RESTART] Khoi dong lai containers...
docker-compose restart
echo [SUCCESS] Hoan tat!
goto finish

:invalid
echo [ERROR] Lua chon khong hop le!
pause
exit /b 1

:finish
echo.
echo ==========================================
echo   HOAN THANH!
echo ==========================================
echo.
echo Truy cap ung dung tai:
echo    Website: http://localhost:8000
echo    phpMyAdmin: http://localhost:8080
echo.
echo Tai khoan dang nhap:
echo    Email: delivery@staff.com
echo    Password: password
echo.
echo Cac lenh huu ich:
echo    - Xem logs: docker-compose logs -f app
echo    - Dung app: docker-compose down
echo    - Khoi dong: docker-compose up -d
echo    - Vao container: docker-compose exec app bash
echo.
echo Can tro giup? Xem file DOCKER.md
echo ==========================================
echo.
pause
