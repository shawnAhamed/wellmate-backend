#!/usr/bin/env bash
# WellMate Backend - Mac Setup Script
# Usage: chmod +x setup-mac.sh && ./setup-mac.sh

set -e

echo "==> WellMate Backend Setup (macOS)"

# 1. Check PHP
if ! command -v php &> /dev/null; then
    echo "PHP not found. Install it with: brew install php"
    exit 1
fi
echo "PHP found: $(php -v | head -n 1)"

# 2. Check Composer
if ! command -v composer &> /dev/null; then
    echo "Composer not found. Install it from https://getcomposer.org/download or: brew install composer"
    exit 1
fi
echo "Composer found: $(composer --version)"

# 3. Install dependencies
echo "==> Installing PHP dependencies..."
composer install

# 4. Copy .env
if [ ! -f .env ]; then
    cp .env.example .env
    echo "==> .env created from .env.example"
else
    echo "==> .env already exists, skipping copy"
fi

# 5. Generate app key
php artisan key:generate

echo ""
echo "==> Next steps:"
echo "1. Make sure MySQL is running (e.g. via XAMPP, or: brew services start mysql)"
echo "2. Create the database: mysql -u root -e 'CREATE DATABASE wellmate;'"
echo "3. Update .env DB_USERNAME/DB_PASSWORD if not using default root with no password"
echo "4. Run: php artisan migrate --seed"
echo "5. Run: php artisan serve"
echo ""
echo "API will be available at http://localhost:8000"
