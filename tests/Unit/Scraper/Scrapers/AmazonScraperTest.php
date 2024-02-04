<?php

declare(strict_types=1);

namespace Tests\Unit\Scraper\Scrapers;

use App\Client\HttpClientInterface;
use App\Exceptions\ScrapingFailedException;
use App\Models\ProductRetailer;
use App\Scraper\ScrapData;
use App\Scraper\Scrapers\AmazonScraper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\TestDataHelper;

class AmazonScraperTest extends TestCase
{
    use TestDataHelper;

    private HttpClientInterface&MockObject $client;

    private AmazonScraper $scraper;

    protected function setUp(): void
    {
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->scraper = new AmazonScraper($this->client);
    }

    public function testScrap(): void
    {
        $this->client->expects(self::once())
            ->method('get')
            ->with('http://temp')
            ->willReturn(self::getTestData('amazon.txt'));

        self::assertEquals(
            new ScrapData('558.99', 'https://m.media-amazon.com/images/I/51t1T3R5v3L.__AC_SY445_SX342_QL70_FMwebp_.jpg'),
            $this->scraper->scrap(new ProductRetailer(['url' => 'http://temp']))
        );
    }

    public function testThrowExceptionIfPriceIsNotFound(): void
    {
        $this->expectException(ScrapingFailedException::class);

        $this->client->expects(self::once())
            ->method('get')
            ->with('http://temp')
            ->willReturn('');

        $this->scraper->scrap(new ProductRetailer(['url' => 'http://temp']));
    }

    public function testReturnImageNullIfNotFound(): void
    {
        $this->client->expects(self::once())
            ->method('get')
            ->with('http://temp')
            ->willReturn('<input type="hidden" id="twister-plus-price-data-price" value="558.99" />');

        self::assertEquals(
            new ScrapData('558.99', null),
            $this->scraper->scrap(new ProductRetailer(['url' => 'http://temp']))
        );
    }
}
