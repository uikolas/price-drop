<?php

declare(strict_types=1);

namespace App\Scraper\Scrapers;

use App\Models\ProductRetailer;
use App\RetailerType;
use App\Scraper\ScrapData;
use Symfony\Component\DomCrawler\Crawler;

class AmazonScraper extends AbstractScraper
{
    public function supports(ProductRetailer $productRetailer): bool
    {
        return $productRetailer->hasType(RetailerType::AMAZON);
    }

    protected function doScraping(Crawler $crawler): ScrapData
    {
        $price = $crawler->filter('.price');
        $cleanPrice = $price->text();
        $cleanPrice = \mb_substr($cleanPrice, 1);

        return new ScrapData($cleanPrice, null, null);
    }
}
