# Instalacion

### Install PHP dependencies:

``` 
composer install 
```

### Setup configuration:

``` 
cp .env.example .env 
```

### Generate application key:

``` 
php artisan key:generate
```

### Run database migrations:

``` 
php artisan migrate
```

### Run database seeder:

``` 
php artisan db:seed
```

### Run artisan server:

``` 
php artisan serve
```
