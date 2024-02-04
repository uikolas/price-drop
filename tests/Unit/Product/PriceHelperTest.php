<?php

declare(strict_types=1);

namespace Tests\Unit\Product;

use App\Product\PriceHelper;
use PHPUnit\Framework\TestCase;

class PriceHelperTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testCleanPrice(string $expectedPrice, string $price): void
    {
        self::assertSame(
            $expectedPrice,
            PriceHelper::cleanPrice($price)
        );
    }

    public static function dataProvider(): iterable
    {
        yield [
            '10.99',
            '€10.99'
        ];

        yield [
            '10.99',
            '€ 10.99'
        ];

        yield [
            '10.99',
            '10.99'
        ];

        yield [
            '10.99',
            '10.99 €'
        ];

        yield [
            '10.99',
            '10.99€'
        ];
    }
}
