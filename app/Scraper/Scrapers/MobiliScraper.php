<?php

declare(strict_types=1);

namespace App\Scraper\Scrapers;

use App\Exceptions\ScrapingFailedException;
use App\Models\ProductRetailer;
use App\Price;
use App\RetailerType;
use App\Scraper\ScrapData;
use Symfony\Component\DomCrawler\Crawler;

class MobiliScraper extends AbstractHtmlScraper
{
    public function supports(ProductRetailer $productRetailer): bool
    {
        return $productRetailer->hasType(RetailerType::MOBILI);
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
        $price = $crawler->filter('.prices_full');

        if ($price->count() === 0) {
            throw ScrapingFailedException::createPriceNotFound($productRetailer);
        }

        $cleanPrice = $price->text();
        $cleanPrice = \mb_substr($cleanPrice, 0, -2);

        return \str_replace(',', '.', $cleanPrice);
    }

    private function scrapImage(Crawler $crawler): ?string
    {
        $image = $crawler->filter('#ti_img_kaina > img');

        if ($image->count() === 0) {
            return null;
        }

        return $image->image()->getUri();
    }
}
