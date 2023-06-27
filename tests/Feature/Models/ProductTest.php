<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Product;
use App\Models\ProductRetailer;
use App\RetailerType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_best_retailer(): void
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        ProductRetailer::factory()
            ->for($product)
            ->type(RetailerType::SKYTECH)
            ->create();

        ProductRetailer::factory()
            ->for($product)
            ->type(RetailerType::MOBILI)
            ->price('760.00')
            ->create();

        ProductRetailer::factory()
            ->for($product)
            ->type(RetailerType::AMAZON)
            ->price('1499.00')
            ->create();

        $bestRetailer = $product->bestRetailer();
        self::assertSame('760.00',  $bestRetailer->price);
        self::assertTrue($bestRetailer->hasType(RetailerType::MOBILI));
    }
}
