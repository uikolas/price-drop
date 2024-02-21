<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductRetailer;
use App\Models\User;
use App\RetailerType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_index(): void
    {
        $product = $this->createProduct();

        $response = $this
            ->actingAs($product->user)
            ->get('/products');

        $response->assertStatus(200);
        $response->assertSeeText('Some product name');
    }

    public function test_product_index_authorization(): void
    {
        $response = $this->get('/products');

        $response->assertRedirect();
        $this->assertGuest();
    }

    public function test_product_create(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(
                '/products',
                [
                    'name' => 'Some product name',
                ]
            );

        $response->assertRedirect();
        $response = $this->followRedirects($response);
        $response->assertSee('Product added');
    }

    public function test_product_show(): void
    {
        $product = $this->createProduct();
        ProductRetailer::factory()
            ->for($product)
            ->type(RetailerType::MOBILI)
            ->create(['url' => 'http://temp']);

        $response = $this
            ->actingAs($product->user)
            ->get('/products/' . $product->id);

        $response->assertStatus(200);
        $response->assertSeeText('http://temp');
    }

    public function test_cannot_access_other_user_product(): void
    {
        $product = $this->createProduct();
        ProductRetailer::factory()
            ->for($product)
            ->type(RetailerType::MOBILI)
            ->create(['url' => 'http://temp']);

        $response = $this
            ->actingAs($this->otherUser())
            ->get('/products/' . $product->id);

        $response->assertForbidden();
    }

    public function test_product_delete(): void
    {
        $product = $this->createProduct();
        $productRetailer = ProductRetailer::factory()
            ->for($product)
            ->type(RetailerType::MOBILI)
            ->create(['url' => 'http://temp']);

        $response = $this
            ->actingAs($product->user)
            ->delete('/products/' . $product->id);

        $response->assertRedirect();
        $this->assertModelMissing($product);
        $this->assertModelMissing($productRetailer);
    }

    public function test_cannot_delete_other_user_product(): void
    {
        $product = $this->createProduct();

        $response = $this
            ->actingAs($this->otherUser())
            ->delete('/products/' . $product->id);

        $response->assertForbidden();
        self::assertNotNull(Product::find($product->id));
    }
}
