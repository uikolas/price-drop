<?php

namespace Database\Factories;

use App\Models\Product;
use App\RetailerType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductRetailer>
 */
class ProductRetailerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'url' => $this->faker->url,
            'type' => 'type',
        ];
    }

    public function type(RetailerType $type): static
    {
        return $this->state(function (array $attributes) use ($type) {
            return [
                'type' => $type,
            ];
        });
    }

    public function price(string $price): static
    {
        return $this->state(function (array $attributes) use ($price) {
            return [
                'price' => $price,
            ];
        });
    }

    public function priceUpdatedAt(Carbon $date): static
    {
        return $this->state(function (array $attributes) use ($date) {
            return [
                'price_updated_at' => $date,
            ];
        });
    }

}
