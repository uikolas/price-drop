<?php

namespace App\Providers;

use App\Client\HttpClientInterface;
use App\Client\GuzzleHttpClient;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public array $bindings = [
        HttpClientInterface::class => GuzzleHttpClient::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        Paginator::useBootstrapFive();

        if (env('APP_ENV') == 'staging') {
            $url->forceScheme('https');
        }
    }
}
