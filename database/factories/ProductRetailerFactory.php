<?php

namespace Database\Factories;

use App\Models\Product;
use App\RetailerType;
use Illuminate\Database\Eloquent\Factories\Factory;

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
    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'url' => $this->faker->url,
            'type' => 'type',
        ];
    }

    public function type(RetailerType $type)
    {
        return $this->state(function (array $attributes) use ($type) {
            return [
                'type' => $type,
            ];
        });
    }
}
