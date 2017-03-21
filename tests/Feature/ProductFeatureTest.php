<?php

namespace Tests\Feature;

use App\Product;
use App\ProductRetailer;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductFeatureTest extends TestCase
{
    use DatabaseMigrations;

    public function testProductIndex()
    {
        $randomName = \Faker\Factory::create()->text(100);

        $user    = factory(User::class)->make();
        $product = factory(Product::class)->create([
            'name'    => $randomName,
            'user_id' => $user->id
        ]);

        $this
            ->actingAs($user)
            ->visit('/products')

            ->seeRouteIs('products.index')
            ->see($randomName)
        ;
    }

    public function testProductCreate()
    {
        $randomName = \Faker\Factory::create()->text(100);

        $user = factory(User::class)->make();

        $this
            ->actingAs($user)
            ->visit('/products/create')
            ->type($randomName, 'name')
            ->press('Add')

            ->seeRouteIs('products.show', ['product' => 1])
            ->see('Product added')
            ->see($randomName)
        ;
    }

    public function testProductDelete()
    {
        $user = factory(User::class)->make();

        $product = factory(Product::class)->create([
            'name'    => 'Some name',
            'user_id' => $user->id
        ]);

        factory(ProductRetailer::class)->create([
            'product_id' => $product->id
        ]);

        $this
            ->actingAs($user)
            ->visit('/products/'.$product->id)
            ->press('Delete Product')

            ->seeRouteIs('products.index')
            ->see('Product successful deleted')
            ->see('No products')
        ;
    }

    public function testCantAccessProductIndex()
    {
        $this
            ->visit('/products')
            ->seeRouteIs('login');
    }
}
