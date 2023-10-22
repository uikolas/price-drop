<?php

declare(strict_types=1);

namespace App\Product;

class PriceComparator
{
    public function isPriceLessThanAnother(?string $price, ?string $anotherPrice): bool
    {
        if ($price === null && $anotherPrice === null) {
            return false;
        }

        if ($anotherPrice === null) {
            return true;
        }

        if ($price === null) {
            return false;
        }

        return \bccomp($price, $anotherPrice, 2) === -1;
    }

    public function arePricesEqual(?string $price, ?string $anotherPrice): bool
    {
        if ($price === null || $anotherPrice === null) {
            return false;
        }

        return \bccomp($price, $anotherPrice, 2) === 0;
    }
}
