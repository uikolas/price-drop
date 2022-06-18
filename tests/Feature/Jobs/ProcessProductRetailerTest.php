<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Client\HttpClientInterface;
use App\Jobs\ProcessProductRetailer;
use App\Models\Product;
use App\Models\ProductRetailer;
use App\Notifications\PriceDrop;
use App\RetailerType;
use App\Scraper\ScraperFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

        $job = new ProcessProductRetailer($productRetailer->id);
        $job->handle($this->app->make(ScraperFactory::class));

        $updatedProductRetailer = $productRetailer->fresh();

        self::assertEquals('189.00', $updatedProductRetailer->price);
        self::assertEquals(RetailerType::MOBILI, $updatedProductRetailer->type);
    }

    public function test_throw_exception_if_model_not_found(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $productRetailer = new ProductRetailer();
        $productRetailer->id = 123;

        (new ProcessProductRetailer($productRetailer->id))->handle($this->app->make(ScraperFactory::class));
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
            ->create(['price' => $currentPrice]);

        foreach ($retailers as $retailer) {
            ProductRetailer::factory()
                ->for($product)
                ->type($retailer['type'])
                ->create(['price' => $retailer['price']]);
        }

        $client = $this->mock(HttpClientInterface::class);
        $client->shouldReceive('get')
            ->with($productRetailer->url)
            ->once()
            ->andReturn($data);

        (new ProcessProductRetailer($productRetailer->id, true))->handle($this->app->make(ScraperFactory::class));

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
