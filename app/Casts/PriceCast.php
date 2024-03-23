<?php

declare(strict_types=1);

namespace App\Casts;

use App\Price;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class PriceCast implements CastsAttributes
{
    private const SCALE = 2;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Price
    {
        if (!isset($attributes['price'])) {
            return null;
        }

        $value = (string) BigDecimal::of($value)->toScale(self::SCALE, RoundingMode::HALF_UP);

        return new Price($value, $attributes['currency'] ?? Price::DEFAULT_CURRENCY);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if ($value === null) {
            return [
                'price' => null,
                'currency' => null,
            ];
        }

        if (!$value instanceof Price) {
            throw new \InvalidArgumentException('The given value is not an instance of Price.');
        }

        return [
            'price' => $value->getValue(),
            'currency' => $value->getCurrency(),
        ];
    }
}
