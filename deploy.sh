#!/bin/bash

# Company Screener Deployment Script
# Run this script on your Digital Ocean droplet after initial server setup

set -e

echo "🚀 Starting Company Screener deployment..."

# Variables - UPDATE THESE
DOMAIN="yourdomain.com"
APP_DIR="/var/www/html"
DB_PASSWORD="your_secure_mysql_password"
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

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    print_error "Please run this script as root (use sudo)"
    exit 1
fi

print_status "Updating system packages..."
apt update && apt upgrade -y

print_status "Installing required packages..."
apt install -y software-properties-common curl wget git unzip supervisor

# Install PHP 8.2
print_status "Installing PHP 8.2..."
add-apt-repository ppa:ondrej/php -y
apt update
apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-common php8.2-mysql php8.2-sqlite3 \
    php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath \
    php8.2-intl php8.2-opcache

# Install Nginx
print_status "Installing Nginx..."
apt install -y nginx

# Install Node.js 20
print_status "Installing Node.js 20..."
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs

# Install Composer
print_status "Installing Composer..."
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install MySQL (optional - comment out if using SQLite)
print_status "Installing MySQL..."
apt install -y mysql-server
mysql -e "CREATE DATABASE company_screener;"
mysql -e "CREATE USER 'screener_user'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';"
mysql -e "GRANT ALL PRIVILEGES ON company_screener.* TO 'screener_user'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

# Create application directory
print_status "Setting up application directory..."
mkdir -p $APP_DIR
cd $APP_DIR

# Clone repository (you'll need to set this up)
print_warning "You need to upload your code to $APP_DIR"
print_warning "Either use git clone or upload via SFTP/rsync"

# Set permissions
print_status "Setting permissions..."
chown -R $APP_USER:$APP_USER $APP_DIR
chmod -R 755 $APP_DIR
chmod -R 775 $APP_DIR/storage
chmod -R 775 $APP_DIR/bootstrap/cache

print_status "✅ Basic server setup complete!"
print_warning "Next steps:"
echo "1. Upload your application code to $APP_DIR"
echo "2. Copy .env.production to .env and configure it"
echo "3. Run the application setup commands (see setup-app.sh)"
echo "4. Configure Nginx virtual host"
echo "5. Install SSL certificate"
