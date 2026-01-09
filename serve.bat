@echo off
echo Starting Laravel WA Marketplace...
echo.
echo Laravel Server: http://localhost:8000
echo.
start "Laravel Server" cmd /k "C:\xampp\php\php artisan serve"
start "Vite Dev Server" cmd /k "npm run dev"
echo.
echo Servers started! Press any key to close this window...
pause >nul
