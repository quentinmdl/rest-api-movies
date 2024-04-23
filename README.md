# Documentation de l'API des Films

Bienvenue dans la documentation de l'API des Films. Cette API permet de créer, lire, mettre à jour, supprimer des informations sur les films et gérer les catégories associées aux films.

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
- `GET /api/movies/search` : Récupère tous les films filtrés par nom ou description.
- `GET /api/movies/{id}` : Récupère un film par son ID.
- `POST /api/movies` : Crée un nouveau film.
- `PUT /api/movies/{id}` : Met à jour un film existant.
- `DELETE /api/movies/{id}` : Supprime un film.

#### Catégories
- `GET /api/categories` : Récupère toutes les catégories.
- `GET /api/categories/{id}` : Récupère une catégorie par son ID.
- `POST /api/categories` : Crée une nouvelle catégorie.
- `PUT /api/categories/{id}` : Met à jour une catégorie existante.
- `DELETE /api/categories/{id}` : Supprime une catégorie.

### Validation

Les requêtes pour créer et mettre à jour un film doivent respecter les règles de validation suivantes :

- `name` : string, requis
- `description` : string, requis
- `release_date` : date, requis
- `rating` : integer, requis

Les requêtes pour créer et mettre à jour une categorie doivent respecter les règles de validation suivantes :

- `name` : string, requis


### Utilitaires

```bash
php artisan l5-swagger:generate
```
```bash
php artisan make:seeder EntitySeeder
```
```bash
php artisan make:model Entity -mcr
```
```bash
php artisan make:interface /Interfaces/EntityRepositoryInterface
```
```bash
php artisan make:class /Repositories/EntityRepository
```
```bash
php artisan make:provider RepositoryServiceProvider
```
```bash
php artisan make:class /Classes/ApiResponseClass
```
```bash
php artisan make:resource EntityResource
```

Install all api config 
```bash
php artisan install:api
```