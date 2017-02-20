#!/bin/bash
if [ -f ".env" ] && [ ! -f ".env.bak" ];then
  printf "Backing up environment..."
  # backup local .env
  mv .env .env.bak
  echo "Done."
fi

# copy testing environment
printf "Copying testing environment..."
cp .env.travis .env
echo "Done."

# run commands to start up testing
php artisan key:generate
php artisan env
php artisan migrate:refresh
php vendor/bin/phpunit && php coverage-checker.php tests/clover.xml 4.39

if [ -f ".env.bak" ];then
  printf "Restoring original environment..."
  # restore .env backup
  mv .env.bak .env
  echo "Done."
fi

