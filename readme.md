## Price Drop

Small app which allows to track prices of added products and get email notifications. Written in Laravel 5.4

[Some photos of web page](public/images)

## Install

    git clone https://github.com/uikolas/price-drop.git

    composer install

    php artisan migrate

    php artisan db:seed

Edit (rename from .env.example) .env file to fill info with your database and email info.

## How to use update and notification
To update products and notify use command:

    php artisan update:products

Or if you want just update products, without email notification use:

    php artisan update:product_retailers

Also you can use [Laravel Task Scheduling](https://laravel.com/docs/5.4/scheduling)
Update and notify command will be run daily at 13:00 or change this value by yourself

## How to add parser?

* Create a new class, which implements `\App\Parse\ParserInterface interface`
* Use API or web crawler, to get product price and return `ParseObject`
* Then created parser register in `\App\Providers\ParserServiceProvider` (add full class name)
* And run `php artisan db:seed` to create parser reference in database