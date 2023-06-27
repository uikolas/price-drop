<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ProductRetailer;

class ProductRetailerComparator
{
    public function isPriceLessThan(ProductRetailer $productRetailer, ?ProductRetailer $otherProductRetailer): bool
    {
        if ($otherProductRetailer === null) {
            return true;
        }

        if ($productRetailer->price === null) {
            return false;
        }

        return \bccomp($productRetailer->price, $otherProductRetailer->price, 2) === -1;
    }
}
