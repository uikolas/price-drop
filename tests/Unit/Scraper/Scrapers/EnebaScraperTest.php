<?php

declare(strict_types=1);

namespace Tests\Unit\Scraper\Scrapers;

use App\Client\HttpClientInterface;
use App\Exceptions\ScrapingFailedException;
use App\Models\ProductRetailer;
use App\Scraper\ScrapData;
use App\Scraper\Scrapers\EnebaScraper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestDataHelper;

class EnebaScraperTest extends TestCase
{
    use TestDataHelper;

    private HttpClientInterface&MockObject $client;

    private EnebaScraper $scraper;

    protected function setUp(): void
    {
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->scraper = new EnebaScraper($this->client, new Crawler());
    }

    /**
     * @dataProvider provider
     */
    public function testScrap(string $expectedPrice, string $data): void
    {
        $this->client->expects(self::once())
            ->method('get')
            ->with('http://temp')
            ->willReturn($data);

        self::assertEquals(
            new ScrapData($expectedPrice, 'https://cdn-products.eneba.com/resized-products/hafzC5AQuovHRu1jmbSFDcq0j1eTzV5uEKfbyEjDvog_350x200_1x-0.jpg'),
            $this->scraper->scrap(new ProductRetailer(['url' => 'http://temp']))
        );
    }

    public static function provider(): iterable
    {
        yield 'EU region' => [
            '54.98',
            self::getTestData('eneba.txt')
        ];

        yield 'LT region' => [
            '54.98',
            self::getTestData('eneba_lt.txt')
        ];
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
            ->willReturn('<div class="_7z2Gr"><span class="L5ErLT">10,99 €</span></div>');

        self::assertEquals(
            new ScrapData('10.99', null),
            $this->scraper->scrap(new ProductRetailer(['url' => 'http://temp']))
        );
    }
}
