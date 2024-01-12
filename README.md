## Installation guid

- composer install
- php artisan key:generate
- cp .env.example .env
- Add your database credentials to the ".env" file
- php migrate
- php db:seed
- php artisan serve //now you can get access to the site using http://127.0.0.1:8000
- In browser go to http://127.0.0.1:8000/admin
- By default, you can use "admin@admin.com" as login and "password" as password

## API
- To generate API documentation run `php artisan l5-swagger:generate`
- In browser go to http://127.0.0.1:8000/api/documentation
