<?php

declare(strict_types=1);

namespace Tests\Unit\Scraper\Scrapers;

use App\Client\HttpClientInterface;
use App\Models\ProductRetailer;
use App\Scraper\ScrapData;
use App\Scraper\Scrapers\MobiliScraper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestDataHelper;

class MobiliScraperTest extends TestCase
{
    use TestDataHelper;

    /**
     * @dataProvider scrapDataProvider
     */
    public function testScrap(string $data, ScrapData $expectedResult): void
    {
        $client = $this->createMock(HttpClientInterface::class);
        $scraper = new MobiliScraper($client, new Crawler());

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
            $this->getTestData('mobili.txt'),
            new ScrapData('189.00', 'EUR', 'https://www.mobili.lt/images/bigphones/nokia_nokia_g50_823045.png'),
        ];

        yield 'values not found' => [
            '',
            new ScrapData(null, 'EUR', null),
        ];
    }
}
