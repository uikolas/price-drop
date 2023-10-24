<?php

declare(strict_types=1);

namespace Tests\Unit\Scraper\Scrapers;

use App\Client\HttpClientInterface;
use App\Models\ProductRetailer;
use App\Scraper\ScrapData;
use App\Scraper\Scrapers\G2AScraper;
use App\Scraper\Scrapers\MobiliScraper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestDataHelper;

class G2AScraperTest extends TestCase
{
    use TestDataHelper;

    /**
     * @dataProvider scrapDataProvider
     */
    public function testScrap(string $data, ScrapData $expectedResult): void
    {
        $client = $this->createMock(HttpClientInterface::class);
        $scraper = new G2AScraper($client, new Crawler());

        $client->expects(self::once())
            ->method('get')
            ->with('http://temp?___currency=AED&___locale=en')
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
            $this->getTestData('g2a.txt'),
            new ScrapData('6.84', 'EUR', 'https://images.g2a.com/360x600/1x1x1/xbox-game-pass-ultimate-1-month-xbox-live-key-united-states-i10000188766014/5d08a7ed7e696c5c4d7df022'),
        ];

        yield 'values not found' => [
            '',
            new ScrapData(null, 'EUR', null),
        ];
    }
}
