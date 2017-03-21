<?php

namespace Tests\Feature;

use App\Product;
use App\ProductRetailer;
use App\Retailer;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductRetailerFeatureTest extends TestCase
{
    use DatabaseMigrations;

    public function testProductRetailerCreate()
    {
        $randomUrl  = \Faker\Factory::create()->url;
        $randomName = \Faker\Factory::create()->company;

        $user = factory(User::class)->make();

        $product = factory(Product::class)->create([
            'user_id' => $user->id
        ]);

        $retailer = factory(Retailer::class)->create([
            'name' => $randomName
        ]);

        $this
            ->actingAs($user)
            ->visit('/product-retailers/create/'.$product->id)
            ->type($randomUrl, 'url')
            ->select($retailer->id, 'retailer')
            ->press('Add')

            ->seeRouteIs('products.show', ['product' => $product->id])
            ->see('Product retailer added')
            ->see($randomName);
    }

    public function testProductRetailerDelete()
    {
        $randomUrl  = \Faker\Factory::create()->url;

        $user = factory(User::class)->make();

        $product = factory(Product::class)->create([
            'name'    => 'Some name',
            'user_id' => $user->id
        ]);

        factory(ProductRetailer::class)->create([
            'product_id' => $product->id,
            'url'        => $randomUrl
        ]);

        $this
            ->actingAs($user)
            ->visit('/products/'.$product->id)
            ->press('delete-product-retailer')

            ->seeRouteIs('products.show', ['product' => $product->id])
            ->see('Product retailer successful deleted')
            ->see('Some name')
            ->dontSee($randomUrl)
        ;
    }

    public function testCantAccessProductRetailerCreate()
    {
        $this
            ->visit('/product-retailers/create/1')

            ->seeRouteIs('login');
    }
}
