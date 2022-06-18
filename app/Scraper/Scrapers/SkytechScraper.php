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
    public function supports(ProductRetailer $productRetailer): bool
    {
        return $productRetailer->hasType(RetailerType::SKYTECH);
    }

    protected function doScraping(Crawler $crawler): ScrapData
    {
        $price = $crawler->filter('.num');

        $cleanPrice = explode('/', $price->text());
        $cleanPrice = trim($cleanPrice[0]);
        $cleanPrice = str_replace('€', '', $cleanPrice);

        return new ScrapData($cleanPrice);
    }
}
