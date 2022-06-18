<?php

declare(strict_types=1);

namespace App\Scraper;

use App\Exceptions\ScraperNotFoundException;
use App\Models\ProductRetailer;

class ScraperFactory
{
    /**
     * @param ScraperInterface[] $scrapers
     */
    public function __construct(private readonly iterable $scrapers)
    {
    }

    /**
     * @throws ScraperNotFoundException
     */
    public function createFromRetailer(ProductRetailer $productRetailer): ScraperInterface
    {
        foreach ($this->scrapers as $scraper) {
            if ($scraper->supports($productRetailer)) {
                return $scraper;
            }
        }

        throw new ScraperNotFoundException(
            \sprintf(
                'Scraper not found for product retailer: %s. Maybe did you forget to register in ScraperProvider?',
                $productRetailer->id,
            )
        );
    }
}
