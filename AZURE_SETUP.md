# Azure Environment Variables Configuration

## Required Environment Variables for Azure App Service

To make the TaskMaster Pro application work on Azure App Service, you need to set the following environment variables in the Azure portal:

### Database Configuration
```
MONGODB_URI=mongodb+srv://brianmayoga:1uZIQRDuX5Km4flb@cyton.etkwfr8.mongodb.net/?retryWrites=true&w=majority&appName=Cyton
DB_CONNECTION=mongodb
DB_HOST=cyton.etkwfr8.mongodb.net
DB_PORT=27017
DB_DATABASE=task_management
DB_USERNAME=brianmayoga
DB_PASSWORD=1uZIQRDuX5Km4flb
```

### Application Configuration
```
APP_NAME=Task Management System
APP_ENV=production
APP_DEBUG=false
APP_URL=https://cyton-gfeyg8hefvf4dpht.canadacentral-01.azurewebsites.net
```

### Email Configuration
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=briancreatives@gmail.com
MAIL_PASSWORD=vadt zjjn rfgc zerf
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=briancreatives@gmail.com
MAIL_FROM_NAME=Task Management System
```

### Security Configuration
```
JWT_SECRET=azure-jwt-secret-key-taskmaster-pro-2025
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
```

## How to Set Environment Variables in Azure:

1. Go to Azure Portal: https://portal.azure.com
2. Navigate to your App Service: `cyton`
3. Go to **Settings** > **Environment Variables**
4. Click **+ Add** for each variable above
5. Set the **Name** and **Value** for each variable
6. Click **Apply** to save changes
7. Restart the App Service

## Testing:

After setting the environment variables:
1. Visit: https://cyton-gfeyg8hefvf4dpht.canadacentral-01.azurewebsites.net/debug.php
2. Check that MongoDB connection shows "✅ MongoDB connection successful!"
3. Try logging in with: admin@taskmaster.com / admin123456

## Alternative Quick Setup:

If you have Azure CLI installed, you can run these commands:

```bash
az webapp config appsettings set --resource-group <your-resource-group> --name cyton --settings \
MONGODB_URI="mongodb+srv://brianmayoga:1uZIQRDuX5Km4flb@cyton.etkwfr8.mongodb.net/?retryWrites=true&w=majority&appName=Cyton" \
APP_ENV="production" \
APP_DEBUG="false" \
MAIL_HOST="smtp.gmail.com" \
MAIL_PORT="587" \
MAIL_USERNAME="briancreatives@gmail.com" \
MAIL_PASSWORD="vadt zjjn rfgc zerf"
```
