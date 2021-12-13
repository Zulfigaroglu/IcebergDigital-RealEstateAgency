## How to Setup

### Install packages with composer
    composer install

### Copy example environment file as below
    cp .env.example .env

### Set your environment variables ( the followings are examples )
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=rea
    DB_PASSWORD=123456

### Run migrations
    php artisan migrate

### Generate application encryption keys
    php artisan key:generate

### Generate personal access client
    php artisan passport:install
