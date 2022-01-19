# LeBoncoin

### Télécharger le projet

- git clone...
- composer update

### Start le projet

- symfony serve -d
- docker-compose up

### Pour stop le projet

- ctrl+C (stop le docker)
- symfony serve:stop

### Pour regarder dans la bdd
- symfony console doctrine:query:sql 'SELECT * FROM user'
- symfony console doctrine:query:sql 'SELECT * FROM ad'
- symfony console doctrine:query:sql 'SELECT * FROM tag'
