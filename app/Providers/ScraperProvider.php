<?php

namespace App\Providers;

use App\Scraper\ScraperFactory;
use App\Scraper\Scrapers\AmazonScraper;
use App\Scraper\Scrapers\MobiliScraper;
use App\Scraper\Scrapers\SkytechScraper;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

class ScraperProvider extends ServiceProvider
{
    private const TAG_SCRAPERS = 'scrapers';

    /**
     * @var string[]
     */
    private array $scrapers = [
        MobiliScraper::class,
        SkytechScraper::class,
        AmazonScraper::class,
    ];

    public function register(): void
    {
        $this->app->tag($this->scrapers, self::TAG_SCRAPERS);

        $this->app->bind(ScraperFactory::class, function (Container $app) {
            return new ScraperFactory($app->tagged(self::TAG_SCRAPERS));
        });
    }

    public function boot(): void
    {
    }
}
