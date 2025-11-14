#!/bin/bash
source ./.env
echo "Environment:"$APP_ENV

# Step1. dependency resolution
## 1-1. Install php dependent packages
composer install

## 1-2. Install node dependent packages
npm ci

# Step2. Clear Laravel cache
## 2-1. this command
php artisan cache:clear
## 2-2.
php artisan config:clear
## 2-3.
php artisan route:clear
## 2-4.
php artisan view:clear

# Step3. Execute migration
php artisan migrate

# Step4.
## 4-1.
chown -R root:root /var/www/html
## 4-2.
if [ $APP_ENV == "production" ]; then
  npm run prod
else
  npm run dev
fi
## 4-3.
chown -R www-data:1000 /var/www/html

# Step5. Create cache
## 5-1.
php artisan config:cache
## 5-2.
php artisan route:cache
## 5-3.
php artisan view:cache
## 5-4. dump-autoload (phpunit gives error if not executed after cache generation)
composer dump-autoload