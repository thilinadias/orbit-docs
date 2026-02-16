#!/bin/bash
set -e

# Copy .env if not exists (for first run with volume)
if [ ! -f .env ]; then
    echo "Creating .env file from example..."
    cp .env.example .env
fi

# Ensure permissions are correct (in case volume mount changed them)
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Cache configuration if .env is valid (skip if it's the default dummy env)
# php artisan config:cache

echo "OrbitDocs is ready."
exec "$@"
