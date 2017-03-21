<?php

namespace Tests\Unit;

use App\Product;
use App\ProductRetailer;
use App\User;
use Money\Money;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductTest extends TestCase
{
    use DatabaseMigrations;

    public function testBestProductRetailers()
    {
        $user = factory(User::class)->make();

        /*** @var $product Product */
        $product = factory(Product::class)->create([
            'user_id' => $user->id
        ]);

        $bestProductRetailer = factory(ProductRetailer::class)->create([
            'product_id' => $product->id,
            'price'      => 1000
        ]);

        $productRetailers = factory(ProductRetailer::class, 3)->create([
            'product_id' => $product->id,
            'price'      => 2000
        ]);

        $best = $product->getBestProductRetailer();

        $this->assertEquals('1000', $best->getPrice()->getAmount());
    }

    public function testNullBestProductRetailer()
    {
        $user = factory(User::class)->make();

        /*** @var $product Product */
        $product = factory(Product::class)->create([
            'user_id' => $user->id
        ]);

        $this->assertNull($product->getBestProductRetailer());
    }

    public function testNullBestProductRetailerList()
    {
        $user = factory(User::class)->make();

        /*** @var $product Product */
        $product = factory(Product::class)->create([
            'user_id' => $user->id
        ]);

        $productRetailers = factory(ProductRetailer::class, 3)->create([
            'product_id' => $product->id,
            'price'      => null
        ]);

        $best = $product->getBestProductRetailer();

        $this->assertNull($best);
    }
}
