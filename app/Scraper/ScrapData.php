<?php

declare(strict_types=1);

namespace App\Scraper;

class ScrapData
{
    public function __construct(
        private readonly ?string $price,
        private readonly ?string $currency,
        private readonly ?string $image,
    ) {
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
}
