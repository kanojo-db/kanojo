#!/bin/bash
set -e

echo "Deployment started ..."

# Enter maintenance mode or return true
# if already is in maintenance mode
(php artisan down --refresh=60) || true

# Install npm dependencies
npm install ci

# Install composer dependencies
composer install --no-scripts --no-interaction --optimize-autoloader

# Clear the old cache
php artisan clear-compiled

# Compile npm assets
npm run build

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

# Run database migrations
php artisan migrate --force

# Fix permissions since we're not using www-data for deployment
sudo chown -R www-data:www-data .

# Restart the Inertia server
php artisan inertia:stop-ssr

# Restart Horizon
php artisan horizon:terminate

# Exit maintenance mode
php artisan up

# Generate API docs -- This should be done after the app is up, as Scribe
# will make requests to the app to generate the docs.
php artisan scribe:generate

echo "Deployment finished!"
