@echo off
echo Starting TaskMaster Pro Test Suite...

echo.
echo 1. Starting PHP development server...
start /B "PHP Server" cmd /c "C:\xampp\php\php.exe -S localhost:8000 -t public"

echo.
echo 2. Waiting for server to start...
timeout /t 3 /nobreak > nul

echo.
echo 3. Testing core functionality...
"C:\xampp\php\php.exe" test-core-functionality.php

echo.
echo 4. Opening application in browser...
start http://localhost:8000/app.html

echo.
echo Test complete! You can now:
echo - Login with admin@test.com / admin123 (admin access)
echo - Or login with user@test.com / user123 (user access)
echo - Test all CRUD operations through the UI
echo.
echo Press any key to stop the server...
pause > nul

echo.
echo Stopping server...
taskkill /f /im php.exe > nul 2>&1
echo Server stopped.
