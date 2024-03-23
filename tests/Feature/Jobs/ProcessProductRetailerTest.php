<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Client\HttpClientInterface;
use App\Exceptions\FailedHttpRequestException;
use App\Jobs\ProcessProductRetailer;
use App\Models\ProductRetailer;
use App\Notifications\PriceDrop;
use App\Price;
use App\RetailerType;
use App\Product\ProductRetailerProcessor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Tests\TestDataHelper;

class ProcessProductRetailerTest extends TestCase
{
    use RefreshDatabase, TestDataHelper;

    public function test_success_handle(): void
    {
        $now = Carbon::create(2021, 5, 21);
        Carbon::setTestNow($now);
        $data = self::getTestData('mobili.txt');

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

        self::assertEquals(new Price('189.00'), $updatedProductRetailer->price);
        self::assertEquals($now, $updatedProductRetailer->price_updated_at);
        self::assertEquals('EUR', $updatedProductRetailer->currency);
        self::assertEquals('https://www.mobili.lt/images/bigphones/nokia_nokia_g50_823045.png', $updatedProductRetailer->image);
        self::assertEquals(RetailerType::MOBILI, $updatedProductRetailer->type);
    }

    public function test_do_not_update_if_price_was_not_changed(): void
    {
        Carbon::setTestNow(Carbon::create(2021, 5, 21));
        $data = self::getTestData('mobili.txt');

        $productRetailer = ProductRetailer::factory()
            ->type(RetailerType::MOBILI)
            ->price(new Price('189.00'))
            ->priceUpdatedAt(Carbon::create(2020, 1, 10))
            ->create();

        $this->mock(HttpClientInterface::class)
            ->shouldReceive('get')
            ->with($productRetailer->url)
            ->once()
            ->andReturn($data);

        $job = new ProcessProductRetailer($productRetailer);
        $job->handle($this->app->make(ProductRetailerProcessor::class));

        $updatedProductRetailer = $productRetailer->fresh();

        self::assertEquals(new Price('189.00'), $updatedProductRetailer->price);
        self::assertEquals(Carbon::create(2020, 1, 10), $updatedProductRetailer->price_updated_at);
        self::assertEquals(RetailerType::MOBILI, $updatedProductRetailer->type);
    }

    /**
     * @dataProvider product_retailer_data_provider
     */
    public function test_success_handle_and_notify(string $currentPrice): void
    {
        Notification::fake();

        $data = self::getTestData('mobili.txt');

        $product = $this->createProduct();
        $productRetailer = ProductRetailer::factory()
            ->for($product)
            ->type(RetailerType::MOBILI)
            ->price(new Price($currentPrice))
            ->create();

        // Additional retailers
        ProductRetailer::factory()
            ->for($product)
            ->type(RetailerType::SKYTECH)
            ->price(new Price('299.99'))
            ->create();

        ProductRetailer::factory()
            ->for($product)
            ->type(RetailerType::AMAZON)
            ->price(new Price('199.99'))
            ->create();

        $this->mock(HttpClientInterface::class)
            ->shouldReceive('get')
            ->with($productRetailer->url)
            ->once()
            ->andReturn($data);

        $job = new ProcessProductRetailer($productRetailer, true);
        $job->handle($this->app->make(ProductRetailerProcessor::class));

        $updatedProductRetailer = $productRetailer->fresh();

        self::assertEquals(new Price('189.00'), $updatedProductRetailer->price);
        self::assertEquals(RetailerType::MOBILI, $updatedProductRetailer->type);
        Notification::assertSentTo([$product->user], PriceDrop::class);
    }

    public static function product_retailer_data_provider(): iterable
    {
        yield 'price was changed to best price' => [
            '300.00',
        ];

        yield 'best retailer price was changed to smaller' => [
            '190.00',
        ];
    }

    public function test_reset_price_if_response_was_not_found(): void
    {
        $productRetailer = ProductRetailer::factory()
            ->type(RetailerType::MOBILI)
            ->create(
                [
                    'image' => 'https://www.mobili.lt/images/bigphones/nokia_nokia_g50_823045.png',
                ]
            );

        $this->mock(HttpClientInterface::class)
            ->shouldReceive('get')
            ->with($productRetailer->url)
            ->once()
            ->andThrow(FailedHttpRequestException::createWithStatusCode($productRetailer->url, 404));

        $job = new ProcessProductRetailer($productRetailer);
        $job->handle($this->app->make(ProductRetailerProcessor::class));

        $updatedProductRetailer = $productRetailer->fresh();

        self::assertEquals(null, $updatedProductRetailer->price);
        self::assertEquals(null, $updatedProductRetailer->price_updated_at);
        self::assertEquals('https://www.mobili.lt/images/bigphones/nokia_nokia_g50_823045.png', $updatedProductRetailer->image);
        self::assertEquals(RetailerType::MOBILI, $updatedProductRetailer->type);
    }
}
