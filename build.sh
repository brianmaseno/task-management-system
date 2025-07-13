#!/bin/bash
# Install PHP and Composer if not available
php -v || echo "PHP not found"
composer --version || echo "Composer not found"

# Install dependencies
composer install --no-dev --optimize-autoloader

# Verify installation
echo "Dependencies installed successfully"
