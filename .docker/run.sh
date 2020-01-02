#!/bin/bash

echo "Loading run.sh file..."

echo "Running php artisan config:cache"
php /srv/app/artisan config:cache

echo "Running php artisan view:clear"
php /srv/app/artisan view:clear

echo "Running php artisan migrate..."
php /srv/app/artisan migrate

echo "Running apache in foreground..."
apachectl -DFOREGROUND
