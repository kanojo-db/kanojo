#!/bin/bash
set -e

echo "Deployment started ..."

# Enter maintenance mode or return true
# if already is in maintenance mode
(php artisan down --refresh=60) || true

# For safety, stash any changes
git stash

# Pull the latest version of the app
git pull origin main

# Install npm dependencies
npm install ci

# Install composer dependencies
composer install --no-dev --no-scripts --no-interaction --optimize-autoloader

# Clear the old cache
php artisan clear-compiled

# Generate API docs
php artisan scribe:generate

# Generate routes
php artisan ziggy:generate

# Recreate cache
php artisan optimize

# Cache config
php artisan config:cache

# Cache events
php artisan event:cache

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

# Restart queue workers to pick up new code
php artisan queue:restart

# Restart the Inertia server
# sudo supervisorctl restart inertiassr

# Exit maintenance mode
php artisan up

echo "Deployment finished!"