#!/bin/sh
set -e

# First run composer install
cd /var/www/symfony  # adjust this path to your project directory
composer install

# Then exec the container's main process (what's set as CMD in the Dockerfile)
exec "$@"