<?php

declare(strict_types=1);

namespace App\Product;

use App\Exceptions\ScraperNotFoundException;
use App\Exceptions\ScrapingFailedException;
use App\Models\ProductRetailer;
use App\Notifications\PriceDrop;
use App\Scraper\ScrapData;
use App\Scraper\ScraperFactory;
use Illuminate\Contracts\Notifications\Dispatcher as NotificationDispatcher;

class ProductRetailerProcessor
{
    public function __construct(
        private readonly ScraperFactory $scraperFactory,
        private readonly NotificationDispatcher $notification,
    ) {
    }

    /**
     * @throws ScraperNotFoundException|ScrapingFailedException
     */
    public function process(ProductRetailer $productRetailer, bool $notify): void
    {
        $product = $productRetailer->product;
        $bestRetailer = $product->bestRetailer();

        $data = $this->scraperFactory
            ->createFromRetailer($productRetailer)
            ->scrap($productRetailer);

        $this->updateProductRetailer($productRetailer, $data);

        if ($notify && $productRetailer->hasLowerPriceThan($bestRetailer)) {
            $this->notification->send($product->user, new PriceDrop($productRetailer));
        }
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

        $productRetailer->save();
    }
}
