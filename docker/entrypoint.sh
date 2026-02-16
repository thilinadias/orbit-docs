#!/bin/bash
set -e

# Copy .env if not exists (for first run with volume)
if [ ! -f .env ]; then
    echo "Creating .env file from example..."
    cp .env.example .env
fi

# Install Composer dependencies if missing
if [ ! -d "vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --optimize-autoloader
fi

# Generate key if missing or empty
if [ -f .env ] && ! grep -q "^APP_KEY=base64" .env; then
    echo "Generating Application Key..."
    php artisan key:generate
fi

# Wait for MySQL (simple sleep to avoid initial connection failure)
echo "Waiting for Database..."
sleep 10

# Run Migrations
echo "Running Migrations..."
php artisan migrate --force

# Create Storage Link
if [ ! -L "public/storage" ]; then
    echo "Creating Storage Link..."
    php artisan storage:link
fi

# Install NPM dependencies and build if missing
if [ ! -d "node_modules" ] || [ ! -d "public/build" ]; then
    echo "Building Frontend Assets..."
    npm install
    npm run build
fi

# Cache configuration if .env is valid (skip if it's the default dummy env)
# php artisan config:cache

echo "OrbitDocs is ready."
exec "$@"
