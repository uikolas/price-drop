<?php

declare(strict_types=1);

namespace App\Scraper\Scrapers;

use App\Models\ProductRetailer;
use App\RetailerType;
use App\Scraper\ScrapData;
use Symfony\Component\DomCrawler\Crawler;

class MobiliScraper extends AbstractScraper
{
    public function supports(ProductRetailer $productRetailer): bool
    {
        return $productRetailer->hasType(RetailerType::MOBILI);
    }

    protected function doScraping(Crawler $crawler): ScrapData
    {
        $price = $crawler->filter('.prices_full');
        $cleanPrice = $price->text();
        $cleanPrice = substr($cleanPrice, 0, -5);
        $cleanPrice = str_replace(',', '.', $cleanPrice);

        return new ScrapData($cleanPrice);
    }
}
