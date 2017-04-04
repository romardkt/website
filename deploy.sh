#!/bin/bash

# composer stuff
php composer.phar install

# maintenance
php artisan down

# build the js/css
yarn
gulp --production

# clear and optimize the Laravel app
php artisan clear-compiled
php artisan route:cache
php artisan config:cache
php artisan cache:clear
php artisan view:clear
php artisan optimize
php artisan migrate --force

# all done
php artisan up
