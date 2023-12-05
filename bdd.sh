rm -rf migrations
mkdir migrations
php bin/console doctrine:schema:drop --full-database --force
php bin/console make:migration
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load