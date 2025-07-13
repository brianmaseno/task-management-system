# 🚀 TaskMaster Pro - Terminal-Only Setup Guide

## Quick Start (No XAMPP GUI Required)

### Option 1: Using Batch Script (Easiest)
1. **Double-click** `start-simple.bat` 
   - OR run in PowerShell: `.\start-simple.bat`

### Option 2: Manual Terminal Commands
```bash
# Navigate to project directory
cd "c:\Users\brian\Desktop\job application\cyton"

# Start server using XAMPP PHP
C:\xampp\php\php.exe -S localhost:8000 -t public
```

### Option 3: PowerShell Script
```powershell
# Run the PowerShell script
powershell -ExecutionPolicy Bypass -File start-server.ps1
```

## 🌐 Access Your Application

Once the server starts, open your browser and go to:
- **Frontend**: http://localhost:8000/app.html
- **API**: http://localhost:8000/index.php

## 🔐 Login Credentials

### Admin Account
- **Email**: admin@taskmaster.com
- **Password**: admin123456

### User Registration
- Users can register their own accounts through the frontend

## 📁 What's Running

- **Backend**: PHP API server serving from `public/` directory
- **Frontend**: Vue.js single-page application (`app.html`)
- **Database**: MongoDB Atlas (cloud-hosted, no local setup needed)

## 🛠️ Requirements

- **XAMPP installed** (for PHP executable)
- **Internet connection** (for MongoDB Atlas)
- **Modern web browser**

## 🔄 Development Workflow

1. **Start Server**: Run one of the startup scripts
2. **Open Browser**: Go to http://localhost:8000/app.html
3. **Login**: Use admin credentials or register new user
4. **Develop**: Edit files and refresh browser to see changes
5. **Stop Server**: Press `Ctrl+C` in terminal

## 📋 Features Available

✅ User Registration & Login  
✅ Admin Dashboard  
✅ Task Management  
✅ User Management (Admin)  
✅ System Logs (Admin)  
✅ Responsive Design  
✅ Session Management  

## 🐛 Troubleshooting

### Server Won't Start
- Ensure XAMPP is installed at `C:\xampp\`
- Check if port 8000 is available
- Try a different port: `php.exe -S localhost:3000 -t public`

### Database Connection Issues
- Check internet connection
- Verify `.env` file has correct MongoDB URI

### Login Issues
- Use exact credentials: admin@taskmaster.com / admin123456
- Clear browser cache if needed

## 🎯 No XAMPP Control Panel Needed!

This setup bypasses the XAMPP Control Panel entirely:
- ✅ **No Apache needed** - Uses PHP built-in server
- ✅ **No MySQL needed** - Uses MongoDB Atlas
- ✅ **Just PHP executable** - Runs directly from terminal

---

**Your TaskMaster Pro is ready to run from terminal only!** 🎉
