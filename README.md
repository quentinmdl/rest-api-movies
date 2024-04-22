# Documentation de l'API des Films

Bienvenue dans la documentation de l'API des Films. Cette API permet de créer, lire, mettre à jour et supprimer des informations sur les films.

## Commencer

Pour commencer à utiliser cette API, veuillez suivre les instructions ci-dessous.

### Prérequis

- PHP >= 8.0
- Composer
- Docker
- Laravel

### Installation

1. Clonez le dépôt sur votre machine locale.
2. Exécutez `composer install` pour installer les dépendances.
3. Copiez `.env.example` en `.env` et configurez votre base de données.
4. Exécutez `php artisan key:generate` pour générer la clé de l'application.
5. Exécutez `docker compose up` pour éxécuter la base de données.
6. Exécutez `php artisan migrate:refresh --seed` pour créer les tables de la base de données et les données de test.
7. Exécutez `php artisan serve` pour démarrer le serveur de développement.

### Utilisation

L'API fournit les endpoints suivants :

- `GET /api/movies` : Récupère tous les films.
- `GET /api/movies/{id}` : Récupère un film par son ID.
- `POST /api/movies` : Crée un nouveau film.
- `PUT /api/movies/{id}` : Met à jour un film existant.
- `DELETE /api/movies/{id}` : Supprime un film.

### Validation

Les requêtes pour créer et mettre à jour un film doivent respecter les règles de validation suivantes :

- `name` : string, requis
- `description` : string, requis
- `release_date` : date, requis
- `rating` : integer, requis
