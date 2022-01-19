# LeBoncoin

- composer update
- symfony serve -d
- docker-compose up

### Pour regarder dans la bdd
- symfony console doctrine:query:sql 'SELECT * FROM user'
- symfony console doctrine:query:sql 'SELECT * FROM ad'
- symfony console doctrine:query:sql 'SELECT * FROM tag'
