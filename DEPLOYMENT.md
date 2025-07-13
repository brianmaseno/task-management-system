# 🚀 TaskFlow Deployment Guide

## Quick Start (Demo Mode)

### Option 1: Direct HTML Demo
1. Open `index.html` in any modern web browser
2. Use demo credentials:
   - **Admin**: admin@admin.com / admin123
   - **User**: user@user.com / user123

### Option 2: Local PHP Server (Full Features)

**Prerequisites:**
- PHP 8.0+
- MongoDB (local or cloud)

**Steps:**
1. **Start PHP Server:**
   ```bash
   php -S localhost:8000 -t public
   ```

2. **Initialize Database:**
   ```bash
   php init.php
   ```

3. **Access Application:**
   - Demo: http://localhost:8000/index.html
   - Full App: http://localhost:8000/app.html

## Production Deployment

### Method 1: Shared Hosting
1. Upload all files to your hosting account
2. Point domain to `public/` directory
3. Configure `.env` with production settings
4. Run `php init.php` once

### Method 2: VPS/Cloud Hosting

**For Ubuntu/Debian:**
```bash
# Install dependencies
sudo apt update
sudo apt install php8.1 php8.1-fpm nginx mongodb

# Configure Nginx
sudo nano /etc/nginx/sites-available/taskflow

# Nginx configuration:
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/taskflow/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}

# Enable site
sudo ln -s /etc/nginx/sites-available/taskflow /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### Method 3: Docker Deployment

Create `Dockerfile`:
```dockerfile
FROM php:8.1-apache

# Install MongoDB extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Copy application
COPY . /var/www/html/
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html
RUN a2enmod rewrite

EXPOSE 80
```

`docker-compose.yml`:
```yaml
version: '3.8'
services:
  taskflow:
    build: .
    ports:
      - "8000:80"
    environment:
      - MONGODB_URI=mongodb://mongo:27017/taskflow
    depends_on:
      - mongo
  
  mongo:
    image: mongo:5.0
    ports:
      - "27017:27017"
    volumes:
      - mongo_data:/data/db

volumes:
  mongo_data:
```

Run with:
```bash
docker-compose up -d
```

## Environment Configuration

### Production .env
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

MONGODB_URI=your-production-mongodb-uri

MAIL_HOST=smtp.your-provider.com
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-secure-password
MAIL_FROM_ADDRESS=noreply@your-domain.com
```

## Security Checklist

- [ ] Change default admin password
- [ ] Configure HTTPS/SSL certificate
- [ ] Set strong MongoDB authentication
- [ ] Configure proper file permissions (644 for files, 755 for directories)
- [ ] Enable PHP security settings
- [ ] Configure rate limiting
- [ ] Set up regular backups

## Performance Optimization

### PHP Configuration
```ini
; php.ini optimizations
memory_limit = 256M
max_execution_time = 60
upload_max_filesize = 10M
post_max_size = 10M
opcache.enable = 1
opcache.memory_consumption = 128
```

### MongoDB Optimization
```javascript
// Create indexes for better performance
db.users.createIndex({"email": 1}, {unique: true})
db.tasks.createIndex({"assignedTo": 1})
db.tasks.createIndex({"status": 1})
db.tasks.createIndex({"deadline": 1})
```

## Monitoring & Maintenance

### Log Files
- PHP errors: `/var/log/php_errors.log`
- MongoDB logs: `/var/log/mongodb/mongod.log`
- Application logs: Check your hosting provider

### Regular Tasks
```bash
# Weekly backup
mongodump --uri="your-mongodb-uri" --out ./backups/$(date +%Y%m%d)

# Clean old logs
find ./logs -name "*.log" -mtime +30 -delete

# Update dependencies (if using Composer)
composer update --no-dev --optimize-autoloader
```

## Troubleshooting

### Common Issues

**1. MongoDB Connection Failed**
- Verify connection string in `.env`
- Check network connectivity
- Ensure MongoDB service is running

**2. Email Not Sending**
- Verify SMTP credentials
- Check firewall settings
- Test with telnet: `telnet smtp.gmail.com 587`

**3. Permission Errors**
- Set correct file permissions:
  ```bash
  chmod 644 .env
  chmod -R 755 public/
  chmod -R 775 data/
  ```

**4. PHP Errors**
- Enable error reporting in development:
  ```php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  ```

### Support Contacts
- Technical Issues: Create GitHub issue
- Security Concerns: Contact administrator
- Feature Requests: Submit pull request

## Hosting Recommendations

### Shared Hosting
- **Hostinger**: PHP 8.1, MongoDB support
- **SiteGround**: Good performance, security
- **A2 Hosting**: Developer-friendly features

### VPS/Cloud
- **DigitalOcean**: $5/month droplet sufficient
- **Linode**: Excellent documentation
- **AWS**: Elastic scaling options
- **Google Cloud**: MongoDB Atlas integration

### Specialized PHP Hosting
- **Laravel Forge**: Automated deployments
- **Cloudways**: Managed cloud hosting
- **PlanetScale**: Database as a service

## SSL Certificate Setup

### Let's Encrypt (Free)
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com
```

### CloudFlare (Free)
1. Add domain to CloudFlare
2. Update nameservers
3. Enable SSL in CloudFlare dashboard

## Backup Strategy

### Automated Backup Script
```bash
#!/bin/bash
# backup.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/taskflow_$DATE"

# MongoDB backup
mongodump --uri="$MONGODB_URI" --out "$BACKUP_DIR/db"

# Files backup
tar -czf "$BACKUP_DIR/files.tar.gz" /var/www/taskflow

# Upload to cloud storage (optional)
# aws s3 cp "$BACKUP_DIR" s3://your-bucket/backups/ --recursive

echo "Backup completed: $BACKUP_DIR"
```

### Cron Job (Daily backups)
```bash
# crontab -e
0 2 * * * /path/to/backup.sh >> /var/log/backup.log 2>&1
```

---

**🎯 For Cytonn Investments Internship Challenge**

This deployment guide ensures your TaskFlow application can be successfully deployed and maintained in any environment, from development to production.

**Built with ❤️ by Brian Mayoga**
