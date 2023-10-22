# :droplet: Price drop
Price drop is a small app which allows tracking prices of added products and get email notification about price drop. Written with Laravel 9.

## Installation
To run project locally it's recommended to install docker and use  [Laravel Sail](https://laravel.com/docs/10.x/sail#installing-composer-dependencies-for-existing-projects).

After running docker with sail you can run:

* `cp .env.example .env`
* `php artisan key:generate`
* `php artisan migrate`
* `php artisan db:seed --class=UserSeeder`
* open website and login (login info is inside `\Database\Seeders\UserSeeder`)

## Images
[Go here](/resources/docs/Overview.md)

## How to update prices?
To update prices for all retailers just run (also this command sends notification if price was dropped):

`php artisan retailers:update`

Or you can configure [Laravel Task Scheduling](https://laravel.com/docs/10.x/scheduling)

After running command. It will create queue jobs which later needs to be handled by [a queue](https://laravel.com/docs/10.x/queues#running-the-queue-worker) 

`php artisan queue:work` or `php artisan queue:work --stop-when-empty`

## How to add a new shop scraper?
1. Create a new class inside `App\Scraper\Scrapers` which implements `\App\Scraper\ScraperInterface` interface.
2. Register new scraper class inside `\App\Providers\ScraperProvider::$scrapers`
3. Add test case inside `Tests\Unit\Scraper\Scrapers`
