#!/usr/bin/env bash
composer install
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console cache:clear
VERSION="$(cat .git/ORIG_HEAD)"
sed -i -e "/ASSET_VERSION=/s/=.*/=${VERSION:0:5}/" ".env.local"