#!/bin/bash

composer install
php artisan package:discover --ansi
php -r "file_exists('.env') || copy('.env.example', '.env');"
php artisan key:generate --ansi
php artisan migrate --seed
php artisan serve --host="${API_HOST}" --port="${API_PORT}"
