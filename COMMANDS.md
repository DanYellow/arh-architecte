- start server : `php -S localhost:8000 -t public`
- run sql : `php bin/console dbal:run-sql 'SELECT * FROM foo'`
- clear cache : `php bin/console cache:clear --env=dev`
- start server : `symfony server:start`
- update db : `php bin/console doctrine:schema:update --force`
- create / update entity : `php bin/console make:entity`


- Back to dev :
  - `composer install`

https://symfony.com/doc/current/deployment.html