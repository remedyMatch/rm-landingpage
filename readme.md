# RemedyMatch.io Website

This website is based on Symfony 4.

## Getting started
1. Clone this repository
2. run `composer install`
3. Create a `.env.local` by running `cp .env .env.local` and fill missing values.
4. By default Slack, Keycloak and GoogleRecaptcha are mocked in "services_dev.yaml". Remove this configuration to run on the systems configured in your environment
5. Create your database schema by running `php bin/console doctrine:schema:create`
5. Create your database schema for the test environment by running `php bin/console doctrine:schema:create --env test`
7. Run unit tests `bin/phpunit --testdox`
8. Run cs fixer `vendor/bin/php-cs-fixer fix` optional `--dry-run`