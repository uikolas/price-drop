<?php

declare(strict_types=1);

namespace App\Scraper\Scrapers;

use App\Client\HttpClientInterface;
use App\Exceptions\ScrapingFailedException;
use App\Models\ProductRetailer;
use App\RetailerType;
use App\Scraper\ScrapData;
use App\Scraper\ScraperInterface;

/**
 * example how to scrap json data
 */
class DummyJsonScraper implements ScraperInterface
{
    public function __construct(private readonly HttpClientInterface $client)
    {
    }

    public function supports(ProductRetailer $productRetailer): bool
    {
        return $productRetailer->hasType(RetailerType::DUMMYJSON);
    }

    /**
     * {@inheritdoc}
     */
    public function scrap(ProductRetailer $productRetailer): ScrapData
    {
        $response = $this->client->get($productRetailer->url);

        try {
            $data = \json_decode($response, true, 512, \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw ScrapingFailedException::create($e);
        }

        return new ScrapData(
            \number_format(\round($data['price'], 2), 2, '.', ''),
            $data['images'][0] ?? null
        );
    }
}
