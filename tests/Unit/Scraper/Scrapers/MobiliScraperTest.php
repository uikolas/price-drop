<?php

declare(strict_types=1);

namespace Tests\Unit\Scraper\Scrapers;

use App\Client\HttpClientInterface;
use App\Exceptions\ScrapingFailedException;
use App\Models\ProductRetailer;
use App\Scraper\ScrapData;
use App\Scraper\Scrapers\MobiliScraper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestDataHelper;

class MobiliScraperTest extends TestCase
{
    use TestDataHelper;

    private HttpClientInterface&MockObject $client;

    private MobiliScraper $scraper;

    protected function setUp(): void
    {
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->scraper = new MobiliScraper($this->client, new Crawler());
    }

    public function testScrap(): void
    {
        $this->client->expects(self::once())
            ->method('get')
            ->with('http://temp')
            ->willReturn(self::getTestData('mobili.txt'));

        self::assertEquals(
            new ScrapData('189.00', 'https://www.mobili.lt/images/bigphones/nokia_nokia_g50_823045.png'),
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
            ->willReturn('<div class="prices prices_full"><span class="ltl">189,00&nbsp;&euro;</span></div>');

        self::assertEquals(
            new ScrapData('189.00', null),
            $this->scraper->scrap(new ProductRetailer(['url' => 'http://temp']))
        );
    }
}
