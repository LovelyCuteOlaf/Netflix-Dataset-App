<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Test Assignment: Netflix Dataset API

This project implements a simple REST API over Netflix-like datasets based on three CSV files (`movies.csv`, `users.csv`, `reviews.csv`).

### Schema
- Tables: `users` (dataset users), `movies`, `reviews`
- Relationships: users many-to-many movies via `reviews` (also holds rating and metadata)

### Endpoints
- `GET /api/movies` — list with filters (`title`, `content_type`, `genre`, `release_year`, `language`, `country`, `is_netflix_original`, `imdb_min`, `imdb_max`) + pagination (`per_page`)
- `GET /api/movies/{movie}` — show details with reviews and users
- `GET /api/users` — list with filters (`country`, `subscription_plan`, `is_active`, `age_min`, `age_max`) + pagination
- `GET /api/users/{user}` — show details with reviews and movies
- `GET /api/reviews` — list with filters (`movie_id`, `user_id`, `rating`, `date_from`, `date_to`) + pagination
- `POST /api/reviews` — create
- `GET /api/reviews/{review}` — show
- `PUT/PATCH /api/reviews/{review}` — update
- `DELETE /api/reviews/{review}` — delete

### Local Setup
1. Copy `.env.example` to `.env` and set DB connection (or use Docker below).
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```
3. Generate app key and run migrations:
   ```bash
   php artisan key:generate
   php artisan migrate
   ```

### Import CSV Data
Place CSVs under `datasets/` (recommended): `datasets/users.csv`, `datasets/movies.csv`, `datasets/reviews.csv`.

Import all datasets at once (users → movies → reviews):
```bash
php artisan import:dataset --chunk=2000
# or simply
php artisan import:dataset
```

Alternatively, you can pass explicit paths:
```bash
php artisan import:dataset --users=datasets/users.csv --movies=datasets/movies.csv --reviews=datasets/reviews.csv --chunk=1000
```

### Docker
Start MySQL and the Laravel app via Docker:
```bash
docker compose up -d --build
```
Run migrations inside the app container and import data:
```bash
docker exec -it laravel_app php artisan key:generate
docker exec -it laravel_app php artisan migrate
docker exec -it laravel_app php artisan import:dataset --users=/var/www/html/datasets/users.csv --movies=/var/www/html/datasets/movies.csv --reviews=/var/www/html/datasets/reviews.csv
```
Access the API at `http://localhost:8080/api/...`

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
