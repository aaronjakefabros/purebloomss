#!/bin/bash

echo "=== Starting Pureblooms Deployment ==="

# 1. Run Migrations (Ito ang mag-create ng tables)
echo "Running database migrations..."
php artisan migrate --force

# 2. Start Apache Server
echo "Starting Apache..."
apache2-foreground