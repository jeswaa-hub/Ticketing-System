#!/usr/bin/env bash
# exit on error
set -e

echo "Running Composer..."
composer install --no-dev --optimize-autoloader

echo "Running NPM..."
npm install
npm run build

echo "Running Migrations..."
php artisan migrate --force

echo "Deployment finished!"