<?php
// Azure Environment Configuration
// This file loads environment variables from Azure App Service settings

// Azure App Service automatically provides environment variables
// We need to map them to our expected variable names

// If running in Azure, environment variables are available in $_ENV and getenv()
if (!file_exists(__DIR__ . '/.env')) {
    // Set default environment variables for Azure deployment
    $_ENV['DB_CONNECTION'] = $_ENV['DB_CONNECTION'] ?? 'mongodb';
    $_ENV['DB_HOST'] = $_ENV['DB_HOST'] ?? 'cyton.etkwfr8.mongodb.net';
    $_ENV['DB_PORT'] = $_ENV['DB_PORT'] ?? '27017';
    $_ENV['DB_DATABASE'] = $_ENV['DB_DATABASE'] ?? 'task_management';
    $_ENV['DB_USERNAME'] = $_ENV['DB_USERNAME'] ?? 'brianmayoga';
    $_ENV['DB_PASSWORD'] = $_ENV['DB_PASSWORD'] ?? '1uZIQRDuX5Km4flb';
    
    $_ENV['MONGODB_URI'] = $_ENV['MONGODB_URI'] ?? 'mongodb+srv://brianmayoga:1uZIQRDuX5Km4flb@cyton.etkwfr8.mongodb.net/?retryWrites=true&w=majority&appName=Cyton';
    
    $_ENV['APP_NAME'] = $_ENV['APP_NAME'] ?? 'Task Management System';
    $_ENV['APP_ENV'] = $_ENV['APP_ENV'] ?? 'production';
    $_ENV['APP_DEBUG'] = $_ENV['APP_DEBUG'] ?? 'false';
    $_ENV['APP_URL'] = $_ENV['APP_URL'] ?? 'https://cyton-gfeyg8hefvf4dpht.canadacentral-01.azurewebsites.net';
    
    $_ENV['MAIL_MAILER'] = $_ENV['MAIL_MAILER'] ?? 'smtp';
    $_ENV['MAIL_HOST'] = $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com';
    $_ENV['MAIL_PORT'] = $_ENV['MAIL_PORT'] ?? '587';
    $_ENV['MAIL_USERNAME'] = $_ENV['MAIL_USERNAME'] ?? 'briancreatives@gmail.com';
    $_ENV['MAIL_PASSWORD'] = $_ENV['MAIL_PASSWORD'] ?? 'vadt zjjn rfgc zerf';
    $_ENV['MAIL_ENCRYPTION'] = $_ENV['MAIL_ENCRYPTION'] ?? 'tls';
    $_ENV['MAIL_FROM_ADDRESS'] = $_ENV['MAIL_FROM_ADDRESS'] ?? 'briancreatives@gmail.com';
    $_ENV['MAIL_FROM_NAME'] = $_ENV['MAIL_FROM_NAME'] ?? 'Task Management System';
    
    $_ENV['JWT_SECRET'] = $_ENV['JWT_SECRET'] ?? 'azure-jwt-secret-key-taskmaster-pro-2025';
    $_ENV['SESSION_LIFETIME'] = $_ENV['SESSION_LIFETIME'] ?? '120';
    $_ENV['SESSION_ENCRYPT'] = $_ENV['SESSION_ENCRYPT'] ?? 'false';
    $_ENV['SESSION_PATH'] = $_ENV['SESSION_PATH'] ?? '/';
    $_ENV['SESSION_DOMAIN'] = $_ENV['SESSION_DOMAIN'] ?? 'null';
}
?>
