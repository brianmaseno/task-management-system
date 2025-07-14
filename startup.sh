#!/bin/bash
# Azure App Service startup script for TaskMaster Pro

echo "Starting TaskMaster Pro on Azure App Service..."

# Set proper working directory
cd /home/site/wwwroot

# Start PHP built-in server on port 8080 with router
echo "Starting PHP server on port 8080 with router..."
php -S 0.0.0.0:8080 router.php

echo "TaskMaster Pro started successfully!"
