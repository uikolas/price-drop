<?php

namespace App\Providers;

use App\Client\HttpClientInterface;
use App\Client\GuzzleHttpClient;
use App\Client\LoggingHttpClient;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class AppServiceProvider extends ServiceProvider
{
    public array $bindings = [
        HttpClientInterface::class => GuzzleHttpClient::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->extend(HttpClientInterface::class, function (HttpClientInterface $client, Application $application) {
            return new LoggingHttpClient(
                $client,
                $application->make(LoggerInterface::class),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
