<?php

declare(strict_types=1);

namespace App\Scraper\Scrapers;

use App\Client\HttpClientInterface;
use App\Exceptions\ScrapingFailedException;
use App\Models\ProductRetailer;
use App\Scraper\ScrapData;
use App\Scraper\ScraperInterface;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractHtmlScraper implements ScraperInterface
{
    public function __construct(
        protected readonly HttpClientInterface $client,
        protected readonly Crawler $crawler
    ) {
    }

    public function scrap(ProductRetailer $productRetailer): ScrapData
    {
        $response = $this->client->get($productRetailer->url);
        $this->crawler->addHtmlContent($response);

        return $this->doScraping($this->crawler, $productRetailer);
    }

    /**
     * @throws ScrapingFailedException
     */
    abstract protected function doScraping(Crawler $crawler, ProductRetailer $productRetailer): ScrapData;
}
