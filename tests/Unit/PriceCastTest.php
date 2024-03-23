<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Casts\PriceCast;
use App\Price;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

class PriceCastTest extends TestCase
{
    private PriceCast $priceCast;

    protected function setUp(): void
    {
        $this->priceCast = new PriceCast();
    }

    public function testGet(): void
    {
        $model = $this->createMock(Model::class);
        $attributes = ['price' => '100', 'currency' => 'USD'];

        $price = $this->priceCast->get($model, 'price', $attributes['price'], $attributes);

        $this->assertInstanceOf(Price::class, $price);
        $this->assertEquals('100.00', $price->getValue());
        $this->assertEquals('USD', $price->getCurrency());
    }

    public function testSet(): void
    {
        $model = $this->createMock(Model::class);
        $price = new Price('100', 'USD');

        $attributes = $this->priceCast->set($model, 'price', $price, []);

        $this->assertIsArray($attributes);
        $this->assertEquals('100', $attributes['price']);
        $this->assertEquals('USD', $attributes['currency']);
    }

    public function testGetWithNullValue(): void
    {
        $model = $this->createMock(Model::class);
        $attributes = ['price' => null, 'currency' => 'USD'];

        $price = $this->priceCast->get($model, 'price', $attributes['price'], $attributes);

        $this->assertNull($price);
    }

    public function testSetWithNullValue(): void
    {
        $model = $this->createMock(Model::class);
        $price = null;

        $attributes = $this->priceCast->set($model, 'price', $price, []);

        $this->assertIsArray($attributes);
        $this->assertNull($attributes['price']);
        $this->assertNull($attributes['currency']);
    }
}
