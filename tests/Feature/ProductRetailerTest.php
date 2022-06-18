<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Jobs\ProcessProductRetailer;
use App\Models\Product;
use App\Models\ProductRetailer;
use App\RetailerType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class ProductRetailerTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_retailer_create(): void
    {
        Bus::fake();

        $product = $this->createProduct();

        $response = $this
            ->actingAs($product->user)
            ->post(
                '/products/' . $product->id . '/retailers',
                [
                    'url' => 'http://temp.com',
                    'type' => 'skytech',
                ]
            );

        $response->assertRedirect();
        $response = $this->followRedirects($response);
        $response->assertSee('Product retailer added');
        $response->assertSee('http://temp.com');
        Bus::assertDispatched(ProcessProductRetailer::class);
    }

    public function test_validate_product_retailer_create(): void
    {
        Bus::fake();

        $product = $this->createProduct();

        $response = $this
            ->actingAs($product->user)
            ->post(
                '/products/' . $product->id . '/retailers',
                [
                    'url' => 'temp',
                    'type' => 'temp',
                ]
            );

        $product = $product->fresh();
        self::assertEmpty($product->productRetailers);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['url', 'type']);
        Bus::assertNotDispatched(ProcessProductRetailer::class);
    }

    public function test_validate_if_same_retailer_added(): void
    {
        Bus::fake();

        /** @var ProductRetailer $productRetailer */
        $productRetailer = ProductRetailer::factory()
            ->type(RetailerType::MOBILI)
            ->create();
        $product = $productRetailer->product;

        $response = $this
            ->actingAs($product->user)
            ->post(
                '/products/' . $product->id . '/retailers',
                [
                    'url' => 'http://temp',
                    'type' => RetailerType::MOBILI->value,
                ]
            );

        $product = $product->fresh();
        self::assertCount(1, $product->productRetailers);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['type']);
        Bus::assertNotDispatched(ProcessProductRetailer::class);
        Bus::assertNotDispatched(ProcessProductRetailer::class);
    }

    public function test_cannot_create_product_retailer_for_other_user(): void
    {
        Bus::fake();

        $product = $this->createProduct();

        $response = $this
            ->actingAs($this->otherUser())
            ->post(
                '/products/' . $product->id . '/retailers',
                [
                    'url' => 'http://temp.com',
                    'type' => 'skytech',
                ]
            );

        $response->assertForbidden();
        Bus::assertNotDispatched(ProcessProductRetailer::class);
    }

    public function test_product_retailer_delete(): void
    {
        /** @var ProductRetailer $productRetailer */
        $productRetailer = ProductRetailer::factory()
            ->type(RetailerType::MOBILI)
            ->create();

        $response = $this
            ->actingAs($productRetailer->product->user)
            ->delete('/retailers/' . $productRetailer->id);

        $response->assertRedirect();
        self::assertNull(Product::find($productRetailer->id));
    }

    public function test_cannot_delete_product_retailer_for_other_user(): void
    {
        /** @var ProductRetailer $productRetailer */
        $productRetailer = ProductRetailer::factory()
            ->type(RetailerType::MOBILI)
            ->create();

        $response = $this
            ->actingAs($this->otherUser())
            ->delete('/retailers/' . $productRetailer->id);

        $response->assertForbidden();
    }
}
