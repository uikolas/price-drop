<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Client\HttpClientInterface;
use App\Jobs\ProcessProductRetailer;
use App\Models\ProductRetailer;
use App\Notifications\PriceDrop;
use App\RetailerType;
use App\Scraper\ScraperFactory;
use App\Services\ProductRetailerProcessor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Tests\TestDataHelper;

class ProcessProductRetailerTest extends TestCase
{
    use RefreshDatabase, TestDataHelper;

    public function test_success_handle(): void
    {
        $data = $this->getTestData('mobili.txt');

        $productRetailer = ProductRetailer::factory()
            ->type(RetailerType::MOBILI)
            ->create();

        $client = $this->mock(HttpClientInterface::class);
        $client->shouldReceive('get')
            ->with($productRetailer->url)
            ->once()
            ->andReturn($data);

        $job = new ProcessProductRetailer($productRetailer);
        $job->handle($this->app->make(ProductRetailerProcessor::class));

        $updatedProductRetailer = $productRetailer->fresh();

        self::assertEquals('189.00', $updatedProductRetailer->price);
        self::assertEquals('EUR', $updatedProductRetailer->currency);
        self::assertEquals('https://www.mobili.lt/images/bigphones/nokia_nokia_g50_823045.png', $updatedProductRetailer->image);
        self::assertEquals(RetailerType::MOBILI, $updatedProductRetailer->type);
    }

    /**
     * @dataProvider product_retailer_data_provider
     */
    public function test_success_handle_and_notify(string $currentPrice, array $retailers, string $expectedPrice): void
    {
        Notification::fake();

        $data = $this->getTestData('mobili.txt');

        $product = $this->createProduct();
        $productRetailer = ProductRetailer::factory()
            ->for($product)
            ->type(RetailerType::MOBILI)
            ->price($currentPrice)
            ->create();

        foreach ($retailers as $retailer) {
            ProductRetailer::factory()
                ->for($product)
                ->type($retailer['type'])
                ->price($retailer['price'])
                ->create();
        }

        $client = $this->mock(HttpClientInterface::class);
        $client->shouldReceive('get')
            ->with($productRetailer->url)
            ->once()
            ->andReturn($data);

        (new ProcessProductRetailer($productRetailer, true))->handle($this->app->make(ProductRetailerProcessor::class));

        $updatedProductRetailer = $productRetailer->fresh();

        self::assertEquals($expectedPrice, $updatedProductRetailer->price);
        self::assertEquals(RetailerType::MOBILI, $updatedProductRetailer->type);
        Notification::assertSentTo([$product->user], PriceDrop::class);
    }

    public function product_retailer_data_provider(): iterable
    {
        yield 'price was changed to best price' => [
            '300.00',
            [
                ['type' => RetailerType::SKYTECH, 'price' => '299.99'],
                ['type' => RetailerType::AMAZON, 'price' => '199.99'],
            ],
            '189.00',
        ];

        yield 'best retailer price was changed to smaller' => [
            '190.00',
            [
                ['type' => RetailerType::SKYTECH, 'price' => '299.99'],
                ['type' => RetailerType::AMAZON, 'price' => '199.99'],
            ],
            '189.00',
        ];
    }
}
