<?php

namespace Tests\Unit;

use App\ProductRetailer;
use Money\Money;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductRetailerTest extends TestCase
{
    use DatabaseMigrations;

    public function testGetPrice()
    {
        /*** @var $productRetailer ProductRetailer */
        $productRetailer = factory(ProductRetailer::class)->make([
            'price' => 10256
        ]);

        $price = $productRetailer->getPrice();

        $this->assertInstanceOf(Money::class, $price);
        $this->assertEquals('10256', $price->getAmount());
    }

    public function testNullGetPrice()
    {
        /*** @var $productRetailer ProductRetailer */
        $productRetailer = factory(ProductRetailer::class)->make([
            'price' => null
        ]);

        $this->assertNull($productRetailer->getPrice());
    }
}
