<?php

declare(strict_types=1);

namespace App\Scraper;

class ScrapData
{
    public function __construct(
        private readonly string $price,
    ) {
    }

    public function getPrice(): string
    {
        return $this->price;
    }
}
