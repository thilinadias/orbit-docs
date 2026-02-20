#!/bin/bash
set -e

MARKER_FILE="/var/www/storage/.orbitdocs_initialized"

# ─────────────────────────────────────────────────────────────
# STEP 1: Seed app volume from Docker image (first run only)
# Assets (public/build, vendor/) are baked into the image.
# ─────────────────────────────────────────────────────────────
if [ ! -f /var/www/artisan ]; then
    echo "[OrbitDocs] First run detected — seeding app volume from image..."
    cp -a /var/www-image/. /var/www/
    echo "[OrbitDocs] App volume seeded."
fi

# ─────────────────────────────────────────────────────────────
# STEP 2: Ensure .env exists with correct values
# ─────────────────────────────────────────────────────────────
if [ ! -f /var/www/.env ]; then
    echo "[OrbitDocs] Creating .env from example..."
    cp /var/www/.env.example /var/www/.env
fi

dos2unix /var/www/.env 2>/dev/null || true

# Fix default dev values to Docker-friendly values
sed -i 's|DB_HOST=127.0.0.1|DB_HOST=db|g'            /var/www/.env
sed -i 's|DB_DATABASE=laravel|DB_DATABASE=orbitdocs|g' /var/www/.env
sed -i 's|DB_USERNAME=root|DB_USERNAME=orbitdocs|g'    /var/www/.env
sed -i 's|^DB_PASSWORD=$|DB_PASSWORD=secret|g'         /var/www/.env
sed -i 's|REDIS_HOST=127.0.0.1|REDIS_HOST=redis|g'    /var/www/.env
sed -i 's|APP_URL=http://localhost|APP_URL=http://'"${APP_DOMAIN:-localhost}"'|g' /var/www/.env

# ─────────────────────────────────────────────────────────────
# STEP 3: Install vendor if somehow missing (safety net)
# Normally vendor/ is baked into the image.
# ─────────────────────────────────────────────────────────────
if [ ! -d /var/www/vendor ]; then
    echo "[OrbitDocs] vendor/ missing — running composer install..."
    cd /var/www && composer install --no-interaction --optimize-autoloader --no-dev
fi

# ─────────────────────────────────────────────────────────────
# STEP 4: Generate app key if missing
# ─────────────────────────────────────────────────────────────
if ! grep -q "^APP_KEY=base64" /var/www/.env; then
    echo "[OrbitDocs] Generating APP_KEY..."
    cd /var/www && php artisan key:generate --force
fi

# ─────────────────────────────────────────────────────────────
# STEP 5: Fix permissions
# ─────────────────────────────────────────────────────────────
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache || true
chown www-data:www-data /var/www/.env && chmod 664 /var/www/.env || true

# ─────────────────────────────────────────────────────────────
# STEP 6: Run DB migrations in background (non-blocking)
# This means php-fpm starts immediately while migrations run.
# The app's GUI installer handles fresh installs.
# On subsequent deploys, only pending migrations are applied.
# ─────────────────────────────────────────────────────────────
run_migrations() {
    echo "[OrbitDocs] Waiting for database..."
    local max_wait=60
    local count=0
    until cd /var/www && php artisan db:show --json > /dev/null 2>&1; do
        count=$((count + 1))
        if [ "$count" -ge "$max_wait" ]; then
            echo "[OrbitDocs] Database did not become ready after ${max_wait}s. Skipping auto-migration."
            return 1
        fi
        sleep 2
    done

    echo "[OrbitDocs] Database ready. Running pending migrations..."
    cd /var/www && php artisan migrate --force 2>&1 && \
        echo "[OrbitDocs] Migrations complete." || \
        echo "[OrbitDocs] WARNING: Migrations failed. Check logs."

    # Create storage symlink
    if [ ! -L /var/www/public/storage ]; then
        cd /var/www && php artisan storage:link --force 2>/dev/null || true
    fi

    # Mark as initialized
    touch "$MARKER_FILE"
}

# Run migrations in background so php-fpm starts immediately
if [ ! -f "$MARKER_FILE" ]; then
    echo "[OrbitDocs] Running first-time setup in background..."
    run_migrations &
else
    # On subsequent deploys: run migrations quickly in background
    echo "[OrbitDocs] Running any pending migrations in background..."
    run_migrations &
fi

echo "[OrbitDocs] Starting php-fpm..."
exec "$@"
