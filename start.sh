#!/bin/bash

# Clear Laravel caches first
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run database migrations
echo "Running migrations..."
php artisan migrate --force

# Optimize Laravel for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting Apache..."
# Start Apache
apache2-foreground