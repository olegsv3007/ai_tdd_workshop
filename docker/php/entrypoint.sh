#!/bin/bash
set -e

cd /var/www/symfony

# Check if Symfony is already installed
if [ ! -f "composer.json" ]; then
    echo "No composer.json found. Creating Symfony project..."
    # Clean the directory first (keeping the current directory)
    find . -mindepth 1 -delete 2>/dev/null || true
    
    # Create a new Symfony project
    composer create-project symfony/skeleton . --quiet --no-interaction
    
    # Install additional packages
    composer require symfony/webapp-pack --quiet --no-interaction
    
    # Create .env.local file
    echo 'DATABASE_URL="mysql://symfony:symfony@mysql:3306/symfony?serverVersion=8&charset=utf8mb4"' > .env.local
fi

# Make sure the var directory exists and is writable
mkdir -p var
chmod -R 777 var/

# Start PHP-FPM
exec php-fpm
