<?php

declare(strict_types=1);

namespace App\Scraper\Scrapers;

use App\Exceptions\ScrapingFailedException;
use App\Models\ProductRetailer;
use App\Price;
use App\Product\PriceHelper;
use App\RetailerType;
use App\Scraper\ScrapData;
use Symfony\Component\DomCrawler\Crawler;

class SkytechScraper extends AbstractHtmlScraper
{
    private const URL = 'https://www.skytech.lt';

    public function supports(ProductRetailer $productRetailer): bool
    {
        return $productRetailer->hasType(RetailerType::SKYTECH);
    }

    protected function doScraping(Crawler $crawler, ProductRetailer $productRetailer): ScrapData
    {
        $price = $this->scrapPrice($crawler, $productRetailer);
        $image = $this->scrapImage($crawler);

        return new ScrapData(new Price($price), $image);
    }

    /**
     * @throws ScrapingFailedException
     */
    private function scrapPrice(Crawler $crawler, ProductRetailer $productRetailer): string
    {
        $price = $crawler->filter('.num');

        if ($price->count() === 0) {
            throw ScrapingFailedException::createPriceNotFound($productRetailer);
        }

        $cleanPrice = $price->text();

        return PriceHelper::cleanPrice($cleanPrice);
    }

    private function scrapImage(Crawler $crawler): ?string
    {
        $image = $crawler->filter('#main-product-image');

        if ($image->count() === 0) {
            return null;
        }

        return self::URL . $image->attr('src');
    }
}
