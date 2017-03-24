<?php

namespace Tests\Unit\Services;

use App\Notifications\PriceDrop;
use App\Parse\ParseObject;
use App\Product;
use App\ProductRetailer;
use App\Services\ParserService;
use App\Services\UpdateProductRetailersService;
use App\Services\UpdateProductsService;
use App\User;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Money\Currency;
use Money\Money;
use Tests\TestCase;

class UpdateProductsServiceTest extends TestCase
{
    use DatabaseMigrations;

    public function testNotifyWhenNewBestPriceIsLessThanOldPrice()
    {
        Notification::fake();

        $user = factory(User::class)->create();

        $product = factory(Product::class)->create([
            'user_id' => $user->id
        ]);

        $productRetailers = factory(ProductRetailer::class)->create([
            'product_id'  => $product->id,
            'price'       => 9999
        ]);

        $updateService = $this->getUpdateProductService(
            new ParseObject(new Money(100, new Currency('EUR')))
        );

        $updateService->updateAndNotify(new Collection([$product]));

        Notification::assertSentTo(
            [$user],
            PriceDrop::class
        );
    }

    public function testDontNotifyWhenBestPriceIsGreaterThanOldPrice()
    {
        Notification::fake();

        $user = factory(User::class)->create();

        $product = factory(Product::class)->create([
            'user_id' => $user->id
        ]);

        $productRetailers = factory(ProductRetailer::class)->create([
            'product_id'  => $product->id,
            'price'       => 10
        ]);

        $updateService = $this->getUpdateProductService(
            new ParseObject(new Money(100, new Currency('EUR')))
        );

        $updateService->updateAndNotify(new Collection([$product]));

        Notification::assertNotSentTo(
            [$user],
            PriceDrop::class
        );
    }

    public function testNotifyWhenOldPriceWasNull()
    {
        Notification::fake();

        $user = factory(User::class)->create();

        $product = factory(Product::class)->create([
            'user_id' => $user->id
        ]);

        $productRetailers = factory(ProductRetailer::class)->create([
            'product_id'  => $product->id,
            'price'       => null
        ]);

        $updateService = $this->getUpdateProductService(
            new ParseObject(new Money(100, new Currency('EUR')))
        );

        $updateService->updateAndNotify(new Collection([$product]));

        Notification::assertSentTo(
            [$user],
            PriceDrop::class
        );
    }

    public function testDontNotifyWhenBestPriceWasNull()
    {
        Notification::fake();

        $user = factory(User::class)->create();

        $product = factory(Product::class)->create([
            'user_id' => $user->id
        ]);

        $productRetailers = factory(ProductRetailer::class)->create([
            'product_id'  => $product->id,
            'price'       => 10
        ]);

        $updateService = $this->getUpdateProductService(
            new ParseObject(null)
        );

        $updateService->updateAndNotify(new Collection([$product]));

        Notification::assertNotSentTo(
            [$user],
            PriceDrop::class
        );
    }
    /**
     * @param ParseObject $parseObject
     * @return UpdateProductsService
     */
    private function getUpdateProductService(ParseObject $parseObject)
    {
        $parserServiceMock = $this->getMockBuilder(ParserService::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $logger = $this->getMockBuilder(Log::class)->getMock();

        $parserServiceMock->expects($this->once())->method('parseProductRetailer')->willReturn($parseObject);

        $updateProductsService = new UpdateProductRetailersService($parserServiceMock, $logger);

        return new UpdateProductsService($updateProductsService);
    }
}
