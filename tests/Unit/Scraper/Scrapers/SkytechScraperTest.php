<?php

declare(strict_types=1);

namespace Tests\Unit\Scraper\Scrapers;

use App\Client\HttpClientInterface;
use App\Exceptions\ScrapingFailedException;
use App\Models\ProductRetailer;
use App\Price;
use App\Scraper\ScrapData;
use App\Scraper\Scrapers\SkytechScraper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestDataHelper;

class SkytechScraperTest extends TestCase
{
    use TestDataHelper;

    private HttpClientInterface&MockObject $client;

    private SkytechScraper $scraper;

    protected function setUp(): void
    {
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->scraper = new SkytechScraper($this->client, new Crawler());
    }

    public function testScrap(): void
    {
        $this->client->expects(self::once())
            ->method('get')
            ->with('http://temp')
            ->willReturn(self::getTestData('skytech.txt'));

        self::assertEquals(
            new ScrapData(new Price('273.19'), 'https://www.skytech.lt/images/medium/99/3086899.jpg'),
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
            ->willReturn('<span class="num"><span>273.19€</span></span>');

        self::assertEquals(
            new ScrapData(new Price('273.19'), null),
            $this->scraper->scrap(new ProductRetailer(['url' => 'http://temp']))
        );
    }
}
