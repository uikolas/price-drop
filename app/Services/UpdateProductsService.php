<?php

namespace App\Services;

use App\Notifications\PriceDrop;
use App\Product;
use Illuminate\Database\Eloquent\Collection;

class UpdateProductsService
{


    /**
     * @var UpdateProductRetailersService
     */
    private $updateProductRetailersService;

    /**
     * UpdateProductsService constructor.
     * @param UpdateProductRetailersService $updateProductRetailersService
     */
    public function __construct(UpdateProductRetailersService $updateProductRetailersService)
    {

        $this->updateProductRetailersService = $updateProductRetailersService;
    }

    /**
     * @param Collection|Product[] $products
     */
    public function updateAndNotify(Collection $products)
    {
        foreach ($products as $product) {
            $oldBestProductRetailer = $product->getBestProductRetailer();
            $oldBestPrice           = $oldBestProductRetailer ? $oldBestProductRetailer->getPrice() : null;

            $this->updateProductRetailersService->update($product->getProductRetailers());

            $newBestProductRetailer = $product->getBestProductRetailer();
            $newBestPrice           = $newBestProductRetailer ? $newBestProductRetailer->getPrice() : null;

            if ($newBestPrice) {
                $sendNotification = $oldBestPrice ? ($newBestPrice->lessThan($oldBestPrice) ? true : false) : true;

                if ($sendNotification) {
                    $product->getUser()->notify(new PriceDrop($newBestProductRetailer));
                }
            }
        }
    }
}
