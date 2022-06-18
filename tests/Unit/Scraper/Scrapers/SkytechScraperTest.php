<?php

declare(strict_types=1);

namespace Scraper\Scrapers;

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

    public function testScrap(): void
    {
        $data = $this->getTestData('skytech.txt');
        $client = $this->createMock(HttpClientInterface::class);
        $scraper = new SkytechScraper($client, new Crawler());

        $client->expects(self::once())
            ->method('get')
            ->with('http://temp')
            ->willReturn($data);

        self::assertEquals(
            new ScrapData('273.19'),
            $scraper->scrap(
                new ProductRetailer(['url' => 'http://temp'])
            )
        );
    }
}
