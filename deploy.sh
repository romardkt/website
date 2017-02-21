#!/bin/bash

# maintenance
php artisan down

# build the js/css
yarn
gulp

# clear and optimize the Laravel app
php artisan clear-compiled
php artisan route:cache
php artisan config:cache
php artisan cache:clear
php artisan view:clear
php artisan optimize
php artisan migrate

# all done
php artisan up
