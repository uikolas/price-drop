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
        $price = $this->scrapPrice($crawler);
        $image = $this->scrapImage($crawler);

        return new ScrapData($price, 'EUR', $image);
    }

    private function scrapPrice(Crawler $crawler): ?string
    {
        try {
            $price = $crawler->filter('.prices_full');
            $cleanPrice = $price->text();
            $cleanPrice = substr($cleanPrice, 0, -5);

            return str_replace(',', '.', $cleanPrice);
        } catch (\InvalidArgumentException) {
            return null;
        }
    }

    private function scrapImage(Crawler $crawler): ?string
    {
        try {
            return $crawler->filter('#ti_img_kaina > img')->image()->getUri();
        } catch (\InvalidArgumentException) {
            return null;
        }
    }
}
