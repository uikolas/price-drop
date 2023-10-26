<?php

declare(strict_types=1);

namespace Tests\Unit\Product;

use App\Product\PriceComparator;
use PHPUnit\Framework\TestCase;

class PriceComparatorTest extends TestCase
{
    private PriceComparator $comparator;

    protected function setUp(): void
    {
        $this->comparator = new PriceComparator();
    }

    /**
     * @dataProvider priceLessThanAnotherDataProvider
     */
    public function testIsPriceLessThanAnother(?string $price1, ?string $price2, bool $expectedResult): void
    {
        self::assertSame(
            $expectedResult,
            $this->comparator->isPriceLessThanAnother($price1, $price2),
        );
    }

    public static function priceLessThanAnotherDataProvider(): iterable
    {
        yield 'null all prices' => [
            null,
            null,
            false,
        ];

        yield 'null second price' => [
            '12.00',
            null,
            true,
        ];

        yield 'null first price' => [
            null,
            '12.00',
            false,
        ];

        yield 'first price bigger' => [
            '12.01',
            '12.00',
            false,
        ];

        yield 'first price smaller' => [
            '10.00',
            '12.00',
            true,
        ];

        yield 'equal prices' => [
            '12.00',
            '12.00',
            false,
        ];
    }

    /**
     * @dataProvider equalPricesDataProvider
     */
    public function testArePricesEqual(?string $price1, ?string $price2, bool $expectedResult): void
    {
        self::assertSame(
            $expectedResult,
            $this->comparator->arePricesEqual($price1, $price2),
        );
    }

    public static function equalPricesDataProvider(): iterable
    {
        yield 'null all prices' => [
            null,
            null,
            false,
        ];

        yield 'null second price' => [
            '12.00',
            null,
            false,
        ];

        yield 'null first price' => [
            null,
            '12.00',
            false,
        ];

        yield 'no equal prices by one cent' => [
            '12.01',
            '12.00',
            false,
        ];

        yield 'no equal prices' => [
            '0.00',
            '12.00',
            false,
        ];

        yield 'equal prices' => [
            '12.00',
            '12.00',
            true,
        ];
    }
}
