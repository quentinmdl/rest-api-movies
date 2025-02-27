# API Documentation for Movies

Welcome to the Movies API documentation. This API allows you to create, read, update, delete information about movies and manage categories associated with movies.

## Getting Started

To start using this API, please follow the instructions below.

### Prerequisites

- PHP >= 8.3
- Composer
- Docker
- Laravel
- Postman (optional)

### Installation

1. Clone the repository to your local machine.
2. Run `composer install` and `npm i` to install the dependencies.
3. Copy `.env.example` to `.env` and configure your database.
4. Complete `.env` and don't forget the `API_DBMOVIE_KEY` for seeding db movies : eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJjZjMwYWIzNmE3Y2M4MjQ1OWIzMjk0MTZhZmUxNzM5MCIsInN1YiI6IjY2MjYyNDViYjlhMGJkMDE3YWQ3MWFlNSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.OHgbOVLByCQfvUAiVpLUHRz_Ehwq9l5MkdcYhhE4RF4
5. Run `php artisan key:generate` to generate the application key.
6. Run `php artisan migrate:refresh --seed` to create the database tables and seed fake data.
7. Run `php artisan serve` to start the development server.

### Usage

The API provides the following endpoints:

#### Movies

- `GET /api/movies`: Retrieves all movies.
- `GET /api/movies/search`: Retrieves all movies filtered by name or description.
- `GET /api/movies/{id}`: Retrieves a movie by its UID.
- `POST /api/movies`: Creates a new movie.
- `PUT /api/movies/{id}`: Updates an existing movie.
- `DELETE /api/movies/{id}`: Deletes a movie.

#### Categories

- `GET /api/categories`: Retrieves all categories.
- `GET /api/categories/{id}`: Retrieves a category by its UID.
- `POST /api/categories`: Creates a new category.
- `PUT /api/categories/{id}`: Updates an existing category.
- `DELETE /api/categories/{id}`: Deletes a category.

#### Media

- `GET /api/medias`: Retrieves all media.
- `GET /api/medias/{id}`: Retrieves a media by its UID.
- `DELETE /api/medias/{id}`: Deletes a media.


### Validation

Requests to create and update a **movie** must adhere to the following validation rules:

- `name`: string, required
- `description`: string, required
- `release_date`: date, required
- `rate`: integer, required
- `media_id`: integer, can be nullable, must be a valid foreign key reference to `medias` table


Requests to create and update a **category** must adhere to the following validation rules:

- `name`: string, required


Requests to create and update a **media** must adhere to the following validation rules:

- `media_path`: string, nullable
- `media_url`: string, nullable
- `media_type`: integer, required, must be a valid foreign key reference to `media_types` table

### Test

To test the API, you can use the following methods:

#### Swagger UI
You can access the API documentation and test the endpoints directly via Swagger UI by visiting the following route:

- `/api/documentation`

#### Postman 

Download and implement in Postman folder : `movies.postman.json` file in Postman to test the API.

- `Postman/movies.postman.json`


### Utilities

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