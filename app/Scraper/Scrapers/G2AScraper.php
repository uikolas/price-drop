<?php

declare(strict_types=1);

namespace App\Scraper\Scrapers;

use App\Exceptions\ScrapingFailedException;
use App\Models\ProductRetailer;
use App\Product\PriceHelper;
use App\RetailerType;
use App\Scraper\ScrapData;
use Symfony\Component\DomCrawler\Crawler;

class G2AScraper extends AbstractHtmlScraper
{
    public function supports(ProductRetailer $productRetailer): bool
    {
        return $productRetailer->hasType(RetailerType::G2A);
    }

    /**
     * {@inheritdoc}
     */
    public function scrap(ProductRetailer $productRetailer): ScrapData
    {
        $response = $this->client->get(\sprintf('%s?___currency=EUR&___locale=en', $productRetailer->url));
        $this->crawler->addHtmlContent($response);

        return $this->doScraping($this->crawler, $productRetailer);
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
            ->filter('[data-locator="ppa-offers-list__item"] [data-locator="ppa-offers-list__price"]')
            ->each(
                fn(Crawler $node): string => PriceHelper::cleanPrice($node->text())
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
        $image = $crawler->filter('[data-locator="ppa-gallery_cover-image"]');

        if ($image->count() === 0) {
            return null;
        }

        return $image->image()->getUri();
    }
}
