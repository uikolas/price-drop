<?php

declare(strict_types=1);

namespace Scraper\Scrapers;

use App\Client\HttpClientInterface;
use App\Models\ProductRetailer;
use App\Scraper\ScrapData;
use App\Scraper\Scrapers\AmazonScraper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestDataHelper;

class AmazonScraperTest extends TestCase
{
    use TestDataHelper;

    public function testScrap(): void
    {
        $data = $this->getTestData('amazon.txt');
        $client = $this->createMock(HttpClientInterface::class);
        $scraper = new AmazonScraper($client, new Crawler());

        $client->expects(self::once())
            ->method('get')
            ->with('http://temp')
            ->willReturn($data);

        self::assertEquals(
            new ScrapData('48.56', null, null),
            $scraper->scrap(
                new ProductRetailer(['url' => 'http://temp'])
            )
        );
    }
}
