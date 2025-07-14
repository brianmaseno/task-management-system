<?php
// Azure Environment Configuration - HARDCODED VALUES
// This file provides all necessary environment variables with hardcoded values

// Database Configuration - HARDCODED
$_ENV['DB_CONNECTION'] = 'mongodb';
$_ENV['DB_HOST'] = 'cyton.etkwfr8.mongodb.net';
$_ENV['DB_PORT'] = '27017';
$_ENV['DB_DATABASE'] = 'task_management';
$_ENV['DB_USERNAME'] = 'brianmayoga';
$_ENV['DB_PASSWORD'] = '1uZIQRDuX5Km4flb';

// MongoDB Connection String - HARDCODED
$_ENV['MONGODB_URI'] = 'mongodb+srv://brianmayoga:1uZIQRDuX5Km4flb@cyton.etkwfr8.mongodb.net/?retryWrites=true&w=majority&appName=Cyton';

// Application Configuration - HARDCODED
$_ENV['APP_NAME'] = 'TaskMaster Pro';
$_ENV['APP_ENV'] = 'production';
$_ENV['APP_DEBUG'] = 'false';
$_ENV['APP_URL'] = 'https://cyton-gfeyg8hefvf4dpht.canadacentral-01.azurewebsites.net';

// Mail Configuration - HARDCODED
$_ENV['MAIL_MAILER'] = 'smtp';
$_ENV['MAIL_HOST'] = 'smtp.gmail.com';
$_ENV['MAIL_PORT'] = '587';
$_ENV['MAIL_USERNAME'] = 'briancreatives@gmail.com';
$_ENV['MAIL_PASSWORD'] = 'vadt zjjn rfgc zerf';
$_ENV['MAIL_ENCRYPTION'] = 'tls';
$_ENV['MAIL_FROM_ADDRESS'] = 'briancreatives@gmail.com';
$_ENV['MAIL_FROM_NAME'] = 'TaskMaster Pro System';

// JWT Secret - HARDCODED
$_ENV['JWT_SECRET'] = 'taskmaster-pro-azure-jwt-secret-2025-hardcoded';

// Session Configuration - HARDCODED
$_ENV['SESSION_LIFETIME'] = '120';
$_ENV['SESSION_ENCRYPT'] = 'false';
$_ENV['SESSION_PATH'] = '/';
$_ENV['SESSION_DOMAIN'] = 'null';

// Additional Azure-specific settings
$_ENV['WEBSITES_ENABLE_APP_SERVICE_STORAGE'] = 'false';
$_ENV['WEBSITES_CONTAINER_START_TIME_LIMIT'] = '1800';

echo "<!-- Azure Environment Variables Loaded with Hardcoded Values -->\n";
?>
