#!/bin/bash

# Application Setup Script
# Run this after uploading your code to the server

set -e

APP_DIR="/var/www/html"
APP_USER="www-data"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

cd $APP_DIR

print_status "Installing PHP dependencies..."
sudo -u $APP_USER composer install --no-dev --optimize-autoloader

print_status "Installing Node.js dependencies..."
sudo -u $APP_USER npm install

print_status "Building frontend assets..."
sudo -u $APP_USER npm run build

print_status "Setting up Laravel..."

# Generate application key
print_status "Generating application key..."
sudo -u $APP_USER php artisan key:generate

# Run database migrations
print_status "Running database migrations..."
sudo -u $APP_USER php artisan migrate --force

# Seed job types
print_status "Seeding job types..."
sudo -u $APP_USER php artisan db:seed --class=JobTypeSeeder

# Clear and cache configuration
print_status "Optimizing Laravel..."
sudo -u $APP_USER php artisan config:cache
sudo -u $APP_USER php artisan route:cache
sudo -u $APP_USER php artisan view:cache

# Set final permissions
print_status "Setting final permissions..."
chown -R $APP_USER:$APP_USER $APP_DIR
chmod -R 755 $APP_DIR
chmod -R 775 $APP_DIR/storage
chmod -R 775 $APP_DIR/bootstrap/cache

# Create SQLite database if using SQLite
if grep -q "DB_CONNECTION=sqlite" .env; then
    print_status "Setting up SQLite database..."
    touch database/database.sqlite
    chown $APP_USER:$APP_USER database/database.sqlite
    chmod 664 database/database.sqlite
fi

print_status "✅ Application setup complete!"
print_warning "Don't forget to:"
echo "1. Configure your .env file with API keys"
echo "2. Set up Nginx virtual host"
echo "3. Install SSL certificate"
echo "4. Start queue workers with Supervisor"
