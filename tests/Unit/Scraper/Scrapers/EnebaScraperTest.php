<?php

declare(strict_types=1);

namespace Tests\Unit\Scraper\Scrapers;

use App\Client\HttpClientInterface;
use App\Models\ProductRetailer;
use App\Scraper\ScrapData;
use App\Scraper\Scrapers\EnebaScraper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestDataHelper;

class EnebaScraperTest extends TestCase
{
    use TestDataHelper;

    /**
     * @dataProvider scrapDataProvider
     */
    public function testScrap(string $data, ScrapData $expectedResult): void
    {
        $client = $this->createMock(HttpClientInterface::class);
        $scraper = new EnebaScraper($client, new Crawler());

        $client->expects(self::once())
            ->method('get')
            ->with('http://temp')
            ->willReturn($data);

        self::assertEquals(
            $expectedResult,
            $scraper->scrap(
                new ProductRetailer(['url' => 'http://temp'])
            )
        );
    }

    public function scrapDataProvider(): iterable
    {
        yield 'correct values' => [
            $this->getTestData('eneba.txt'),
            new ScrapData('10.99', 'EUR', 'https://cdn-products.eneba.com/resized-products/s29Db6ZBVLneuD0t66qnYRamvGDP3p8chLz-3IomxcU_350x200_1x-0.jpeg'),
        ];

        yield 'values not found' => [
            '',
            new ScrapData(null, 'EUR', null),
        ];
    }
}
