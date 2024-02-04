<?php

declare(strict_types=1);

namespace App\Scraper\Scrapers;

use App\Exceptions\ScrapingFailedException;
use App\Models\ProductRetailer;
use App\Product\PriceHelper;
use App\RetailerType;
use App\Scraper\ScrapData;
use Symfony\Component\DomCrawler\Crawler;

class EnebaScraper extends AbstractHtmlScraper
{
    public function supports(ProductRetailer $productRetailer): bool
    {
        return $productRetailer->hasType(RetailerType::ENEBA);
    }

    /**
     * {@inheritdoc}
     */
    protected function doScraping(Crawler $crawler, ProductRetailer $productRetailer): ScrapData
    {
        $price = $this->scrapPrice($crawler, $productRetailer);
        $image = $this->scrapImage($crawler);

        return new ScrapData($price, $image);
    }

    /**
     * @throws ScrapingFailedException
     */
    private function scrapPrice(Crawler $crawler, ProductRetailer $productRetailer): string
    {
        $prices = $crawler
            ->filter('._7z2Gr .L5ErLT')
            ->each(
                fn(Crawler $node): string => PriceHelper::cleanPrice(str_replace(',', '.', $node->text()))
            );

        asort($prices);
        $price = $prices[0] ?? null;

        if ($price === null) {
            throw ScrapingFailedException::createPriceNotFound($productRetailer);
        }

        return $price;
    }

    private function scrapImage(Crawler $crawler): ?string
    {
        $image = $crawler->filter('.OlZQ6u > picture > img');

        if ($image->count() === 0) {
            return null;
        }

        return $image->image()->getUri();
    }
}
