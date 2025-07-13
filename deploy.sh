#!/bin/bash

# Azure deployment script for TaskMaster Pro
echo "Starting Azure deployment..."

# Install Composer dependencies
echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Set proper permissions
echo "Setting file permissions..."
chmod -R 755 public/
chmod -R 755 backend/

# Create logs directory if it doesn't exist
mkdir -p logs
chmod 755 logs

echo "Azure deployment completed successfully!"
