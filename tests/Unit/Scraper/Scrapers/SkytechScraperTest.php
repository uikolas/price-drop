<?php

declare(strict_types=1);

namespace Tests\Unit\Scraper\Scrapers;

use App\Client\HttpClientInterface;
use App\Models\ProductRetailer;
use App\Scraper\ScrapData;
use App\Scraper\Scrapers\SkytechScraper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestDataHelper;

class SkytechScraperTest extends TestCase
{
    use TestDataHelper;

    /**
     * @dataProvider scrapDataProvider
     */
    public function testScrap(string $data, ScrapData $expectedResult): void
    {
        $client = $this->createMock(HttpClientInterface::class);
        $scraper = new SkytechScraper($client, new Crawler());

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
            $this->getTestData('skytech.txt'),
            new ScrapData('273.19', 'EUR', 'https://www.skytech.lt/images/medium/99/3086899.jpg'),
        ];

        yield 'values not found' => [
            '',
            new ScrapData(null, 'EUR', null),
        ];
    }
}
