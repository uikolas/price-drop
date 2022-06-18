<?php

declare(strict_types=1);

namespace App\Scraper\Scrapers;

use App\Client\HttpClientInterface;
use App\Exceptions\FailedHttpRequestException;
use App\Exceptions\ScrapingFailedException;
use App\Models\ProductRetailer;
use App\Scraper\ScrapData;
use App\Scraper\ScraperInterface;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractScraper implements ScraperInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly Crawler $crawler
    ) {
    }

    public function scrap(ProductRetailer $productRetailer): ScrapData
    {
        try {
            $response = $this->client->get($productRetailer->url);
            $this->crawler->addHtmlContent($response);

            return $this->doScraping($this->crawler);
        } catch (FailedHttpRequestException | \InvalidArgumentException $exception) {
            throw new ScrapingFailedException('Failed to scrap data!', 0, $exception);
        }
    }

    abstract protected function doScraping(Crawler $crawler): ScrapData;
}
