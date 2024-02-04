<?php

declare(strict_types=1);

namespace Tests\Unit\Scraper\Scrapers;

use App\Client\HttpClientInterface;
use App\Exceptions\ScrapingFailedException;
use App\Models\ProductRetailer;
use App\Scraper\ScrapData;
use App\Scraper\Scrapers\DummyJsonScraper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DummyJsonScraperTest extends TestCase
{
    private HttpClientInterface&MockObject $client;

    private DummyJsonScraper $scraper;

    protected function setUp(): void
    {
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->scraper = new DummyJsonScraper($this->client);
    }

    public function testScrap(): void
    {
        $this->client->expects(self::once())
            ->method('get')
            ->with('http://temp')
            ->willReturn('{"id":1,"title":"iPhone 9","description":"An apple mobile which is nothing like apple","price":549,"discountPercentage":12.96,"rating":4.69,"stock":94,"brand":"Apple","category":"smartphones","thumbnail":"https://cdn.dummyjson.com/product-images/1/thumbnail.jpg","images":["https://cdn.dummyjson.com/product-images/1/1.jpg","https://cdn.dummyjson.com/product-images/1/2.jpg","https://cdn.dummyjson.com/product-images/1/3.jpg","https://cdn.dummyjson.com/product-images/1/4.jpg","https://cdn.dummyjson.com/product-images/1/thumbnail.jpg"]}');

        self::assertEquals(
            new ScrapData('549.00', 'https://cdn.dummyjson.com/product-images/1/1.jpg'),
            $this->scraper->scrap(new ProductRetailer(['url' => 'http://temp'])),
        );
    }

    public function testThrowExceptionIFCannotDecodeJson(): void
    {
        $this->expectException(ScrapingFailedException::class);

        $this->client->expects(self::once())
            ->method('get')
            ->with('http://temp')
            ->willReturn('{"id:1]');

        $this->scraper->scrap(new ProductRetailer(['url' => 'http://temp']));
    }
}
