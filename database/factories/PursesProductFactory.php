<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Purse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PursesProduct>
 */
class PursesProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'purse_id' => Purse::all()->random()->id,
            'product_id' => Product::all()->random()->id,
            'quantity' => $this->faker->numberBetween(1, 10),
            'unit_price' => $this->faker->randomFloat(2, 10),
            'child_unit_price' => $this->faker->randomFloat(2, 10),
            'gross_price' => $this->faker->randomFloat(2, 10),
        ];
    }
}
