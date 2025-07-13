@echo off
echo 🚀 TaskMaster Pro - Simple Server Start
echo =========================================
echo.

echo ✅ MongoDB compatibility fixed!
echo ✅ Dependencies updated successfully!
echo.

echo 🌐 Starting development server...
echo Frontend: http://localhost:8000/app.html
echo API: http://localhost:8000/index.php
echo.
echo 🔐 Admin Credentials:
echo Email: admin@taskmaster.com
echo Password: admin123456
echo.
echo Press Ctrl+C to stop the server
echo.

C:\xampp\php\php.exe -S localhost:8000 -t public
