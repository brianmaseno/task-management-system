# TaskMaster Pro Setup Guide

## Overview
This is a complete Task Management System with user authentication, session management, and task assignment capabilities.

## Features Implemented
✅ **User Authentication System**
- User registration (anyone can create an account)
- Secure login with password hashing
- Persistent sessions (users stay logged in until logout)
- Admin account with special privileges

✅ **Session Management**
- Server-side session storage
- Automatic session validation
- Secure logout functionality

✅ **Database Integration**
- MongoDB Atlas cloud database
- Proper user data storage
- No dummy data (removed)
- Secure password hashing

✅ **User Management**
- Admin can create, edit, delete users
- Role-based access control (admin/user)
- User profile management

✅ **Task Management**
- Create and assign tasks
- Track task status (Pending, In Progress, Completed)
- Email notifications for task assignments
- Task deadline management

## Default Admin Credentials
- **Email:** admin@taskmaster.com
- **Password:** admin123456

## Installation Steps

### 1. Install PHP (if not installed)
Download and install PHP 8.0+ from: https://www.php.net/downloads.php
- Add PHP to your system PATH
- Ensure the following extensions are enabled:
  - php_mongodb
  - php_curl
  - php_mbstring
  - php_openssl

### 2. Install Composer Dependencies
```bash
cd "c:\Users\brian\Desktop\job application\cyton"
composer install
```

### 3. Configure Database
The `.env` file is already configured with MongoDB Atlas connection.
Database: `task_management`
Collection: `users`, `tasks`

### 4. Initialize Database
```bash
php init.php
```

### 5. Start the Server
```bash
php -S localhost:8000 -t public
```

### 6. Access the Application
Open your browser and go to: http://localhost:8000/app.html

## Key Features

### For Regular Users
- **Register:** Create new account with name, email, password
- **Login:** Secure authentication with session persistence
- **View Tasks:** See assigned tasks and their status
- **Update Status:** Change task status (Pending → In Progress → Completed)
- **Dashboard:** Overview of task statistics

### For Administrators
- **User Management:** Add, edit, delete users
- **Task Management:** Create, assign, edit, delete tasks
- **System Overview:** Monitor all users and tasks
- **Email Notifications:** Automatic emails when tasks are assigned

## Security Features
- Password hashing using PHP's password_hash()
- Session-based authentication
- CSRF protection via sessions
- Input validation and sanitization
- Role-based access control

## File Structure
```
backend/
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php      # Login/Register logic
│   │   ├── UserController.php      # User management
│   │   └── TaskController.php      # Task management
│   ├── Models/
│   │   ├── User.php               # User data model
│   │   └── Task.php               # Task data model
│   ├── Services/
│   │   ├── SessionService.php     # Session management
│   │   └── EmailService.php       # Email notifications
│   └── Database/
│       └── Connection.php         # MongoDB connection
├── routes/
│   └── api.php                    # API routing
public/
├── index.php                      # Main entry point
└── app.html                       # Frontend application
```

## API Endpoints
- `POST /index.php` with action:
  - `register` - Create new user account
  - `login` - Authenticate user
  - `logout` - End user session
  - `checkAuth` - Verify session status
  - `getUsers` - List all users (admin only)
  - `createUser` - Add new user (admin only)
  - `updateUser` - Edit user (admin only)
  - `deleteUser` - Remove user (admin only)
  - `getUserTasks` - Get user's tasks
  - `getAllTasks` - Get all tasks (admin only)
  - `createTask` - Create new task (admin only)
  - `updateTask` - Edit task (admin only)
  - `updateTaskStatus` - Change task status
  - `deleteTask` - Remove task (admin only)

## Database Schema

### Users Collection
```json
{
  "_id": ObjectId,
  "name": "string",
  "email": "string",
  "password": "hashed_string",
  "role": "user|admin",
  "is_active": true,
  "created_at": DateTime,
  "updated_at": DateTime,
  "last_login": DateTime
}
```

### Tasks Collection
```json
{
  "_id": ObjectId,
  "title": "string",
  "description": "string",
  "assignedTo": "user_id",
  "status": "Pending|In Progress|Completed",
  "deadline": DateTime,
  "created_at": DateTime,
  "updated_at": DateTime
}
```

## Troubleshooting

### MongoDB Connection Issues
1. Ensure internet connection is active
2. Verify MongoDB Atlas credentials in `.env`
3. Check if your IP is whitelisted in MongoDB Atlas

### PHP Issues
1. Ensure PHP 8.0+ is installed
2. Check that required extensions are enabled
3. Verify composer dependencies are installed

### Session Issues
1. Ensure session files are writable
2. Check PHP session configuration
3. Clear browser cookies if needed

## Next Steps
1. Install PHP and required extensions
2. Run `composer install` to install dependencies
3. Execute `php init.php` to initialize the database
4. Start the server with `php -S localhost:8000 -t public`
5. Navigate to http://localhost:8000/app.html
6. Login with admin credentials or register a new user

The system is now ready for production use with proper authentication, session management, and user registration capabilities!
