<?php

declare(strict_types=1);

namespace App\Scraper;

class ScrapData
{
    public const CURRENCY_CURRENCY = 'EUR';

    public function __construct(
        private readonly string $price,
        private readonly ?string $image,
        private readonly ?string $currency = self::CURRENCY_CURRENCY,
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
