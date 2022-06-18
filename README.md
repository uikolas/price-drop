# Price drop
Price drop :droplet: is a small app which allows to track prices of added products and get email notification about price drop. Written with Laravel 9.

## Installation
To run project locally it's recommended to install docker and use  [Laravel Sail](https://laravel.com/docs/9.x/sail#installing-composer-dependencies-for-existing-projects).

After running docker with sail you can run:

* `php artisan migrate`
* `php artisan db:seed --class=UserSeeder`

## How to update prices?
To update prices for all retailers just run (also this command sends notification if price was dropped):

`php artisan retailers:update`

Or click update button inside product show page (for specific retailer).

It will create queue jobs which later needs to be handled by a queue 

`php artisan queue:work` or `php artisan queue:work --stop-when-empty`

## How to add a new scraper?
1. Create a new class inside `App\Scraper\Scrapers` which implements `\App\Scraper\ScraperInterface` interface.
2. Register new scraper class inside `\App\Providers\ScraperProvider::$scrapers`
3. Add test case inside `Tests\Unit\Scraper\Scrapers`

## Images
index page
![index](/resources/assets/index.png)
show page
![show](/resources/assets/show.png)
