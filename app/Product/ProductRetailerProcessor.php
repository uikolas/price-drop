<?php

declare(strict_types=1);

namespace App\Product;

use App\Exceptions\FailedHttpRequestException;
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
     * @throws ScraperNotFoundException|ScrapingFailedException|FailedHttpRequestException
     */
    public function process(ProductRetailer $productRetailer, bool $notify): void
    {
        $product = $productRetailer->product;
        $bestRetailer = $product->bestRetailer();
        $scraper = $this->scraperFactory->createFromRetailer($productRetailer);

        try {
            $data = $scraper->scrap($productRetailer);
        } catch (FailedHttpRequestException $e) {
            if ($e->isNotFound()) {
                $productRetailer->price = null;
                $productRetailer->save();

                return;
            }

            throw $e;
        }

        $this->updateProductRetailer($productRetailer, $data);

        if ($notify && $productRetailer->price?->lessThan($bestRetailer?->price)) {
            $this->notification->send($product->user, new PriceDrop($productRetailer));
        }
    }

    private function updateProductRetailer(ProductRetailer $productRetailer, ScrapData $data): void
    {
        if (!$productRetailer->price?->equals($data->getPrice())) {
            $productRetailer->price = $data->getPrice();
        }

        if ($data->getImage() !== null) {
            $productRetailer->image = $data->getImage();
        }

        $productRetailer->save();
    }
}
