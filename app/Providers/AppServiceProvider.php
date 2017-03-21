<?php

namespace App\Providers;

use App\Parse\ParserFactory;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Schema::defaultStringLength(191);

        \Blade::directive('money', function ($expression) {
            return "<?php echo formatMoney($expression); ?>";
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Client::class, function ($app) {
            return new Client([
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.1'
                ]
            ]);
        });

        $this->app->bind(ParserFactory::class, function ($app) {
            return new ParserFactory($app->tagged('parsers'));
        });

        $this->app->bind(\RetailersTableSeeder::class, function ($app) {
            return new \RetailersTableSeeder($app->tagged('parsers'));
        });
    }
}
