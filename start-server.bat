@echo off
echo 🚀 TaskMaster Pro - Terminal Setup
echo =====================================
echo.

REM Try to find PHP in common locations
set PHP_PATH=""
if exist "C:\xampp\php\php.exe" set PHP_PATH=C:\xampp\php\php.exe
if exist "C:\php\php.exe" set PHP_PATH=C:\php\php.exe
if exist "C:\wamp\bin\php\php8.2.12\php.exe" set PHP_PATH=C:\wamp\bin\php\php8.2.12\php.exe

REM Check if PHP is in PATH
php --version >nul 2>&1
if %errorlevel% == 0 (
    set PHP_PATH=php
)

if %PHP_PATH%=="" (
    echo ❌ PHP not found. Please ensure PHP is installed or XAMPP is available.
    echo Common locations checked:
    echo   - C:\xampp\php\php.exe
    echo   - C:\php\php.exe
    echo   - C:\wamp\bin\php\php8.2.12\php.exe
    echo.
    echo Please install PHP or update the path in this script.
    pause
    exit /b 1
)

echo ✅ Found PHP at: %PHP_PATH%
echo.

REM Initialize database
echo 📊 Initializing database...
%PHP_PATH% initialize.php
if %errorlevel% neq 0 (
    echo ❌ Database initialization failed
    pause
    exit /b 1
)

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

REM Start the server
%PHP_PATH% -S localhost:8000 -t public
