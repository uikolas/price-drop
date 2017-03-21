## Price Drop

Small app which allows to track prices of added products and get email notifications. Written in Laravel 5.4

## Install

    git clone

    composer install

    php artisan migrate

    php artisan db:seed

## How to use update
To Update products and notify use command:

    php artisan update:products

Or if you want just update products, without notification use:

    php artisan update:product_retailers

Also you can use [Laravel Task Scheduling](https://laravel.com/docs/5.4/scheduling)
Update and notify command will be run daily at 13:00

## How to add parser?

* Create a new class, which implements \App\Parse\ParserInterface interface
* Then parser register in \App\Providers\ParserServiceProvider (add class name)
* And run php artisan db:seed to create parser in database