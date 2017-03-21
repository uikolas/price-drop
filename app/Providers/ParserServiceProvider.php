<?php

namespace App\Providers;

use App\Parse\Parsers\MobileLineParser;
use App\Parse\Parsers\SkytechParser;
use Illuminate\Support\ServiceProvider;

class ParserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->tag($this->parsers(), 'parsers');
    }

    /**
     * Register created product retailers parsers here
     *
     * @return array
     */
    private function parsers()
    {
        return [
            MobileLineParser::class,
            SkytechParser::class,
        ];
    }
}
