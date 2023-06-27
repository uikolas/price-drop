<?php

namespace App\Jobs;

use App\Exceptions\ScraperNotFoundException;
use App\Exceptions\ScrapingFailedException;
use App\Models\ProductRetailer;
use App\Notifications\PriceDrop;
use App\Scraper\ScrapData;
use App\Scraper\ScraperFactory;
use App\Services\ProductRetailerProcessor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessProductRetailer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Delete the job if its models no longer exist.
     */
    public bool $deleteWhenMissingModels = true;

    public function __construct(
        private readonly ProductRetailer $productRetailer,
        private readonly bool $notify = false,
    ) {
    }

    /**
     * @throws ScraperNotFoundException|ScrapingFailedException
     */
    public function handle(ProductRetailerProcessor $productRetailerProcessor): void
    {
        $productRetailerProcessor->process($this->productRetailer, $this->notify);
    }

    /**
     * Calculate the number of seconds to wait before retrying the job.
     */
    public function backoff(): array
    {
        return [5, 10, 15];
    }
}
