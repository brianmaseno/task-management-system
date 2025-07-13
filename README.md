# 🚀 TaskMaster Pro - Advanced Task Management System

A comprehensive task management system with **complete user authentication**, **session management**, and **user registration**. Built with PHP backend, Vue.js frontend, and MongoDB database integration.

## ✨ COMPLETED FEATURES

### 🔐 Authentication & User Management
- ✅ **User Registration**: Anyone can create an account
- ✅ **Secure Login**: Password hashing with PHP password_hash()
- ✅ **Persistent Sessions**: Users stay logged in until they logout
- ✅ **Admin Account**: Default admin (admin@taskmaster.com / admin123456)
- ✅ **Role-Based Access**: Admin vs User permissions
- ✅ **Session Validation**: Server-side session management
- ✅ **No Dummy Data**: Clean database with real user storage

### 👨‍💼 Administrator Features
- ✅ **User Management**: Add, edit, delete users
- ✅ **Task Assignment**: Create and assign tasks with deadlines
- ✅ **Task Management**: Full CRUD operations on tasks
- ✅ **Dashboard**: Overview of all tasks and system statistics
- ✅ **Email Notifications**: Automatic notifications for task assignments

### 👤 User Features
- ✅ **Registration Form**: Create account with name, email, password
- ✅ **Personal Dashboard**: View assigned tasks and statistics
- ✅ **Task Status Updates**: Update status (Pending, In Progress, Completed)
- ✅ **Persistent Login**: No need to login repeatedly
- ✅ **Profile Management**: View personal information

## 🎯 SYSTEM REQUIREMENTS FULFILLED

### ✅ User Authentication
- [x] Users can register new accounts
- [x] Secure login system
- [x] Passwords stored securely (hashed)
- [x] Session management without localStorage dependency
- [x] Admin account with elevated privileges

### ✅ User Registration
- [x] Registration form with validation
- [x] Email uniqueness check
- [x] Password strength requirements
- [x] Automatic user role assignment
- [x] Real-time form validation

### ✅ Task Management
- [x] Admin can create tasks
- [x] Tasks assigned to specific users
- [x] Task status tracking
- [x] Deadline management
- [x] Task editing and deletion

### ✅ Database Integration
- [x] MongoDB cloud database (Atlas)
- [x] User data properly stored
- [x] Task data with relationships
- [x] No dummy data - all real data
- [x] Proper indexing and queries

### ✅ Session Management
- [x] Server-side session storage
- [x] Automatic session validation
- [x] Secure logout functionality
- [x] Session timeout handling
- [x] Cross-request session persistence

## � QUICK START GUIDE

### Prerequisites
- PHP 8.0+ with MongoDB extension
- Internet connection (for MongoDB Atlas)
- Modern web browser

### Installation Steps

1. **Install PHP** (if not installed)
   - Download from https://www.php.net/downloads.php
   - Ensure PHP is in your system PATH
   - Required extensions: mongodb, curl, mbstring, openssl

2. **Install Dependencies**
   ```bash
   cd "c:\Users\brian\Desktop\job application\cyton"
   composer install
   ```

3. **Initialize Database**
   ```bash
   php test-connection.php  # Test connection and create admin
   ```

4. **Start Server**
   ```bash
   php -S localhost:8000 -t public
   ```

5. **Access Application**
   - Open: http://localhost:8000/app.html
   - Admin: admin@taskmaster.com / admin123456
   - Or register a new user account

## 👥 AUTHENTICATION CREDENTIALS

### Default Admin Account
- **Email**: admin@taskmaster.com
- **Password**: admin123456
- **Role**: Administrator
- **Capabilities**: Full system access, user management, task management

### User Registration
- Users can create their own accounts
- Email must be unique
- Password minimum 6 characters
- Automatic role assignment as 'user'
- Persistent sessions until logout

## 📁 UPDATED PROJECT STRUCTURE

```
backend/
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php      # ✅ Login/Register/Logout
│   │   ├── UserController.php      # ✅ User CRUD operations
│   │   └── TaskController.php      # ✅ Task management
│   ├── Models/
│   │   ├── User.php               # ✅ User model with auth
│   │   └── Task.php               # ✅ Task model
│   ├── Services/
│   │   ├── SessionService.php     # ✅ Session management
│   │   └── EmailService.php       # ✅ Email notifications
│   └── Database/
│       └── Connection.php         # ✅ MongoDB connection
public/
├── index.php                      # ✅ API entry with session handling
└── app.html                       # ✅ Frontend with registration
```

## 🔄 API ENDPOINTS (UPDATED)

All endpoints via POST to `index.php`:

### Authentication (Public)
- `register` - Create new user account
- `login` - User authentication
- `logout` - End user session
- `checkAuth` - Verify session status

### User Management (Admin Required)
- `getUsers` - List all users
- `createUser` - Add new user
- `updateUser` - Edit user
- `deleteUser` - Remove user

### Task Management (Login Required)
- `getUserTasks` - Get user's tasks
- `getAllTasks` - Get all tasks (admin)
- `createTask` - Create task (admin)
- `updateTask` - Edit task (admin)
- `updateTaskStatus` - Update status
- `deleteTask` - Remove task (admin)

