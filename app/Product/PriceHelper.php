<?php

declare(strict_types=1);

namespace App\Product;

class PriceHelper
{
    public static function cleanPrice(string $price): string
    {
        return \trim(preg_replace('/[^\d.]/', '', $price));
    }
}
