# LeBoncoin

### Télécharger le projet

- git clone...
- composer update

### Start le projet

- symfony serve -d
- docker-compose up -d
- symfony console make:migration
- symfony console doctrine:migrations:migrate
- symfony console doctrine:fixtures:load

### Pour stop le projet

- docker-compose down
- symfony serve:stop

### Pour regarder dans la bdd
- symfony console doctrine:query:sql 'SELECT * FROM user'
- symfony console doctrine:query:sql 'SELECT * FROM ad'
- symfony console doctrine:query:sql 'SELECT * FROM tag'

### Problème de chargement des images
- Désactiver AdBlock car il bloque toute les images avec un mot comme "ad" 
