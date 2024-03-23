<?php

declare(strict_types=1);

namespace App\Scraper;

use App\Price;

class ScrapData
{
    public function __construct(
        private readonly Price $price,
        private readonly ?string $image,
    ) {
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
}
