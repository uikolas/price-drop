<?php

namespace App\Providers;

use App\Client\HttpClientInterface;
use App\Client\GuzzleHttpClient;
use App\Models\Product;
use App\Models\ProductRetailer;
use App\Policies\ProductPolicy;
use App\Policies\ProductRetailerPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public array $bindings = [
        HttpClientInterface::class => GuzzleHttpClient::class,
    ];

    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
        ProductRetailer::class => ProductRetailerPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
