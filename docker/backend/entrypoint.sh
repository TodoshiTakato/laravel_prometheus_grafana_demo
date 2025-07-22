#!/bin/sh

# Create Laravel storage directory structure if it doesn't exist
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Set proper permissions
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Install composer dependencies if vendor directory doesn't exist
if [ ! -d "/var/www/html/vendor" ]; then
    composer install --no-interaction --no-progress
fi

# Start supervisor
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf 