<?php

namespace App\Jobs;

use App\Exceptions\ScraperNotFoundException;
use App\Exceptions\ScrapingFailedException;
use App\Models\ProductRetailer;
use App\Notifications\PriceDrop;
use App\Scraper\ScraperFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessProductRetailer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly int $productRetailerId,
        private readonly bool $notify = false,
    ) {
    }

    /**
     * @throws ScraperNotFoundException|ScrapingFailedException|ModelNotFoundException
     */
    public function handle(ScraperFactory $scraperFactory): void
    {
        $productRetailer = ProductRetailer::findOrFail($this->productRetailerId);
        $product = $productRetailer->product;
        $bestRetailer = $product->bestRetailer();

        $data = $scraperFactory
            ->createFromRetailer($productRetailer)
            ->scrap($productRetailer);

        $productRetailer->price = $data->getPrice();
        $productRetailer->save();

        if ($this->notify && $productRetailer->hasLowerPriceThan($bestRetailer)) {
            $product->user->notify(new PriceDrop($productRetailer));
        }
    }
}
