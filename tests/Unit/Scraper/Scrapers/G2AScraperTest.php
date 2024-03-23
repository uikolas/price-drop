<?php

declare(strict_types=1);

namespace Tests\Unit\Scraper\Scrapers;

use App\Client\HttpClientInterface;
use App\Exceptions\ScrapingFailedException;
use App\Models\ProductRetailer;
use App\Price;
use App\Scraper\ScrapData;
use App\Scraper\Scrapers\G2AScraper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestDataHelper;

class G2AScraperTest extends TestCase
{
    use TestDataHelper;

    private HttpClientInterface&MockObject $client;

    private G2AScraper $scraper;

    protected function setUp(): void
    {
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->scraper = new G2AScraper($this->client, new Crawler());
    }

    public function testScrap(): void
    {
        $this->client->expects(self::once())
            ->method('get')
            ->with('http://temp?___currency=EUR&___locale=en')
            ->willReturn(self::getTestData('g2a.txt'));

        self::assertEquals(
            new ScrapData(new Price('40.73'), 'https://images.g2a.com/360x600/1x1x1/nba-2k24-kobe-bryant-edition-xbox-series-x-s-xbox-live-key-global-i10000340079029/96e0945f27084558a0f77237'),
            $this->scraper->scrap(new ProductRetailer(['url' => 'http://temp']))
        );
    }

    public function testThrowExceptionIfPriceNotFound(): void
    {
        $this->expectException(ScrapingFailedException::class);

        $this->client->expects(self::once())
            ->method('get')
            ->with('http://temp?___currency=EUR&___locale=en')
            ->willReturn('');

        $this->scraper->scrap(new ProductRetailer(['url' => 'http://temp']));
    }

    public function testReturnPriceNullIfNotFound(): void
    {
        $this->client->expects(self::once())
            ->method('get')
            ->with('http://temp?___currency=EUR&___locale=en')
            ->willReturn('<div data-locator="ppa-offers-list__item"><span data-locator="ppa-offers-list__price">100.00</span></div>');

        self::assertEquals(
            new ScrapData(new Price('100.00'), null),
            $this->scraper->scrap(new ProductRetailer(['url' => 'http://temp']))
        );
    }
}
