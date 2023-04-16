#!/bin/bash
set -e

echo "Deployment started ..."

# Enter maintenance mode or return true
# if already is in maintenance mode
(php artisan down) || true

# Pull the latest version of the app
git pull origin main

# Install composer dependencies
composer install --no-dev --no-scripts --no-interaction --prefer-dist --optimize-autoloader

# Clear the old cache
php artisan clear-compiled

# Recreate cache
php artisan optimize

# Cache config
php artisan config:cache

# Cache events
php artisan event:cache

# Generate API docs
php artisan scribe:generate

# Generate routes
php artisan ziggy:generate

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Compile npm assets
npm run build

# Run database migrations
php artisan migrate --force

# Fix permissions since we're not using www-data for deployment
sudo chown -R www-data:www-data .

# Exit maintenance mode
php artisan up

echo "Deployment finished!"