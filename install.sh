#!/usr/bin/env bash
composer install
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console cache:clear
