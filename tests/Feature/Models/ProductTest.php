<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Product;
use App\Models\ProductRetailer;
use App\Price;
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
            ->price(new Price('760.00'))
            ->create();

        ProductRetailer::factory()
            ->for($product)
            ->type(RetailerType::AMAZON)
            ->price(new Price('1499.11'))
            ->create();

        $bestRetailer = $product->bestRetailer();
        self::assertEquals(new Price('760.00'), $bestRetailer->price);
        self::assertTrue($bestRetailer->hasType(RetailerType::MOBILI));
    }
}
