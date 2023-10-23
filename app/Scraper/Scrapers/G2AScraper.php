<?php

declare(strict_types=1);

namespace App\Scraper\Scrapers;

use App\Client\HttpClientInterface;
use App\Exceptions\FailedHttpRequestException;
use App\Exceptions\ScrapingFailedException;
use App\Models\ProductRetailer;
use App\RetailerType;
use App\Scraper\ScrapData;
use App\Scraper\ScraperInterface;
use Symfony\Component\DomCrawler\Crawler;

class G2AScraper implements ScraperInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly Crawler $crawler
    ) {
    }

    public function supports(ProductRetailer $productRetailer): bool
    {
        return $productRetailer->hasType(RetailerType::G2A);
    }

    public function scrap(ProductRetailer $productRetailer): ScrapData
    {
        try {
            $response = $this->client->get(
                \sprintf('%s?___currency=AED&___locale=en', $productRetailer->url)
            );
            $this->crawler->addHtmlContent($response);

            return $this->doScraping($this->crawler);
        } catch (FailedHttpRequestException | \InvalidArgumentException $exception) {
            throw new ScrapingFailedException('Failed to scrap data!', 0, $exception);
        }
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
            $price = $crawler->filter('[data-locator="zth-price"]');
            $cleanPrice = $price->text();

            return substr($cleanPrice, 4);
        } catch (\InvalidArgumentException) {
            return null;
        }
    }

    private function scrapImage(Crawler $crawler): ?string
    {
        try {
            return $crawler->filter('[data-locator="ppa-gallery_cover-image"]')->image()->getUri();
        } catch (\InvalidArgumentException) {
            return null;
        }
    }
}
