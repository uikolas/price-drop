<?php

namespace Tests\Services\Unit;

use App\Parse\ParseObject;
use App\Product;
use App\ProductRetailer;
use App\Retailer;
use App\Services\ParserService;
use App\Services\UpdateProductRetailersService;
use App\Services\UpdateProductsService;
use App\User;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Money\Currency;
use Money\Money;
use Psr\Log\NullLogger;
use Tests\TestCase;

class UpdateProductRetailersServiceTest extends TestCase
{
    use DatabaseMigrations;

    public function testUpdatePrices()
    {
        $updateProductsService = $this->getUpdateProductRetailersService(
            new ParseObject(new Money(1000, new Currency('EUR')))
        );

        $user = factory(User::class)->make();

        $product = factory(Product::class)->create([
            'user_id' => $user->id
        ]);

        $productRetailers = factory(ProductRetailer::class)->make([
            'product_id'  => $product->id
        ]);

        $updateProductsService->update(new Collection([$productRetailers]));

        $this->assertInstanceOf(Money::class, $productRetailers->getPrice());
        $this->assertEquals('1000', $productRetailers->getPrice()->getAmount());
    }

    public function testNullUpdatePrices()
    {
        $updateProductsService = $this->getUpdateProductRetailersService(new ParseObject(null));

        $user = factory(User::class)->make();

        $product = factory(Product::class)->create([
            'user_id' => $user->id
        ]);

        $productRetailers = factory(ProductRetailer::class)->make([
            'product_id'  => $product->id
        ]);

        $updateProductsService->update(new Collection([$productRetailers]));

        $this->assertNull($productRetailers->getPrice());
    }

    /**
     * @param ParseObject $parseObject
     * @return UpdateProductRetailersService
     */
    private function getUpdateProductRetailersService(ParseObject $parseObject)
    {
        $parserServiceMock = $this->getMockBuilder(ParserService::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $logger = $this->getMockBuilder(Log::class)->getMock();

        $parserServiceMock->expects($this->once())->method('parseProductRetailer')->willReturn($parseObject);

        return new UpdateProductRetailersService($parserServiceMock, $logger);
    }
}
