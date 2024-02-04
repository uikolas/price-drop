<?php

declare(strict_types=1);

namespace App\Scraper\Scrapers;

use App\Client\HttpClientInterface;
use App\Exceptions\ScrapingFailedException;
use App\Models\ProductRetailer;
use App\RetailerType;
use App\Scraper\ScrapData;
use App\Scraper\ScraperInterface;

class AmazonScraper implements ScraperInterface
{
    public function __construct(private readonly HttpClientInterface $client)
    {
    }

    public function supports(ProductRetailer $productRetailer): bool
    {
        return $productRetailer->hasType(RetailerType::AMAZON);
    }

    /**
     * {@inheritdoc}
     */
    public function scrap(ProductRetailer $productRetailer): ScrapData
    {
        $response = $this->client->get($productRetailer->url);

        preg_match('/twister-plus-price-data-price.*value="(.*)"/', $response, $matches);

        if (!isset($matches[1])) {
            throw ScrapingFailedException::createPriceNotFound($productRetailer);
        }

        $price = $matches[1];
        $image = $this->scrapImage($response);

        return new ScrapData($price, $image);
    }

    private function scrapImage(string $response): ?string
    {
        preg_match('/<img.* src="(.*?)".*id="landingImage".*\/>/', $response, $matches);

        return $matches[1] ?? null;
    }
}
