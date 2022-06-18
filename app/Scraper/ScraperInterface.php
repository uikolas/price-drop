<?php

declare(strict_types=1);

namespace App\Scraper;

use App\Exceptions\ScrapingFailedException;
use App\Models\ProductRetailer;

interface ScraperInterface
{
    public function supports(ProductRetailer $productRetailer): bool;

    /**
     * @throws ScrapingFailedException
     */
    public function scrap(ProductRetailer $productRetailer): ScrapData;
}
