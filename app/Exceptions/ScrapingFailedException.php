<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Models\ProductRetailer;

class ScrapingFailedException extends \Exception
{
    public static function create(\Throwable $exception): self
    {
        return new self('Failed to scrap data', 0, $exception);
    }

    public static function createPriceNotFound(ProductRetailer $productRetailer): self
    {
        return new self(
            \sprintf(
                'Price not found for product retailer with id: %s and url: %s',
                $productRetailer->id,
                $productRetailer->url,
            ),
        );
    }
}
