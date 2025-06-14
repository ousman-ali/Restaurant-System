<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductType>
 */
class ProductTypeFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $productTypes = $this->getProductTypes();

        $type = $this->faker->randomElement($productTypes);


        return [
            'product_type' => $type,
            'user_id' => 1,
            'status' => 1,
        ];
    }

    protected function getProductTypes(): array
    {
        return [
            'Meet',
            'Spices',
            'Oils',
            'Dairy',
            'Grains',
            'Fruits',
            'Vegetables',
            'Seafood',
            'Bakery',
            'Beverages'
        ];
    }
}
