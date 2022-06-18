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

    public function testScrap(): void
    {
        $data = $this->getTestData('mobili.txt');
        $client = $this->createMock(HttpClientInterface::class);
        $scraper = new MobiliScraper($client, new Crawler());

        $client->expects(self::once())
            ->method('get')
            ->with('http://temp')
            ->willReturn($data);

        self::assertEquals(
            new ScrapData('189.00'),
            $scraper->scrap(
                new ProductRetailer(['url' => 'http://temp'])
            )
        );
    }
}
