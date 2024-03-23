<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Price;
use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{
    /**
     * @dataProvider priceLessThanAnotherDataProvider
     */
    public function testIsPriceLessThanAnother(Price $price1, ?Price $price2, bool $expectedResult): void
    {
        self::assertSame(
            $expectedResult,
            $price1->lessThan($price2),
        );
    }

    public static function priceLessThanAnotherDataProvider(): iterable
    {
        yield 'null second price' => [
            new Price('12.00'),
            null,
            true,
        ];

        yield 'first price bigger' => [
            new Price('12.01'),
            new Price('12.00'),
            false,
        ];

        yield 'first price smaller' => [
            new Price('10.00'),
            new Price('12.00'),
            true,
        ];

        yield 'equal prices' => [
            new Price('12.00'),
            new Price('12.00'),
            false,
        ];
    }

    /**
     * @dataProvider equalPricesDataProvider
     */
    public function testArePricesEqual(Price $price1, ?Price $price2, bool $expectedResult): void
    {
        self::assertSame(
            $expectedResult,
            $price1->equals($price2),
        );
    }

    public static function equalPricesDataProvider(): iterable
    {
        yield 'null second price' => [
            new Price('12.00'),
            null,
            false,
        ];

        yield 'no equal prices by one cent' => [
            new Price('12.01'),
            new Price('12.00'),
            false,
        ];

        yield 'no equal prices' => [
            new Price('0.00'),
            new Price('12.00'),
            false,
        ];

        yield 'equal prices' => [
            new Price('12.00'),
            new Price('12.00'),
            true,
        ];
    }
}
