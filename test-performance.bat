@echo off
echo ============================================
echo Laravel Performance Test
echo ============================================
echo.
echo Testing application response time...
echo.

echo [Test 1] Homepage
curl -w "Time: %%{time_total}s\n" -o NUL -s http://localhost:8000/
echo.

echo [Test 2] Posts Index
curl -w "Time: %%{time_total}s\n" -o NUL -s http://localhost:8000/posts
echo.

echo [Test 3] Price Table
curl -w "Time: %%{time_total}s\n" -o NUL -s http://localhost:8000/price-table
echo.

echo ============================================
echo Test completed!
echo.
echo Expected results after optimization:
echo - First load: ~0.5-1.5s (Laravel bootstrap)
echo - Subsequent loads: ~0.3-0.8s (cached)
echo.
echo If still slow, run: php artisan telescope:install
echo to debug database queries.
echo ============================================
pause
