<?php

declare(strict_types=1);

namespace App\Scraper\Scrapers;

use App\Client\HttpClientInterface;
use App\Models\ProductRetailer;
use App\RetailerType;
use App\Scraper\ScrapData;
use App\Scraper\ScraperInterface;
use Symfony\Component\DomCrawler\Crawler;

class SkytechScraper extends AbstractScraper
{
    private const URL = 'https://www.skytech.lt';

    public function supports(ProductRetailer $productRetailer): bool
    {
        return $productRetailer->hasType(RetailerType::SKYTECH);
    }

    protected function doScraping(Crawler $crawler): ScrapData
    {
        $price = $this->scrapPrice($crawler);
        $image = $this->scrapImage($crawler);

        return new ScrapData($price, 'EUR', $image);
    }

    private function scrapPrice(Crawler $crawler): ?string
    {
        try {
            $price = $crawler->filter('.num');
            $cleanPrice = $price->text();

            return \mb_substr($cleanPrice, 0, -1);
        } catch (\InvalidArgumentException) {
            return null;
        }
    }

    private function scrapImage(Crawler $crawler): ?string
    {
        try {
            $image = $crawler->filter('#main-product-image')->attr('src');

            return self::URL. $image;
        } catch (\InvalidArgumentException) {
            return null;
        }
    }
}
