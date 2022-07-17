<?php

namespace App\Jobs;

use App\Exceptions\ScraperNotFoundException;
use App\Exceptions\ScrapingFailedException;
use App\Models\ProductRetailer;
use App\Notifications\PriceDrop;
use App\Scraper\ScrapData;
use App\Scraper\ScraperFactory;
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
    public function handle(ScraperFactory $scraperFactory): void
    {
        $product = $this->productRetailer->product;
        $bestRetailer = $product->bestRetailer();

        $data = $scraperFactory
            ->createFromRetailer($this->productRetailer)
            ->scrap($this->productRetailer);

        $this->updateProductRetailer($this->productRetailer, $data);

        $this->productRetailer->save();

        if ($this->notify && $this->productRetailer->hasLowerPriceThan($bestRetailer)) {
            $product->user->notify(new PriceDrop($this->productRetailer));
        }
    }

    /**
     * Calculate the number of seconds to wait before retrying the job.
     */
    public function backoff(): array
    {
        return [5, 10, 15];
    }

    private function updateProductRetailer(ProductRetailer $productRetailer, ScrapData $data): void
    {
        $productRetailer->price = $data->getPrice();

        if ($data->getCurrency() !== null) {
            $productRetailer->currency = $data->getCurrency();
        }

        if ($data->getImage() !== null) {
            $productRetailer->image = $data->getImage();
        }
    }
}
