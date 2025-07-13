# PowerShell script to download and setup PHP for Windows
Write-Host "🚀 Setting up PHP for TaskMaster Pro..." -ForegroundColor Green

# Create PHP directory
$phpDir = "C:\php"
if (-not (Test-Path $phpDir)) {
    New-Item -ItemType Directory -Path $phpDir -Force
    Write-Host "✅ Created PHP directory: $phpDir" -ForegroundColor Green
}

# Download PHP (you'll need to run this manually or use a package manager)
Write-Host ""
Write-Host "📋 MANUAL INSTALLATION STEPS:" -ForegroundColor Yellow
Write-Host "1. Go to: https://windows.php.net/download/" -ForegroundColor White
Write-Host "2. Download: PHP 8.2 VC15 x64 Thread Safe" -ForegroundColor White
Write-Host "3. Extract to: C:\php\" -ForegroundColor White
Write-Host "4. Add C:\php to your Windows PATH" -ForegroundColor White
Write-Host ""

# Alternative: Use Chocolatey (if available)
Write-Host "🍫 ALTERNATIVE - Using Chocolatey (if installed):" -ForegroundColor Cyan
Write-Host "Run: choco install php" -ForegroundColor White
Write-Host ""

# Alternative: Use Scoop (if available)
Write-Host "🥄 ALTERNATIVE - Using Scoop (if installed):" -ForegroundColor Cyan
Write-Host "Run: scoop install php" -ForegroundColor White
Write-Host ""

Write-Host "💡 EASIEST OPTION - XAMPP:" -ForegroundColor Magenta
Write-Host "1. Download XAMPP from: https://www.apachefriends.org/" -ForegroundColor White
Write-Host "2. Install with default settings" -ForegroundColor White
Write-Host "3. Start Apache from XAMPP Control Panel" -ForegroundColor White
Write-Host "4. Your PHP will be at: C:\xampp\php\php.exe" -ForegroundColor White
