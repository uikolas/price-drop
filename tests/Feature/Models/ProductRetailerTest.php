<?php

declare(strict_types=1);

namespace Models;

use App\Models\ProductRetailer;
use App\RetailerType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductRetailerTest extends TestCase
{
    use RefreshDatabase;

    public function test_lower_price(): void
    {
        /** @var ProductRetailer $productRetailer */
        $productRetailer = ProductRetailer::factory()
            ->type(RetailerType::SKYTECH)
            ->create(['price' => '99.97']);

        $bestProductRetailer = ProductRetailer::factory()
            ->type(RetailerType::MOBILI)
            ->create(['price' => '99.98']);

        self::assertTrue(
            $productRetailer->hasLowerPriceThan($bestProductRetailer)
        );
    }

    public function test_with_null_bet_product_retailer(): void
    {
        /** @var ProductRetailer $productRetailer */
        $productRetailer = ProductRetailer::factory()
            ->type(RetailerType::SKYTECH)
            ->create(['price' => '99.97']);

        self::assertTrue(
            $productRetailer->hasLowerPriceThan(null)
        );
    }

    public function test_lower_price_with_null_price(): void
    {
        /** @var ProductRetailer $productRetailer */
        $productRetailer = ProductRetailer::factory()
            ->type(RetailerType::SKYTECH)
            ->create();

        $bestProductRetailer = ProductRetailer::factory()
            ->type(RetailerType::MOBILI)
            ->create(['price' => '99.98']);

        self::assertFalse(
            $productRetailer->hasLowerPriceThan($bestProductRetailer)
        );
    }
}
