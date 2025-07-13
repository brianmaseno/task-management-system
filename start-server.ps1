# TaskMaster Pro - PowerShell Startup Script
Write-Host "🚀 TaskMaster Pro - Terminal Setup" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host ""

# Try to find PHP in common locations
$phpPaths = @(
    "C:\xampp\php\php.exe",
    "C:\php\php.exe",
    "C:\wamp\bin\php\php8.2.12\php.exe",
    "php"  # Check if PHP is in PATH
)

$phpPath = $null
foreach ($path in $phpPaths) {
    if ($path -eq "php") {
        # Check if PHP is in PATH
        try {
            $null = & php --version 2>$null
            $phpPath = "php"
            break
        }
        catch {
            continue
        }
    }
    elseif (Test-Path $path) {
        $phpPath = $path
        break
    }
}

if (-not $phpPath) {
    Write-Host "❌ PHP not found. Please ensure PHP is installed." -ForegroundColor Red
    Write-Host "Common locations checked:" -ForegroundColor Yellow
    Write-Host "  - C:\xampp\php\php.exe"
    Write-Host "  - C:\php\php.exe"
    Write-Host "  - C:\wamp\bin\php\php8.2.12\php.exe"
    Write-Host ""
    Write-Host "Please install PHP or add it to your PATH." -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit 1
}

Write-Host "✅ Found PHP at: $phpPath" -ForegroundColor Green
Write-Host ""

# Initialize database
Write-Host "📊 Initializing database..." -ForegroundColor Yellow
try {
    & $phpPath initialize.php
    if ($LASTEXITCODE -ne 0) {
        throw "Database initialization failed"
    }
}
catch {
    Write-Host "❌ Database initialization failed: $_" -ForegroundColor Red
    Read-Host "Press Enter to exit"
    exit 1
}

Write-Host ""
Write-Host "🌐 Starting development server..." -ForegroundColor Green
Write-Host "Frontend: http://localhost:8000/app.html" -ForegroundColor Cyan
Write-Host "API: http://localhost:8000/index.php" -ForegroundColor Cyan
Write-Host ""
Write-Host "🔐 Admin Credentials:" -ForegroundColor Yellow
Write-Host "Email: admin@taskmaster.com"
Write-Host "Password: admin123456"
Write-Host ""
Write-Host "Press Ctrl+C to stop the server" -ForegroundColor Magenta
Write-Host ""

# Start the server
try {
    & $phpPath -S localhost:8000 -t public
}
catch {
    Write-Host "❌ Failed to start server: $_" -ForegroundColor Red
    Read-Host "Press Enter to exit"
    exit 1
}
