#!/bin/bash
# Azure App Service startup script for TaskMaster Pro

echo "Starting TaskMaster Pro on Azure App Service..."

# Set proper working directory
cd /home/site/wwwroot

# Start PHP built-in server on port 8080 (Azure requirement)
echo "Starting PHP server on port 8080..."
php -S 0.0.0.0:8080 -t /home/site/wwwroot

echo "TaskMaster Pro started successfully!"
