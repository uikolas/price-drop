<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Jobs\ProcessProductRetailer;
use App\Models\ProductRetailer;
use App\RetailerType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class TriggerProductRetailerUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_trigger(): void
    {
        Bus::fake();

        /** @var ProductRetailer $productRetailer */
        $productRetailer = ProductRetailer::factory()
            ->type(RetailerType::MOBILI)
            ->create();

        $response = $this
            ->actingAs($productRetailer->product->user)
            ->post('/retailers/' . $productRetailer->id . '/trigger');

        $response->assertRedirect();
        Bus::assertDispatched(ProcessProductRetailer::class);
    }

    public function test_cannot_trigger_other_customer_retailer(): void
    {
        Bus::fake();

        /** @var ProductRetailer $productRetailer */
        $productRetailer = ProductRetailer::factory()
            ->type(RetailerType::MOBILI)
            ->create();

        $response = $this
            ->actingAs($this->otherUser())
            ->post('/retailers/' . $productRetailer->id . '/trigger');

        $response->assertForbidden();
        Bus::assertNotDispatched(ProcessProductRetailer::class);
    }
}
