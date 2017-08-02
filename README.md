# test-idix
Pour installer le projet en local: 
- git clone https://github.com/manolo317/test-idix.git
- $ (master) git pull (je récupère la dernière version de master)
- $ (master) php bin/console server:run (je démarre le serveur)
- $ (master) composer update (je met à jour les dépendances)
- je met à jour les paramètres de mon fichier parameters.yml
- $ (master) php app/console doctrine:database:create (création base de données)
- $ (master) php app/console doctrine:schema:update --force (création tables)

Routes:
- "/films": liste des films
- "/films/{id}": détails film
- "admin/film": espace administration des films
- "admin/character": espace administration des films

Pour importer les films et les personnage de Swapi:
- $ (master) php app/console doctrine:fixtures:load