## 📊 DATABASE SCHEMA (IMPLEMENTED)

### Users Collection
```json
{
  "_id": ObjectId,
  "name": "User Full Name",
  "email": "user@example.com",
  "password": "$2y$10$hashed_password",
  "role": "user|admin",
  "is_active": true,
  "created_at": ISODate,
  "updated_at": ISODate,
  "last_login": ISODate
}
```

### Tasks Collection
```json
{
  "_id": ObjectId,
  "title": "Task Title",
  "description": "Task Description",
  "assignedTo": "user_object_id",
  "status": "Pending|In Progress|Completed",
  "deadline": ISODate,
  "created_at": ISODate,
  "updated_at": ISODate
}
```

## 📧 Email Configuration

To enable email notifications:

1. **Gmail Setup** (recommended):
   - Enable 2-factor authentication
   - Generate an App Password
   - Use the App Password in `MAIL_PASSWORD`

2. **Other SMTP Providers**:
   - Update `MAIL_HOST`, `MAIL_PORT`, and credentials
   - Adjust `MAIL_ENCRYPTION` if needed

## 🎨 UI Features

- **Responsive Design**: Works on desktop, tablet, and mobile
- **Modern Gradients**: Beautiful color schemes and animations
- **Interactive Dashboard**: Real-time statistics and task overview
- **Modal Forms**: Clean, user-friendly forms for data entry
- **Status Indicators**: Visual task status with color coding
- **Smooth Animations**: CSS transitions and hover effects

## 🔒 Security Features

- **Password Hashing**: Secure password storage using PHP password_hash()
- **Input Validation**: Server-side validation for all inputs
- **CORS Headers**: Proper cross-origin resource sharing
- **Error Handling**: Comprehensive error handling and logging
- **SQL Injection Prevention**: NoSQL approach eliminates SQL injection risks

## 🚀 Deployment

### Local Development
The system is ready to run locally with the PHP built-in server.

### Vercel Deployment (Recommended)
1. **Install Vercel CLI**: `npm install -g vercel`
2. **Create Vercel Account**: Sign up at https://vercel.com
3. **Configure Environment Variables**:
   - Go to your Vercel project settings
   - Add environment variables from `.env.example`
   - Set `MONGODB_URI` to your MongoDB Atlas connection string
4. **Deploy**: Run `vercel` in project root
5. **Custom Domain**: Configure custom domain in Vercel dashboard

### Traditional Web Hosting
1. **Upload Files**: Upload all files to your web server
2. **Configure Web Server**: Point document root to `public/` directory
3. **Set Permissions**: Ensure proper file permissions
4. **Update Environment**: Configure production environment variables
5. **SSL Certificate**: Enable HTTPS for production use

### Hosting Recommendations
- **Serverless**: Vercel (recommended), Netlify Functions
- **VPS/Cloud**: DigitalOcean, AWS, Google Cloud, Azure
- **Specialized PHP Hosting**: Heroku, Railway, PlanetScale

## 🧪 Testing

### Manual Testing
1. **Login**: Test both admin and user accounts
2. **User Management**: Create, edit, delete users (admin)
3. **Task Management**: Create, assign, update tasks
4. **Status Updates**: Change task statuses as user
5. **Email Notifications**: Verify email delivery (if configured)

### API Testing
Use tools like Postman or curl to test API endpoints:
```bash
# Test login
curl -X POST http://localhost:8000/index.php \
  -H "Content-Type: application/json" \
  -d '{"action":"login","email":"admin@admin.com","password":"admin123"}'
```

## 🐛 Troubleshooting

### Common Issues

1. **MongoDB Connection Fails**
   - Verify connection string in `.env`
   - Check network connectivity
   - Ensure MongoDB service is running

2. **Email Not Sending**
   - Verify SMTP credentials
   - Check email provider settings
   - Review error logs

3. **Permission Errors**
   - Ensure web server has read/write access to project files
   - Check file permissions (755 for directories, 644 for files)

4. **API Errors**
   - Check PHP error logs
   - Verify all required PHP extensions are installed
   - Ensure proper file structure

## 📈 Performance Optimization

- **Caching**: Implement Redis or Memcached for session storage
- **Database Indexing**: Add MongoDB indexes for frequently queried fields
- **Asset Compression**: Minify CSS and JavaScript files
- **CDN**: Use CDN for static assets in production

## 🔮 Future Enhancements

- **File Attachments**: Add file upload capability to tasks
- **Task Comments**: Add commenting system for task collaboration
- **Time Tracking**: Implement time tracking for tasks
- **Advanced Reporting**: Generate detailed reports and analytics
- **Mobile App**: Native mobile application
- **Real-time Notifications**: WebSocket-based real-time updates
- **Task Templates**: Predefined task templates
- **Project Management**: Group tasks into projects

## 📝 License

This project is open source and available under the [MIT License](LICENSE).

## 👨‍💻 Author

**Brian Mayoga**  
Software Engineer Intern Candidate  
Email: brianmayoga@example.com

## 🤝 Contributing

1. Fork the project
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📞 Support

For support and questions:
- Create an issue in the project repository
- Contact the development team
- Check the troubleshooting section above

---

**Built with ❤️ for Cytonn Investments Software Engineering Internship Challenge**
