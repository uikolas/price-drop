# Price drop
Small app which allows to track prices of added products and get email notification about price drop. Written in Laravel 9.

## Installation
To run project locally it's recommended install docker and use  [Laravel Sail](https://laravel.com/docs/9.x/sail).

After running docker with sail you can run:

`php artisan migrate`

`php artisan db:seed --class=UserSeeder`

## How to update prices?
To update prices for all retailers just run:

`php artisan retailers:update`

Or click update button inside product show page.

It will create queue jobs which later needs to be handled by a queue 

`php artisan queue:work` or `php artisan queue:work --stop-when-empty`

## How to add a new scraper?
1. Create a new class inside `App\Scraper\Scrapers` which implements `\App\Scraper\ScraperInterface` interface.
2. Register new scraper class inside `\App\Providers\ScraperProvider::$scrapers`

## Images
index page
![index](/resources/assets/index.png)
show page
![show](/resources/assets/show.png)
