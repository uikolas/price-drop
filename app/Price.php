<?php

declare(strict_types=1);

namespace App;

final class Price
{
    public const DEFAULT_CURRENCY = 'EUR';

    public function __construct(
        private readonly string $value,
        private readonly string $currency = self::DEFAULT_CURRENCY,
    ) {
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function lessThan(?Price $anotherPrice): bool
    {
        if ($anotherPrice === null) {
            return true;
        }

        return \bccomp($this->value, $anotherPrice->getValue(), 2) === -1;
    }

    public function equals(?Price $anotherPrice): bool
    {
        if ($anotherPrice === null) {
            return false;
        }

        return \bccomp($this->value, $anotherPrice->getValue(), 2) === 0;
    }
}
