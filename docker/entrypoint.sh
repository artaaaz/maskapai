#!/bin/sh
# ============================================
# Entrypoint Script - UKK Maskapai Penerbangan
# ============================================
# Fungsi:
# - Memastikan folder storage tersedia
# - Memastikan bootstrap/cache tersedia
# - Memperbaiki permission
# - Menjalankan PHP-FPM
# ============================================

set -e

# Ensure storage directories exist
mkdir -p /var/www/storage/framework/cache/data
mkdir -p /var/www/storage/framework/sessions
mkdir -p /var/www/storage/framework/views
mkdir -p /var/www/storage/logs
mkdir -p /var/www/bootstrap/cache

# Fix permissions for Laravel directories
chmod -R 775 /var/www/storage
chmod -R 775 /var/www/bootstrap/cache
chown -R www-data:www-data /var/www/storage
chown -R www-data:www-data /var/www/bootstrap/cache
chown -R www-data:www-data /var/www/public

# Execute the main process (PHP-FPM)
exec php-fpm