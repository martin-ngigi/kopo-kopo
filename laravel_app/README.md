## Create DB
- touch database/database.sqlite
- php artisan migrate

# installation
```
composer require darkaonline/l5-swagger
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
php artisan l5-swagger:generate
```

## Controllers
- Admin
```
php artisan make:controller TransactionController

```
- API
```
php artisan make:controller API/APIController
php artisan make:controller API/TransactionAPIController

```

## Models
```
php artisan make:model Transaction -m     

```

### clear machine cache:
- in the terminal, run:
```
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan route:cache
php artisan config:cache
php artisan cache:clear
composer dump-autoload
php artisan view:clear
```