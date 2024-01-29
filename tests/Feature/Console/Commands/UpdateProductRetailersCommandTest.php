<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use App\Jobs\ProcessProductRetailer;
use App\Models\ProductRetailer;
use App\RetailerType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class UpdateProductRetailersCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_dispatch_job(): void
    {
        Bus::fake();

        ProductRetailer::factory()
            ->type(RetailerType::MOBILI)
            ->create();

        $this->artisan('retailers:update')->assertExitCode(0);

        Bus::assertDispatched(ProcessProductRetailer::class);
    }

    public function test_do_not_dispatch_job_if_no_product_retailers_found(): void
    {
        Bus::fake();

        $this->artisan('retailers:update')->assertExitCode(0);

        Bus::assertNotDispatched(ProcessProductRetailer::class);
    }
}
