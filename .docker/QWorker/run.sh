#!/bin/bash

echo "Running worker run.sh file..."

echo "Running php artisan config:cache"
php /srv/app/artisan config:cache

echo "Running php artisan migrate"
php /srv/app/artisan migrate

echo "Running cron service..."
service cron start

echo "Starting supervisor..."
supervisord -c /etc/supervisor/supervisord.conf
supervisorctl reread
supervisorctl update

supervisorctl start laravel-sync-worker:*

echo "Finishing supervisor..."