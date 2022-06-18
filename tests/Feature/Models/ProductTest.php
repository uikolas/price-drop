<?php

declare(strict_types=1);

namespace Models;

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
            ->create(['price' => '99.98']);

        ProductRetailer::factory()
            ->for($product)
            ->type(RetailerType::AMAZON)
            ->create(['price' => '99.99']);

        $bestRetailer = $product->bestRetailer();
        self::assertSame('99.98',  $bestRetailer->price);
        self::assertTrue($bestRetailer->hasType(RetailerType::MOBILI));
    }
}
