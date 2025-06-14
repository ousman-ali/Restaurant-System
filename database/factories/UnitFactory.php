<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Unit>
 */
class UnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $restaurantUnitPairs = [
            // Weight units commonly used in restaurants
            ['kg', 'g', 1000],     // Kilogram to gram
            ['lb', 'oz', 16],      // Pound to ounce
            ['g', 'mg', 1000],     // Gram to milligram

            // Volume units for cooking and drinks
            ['L', 'mL', 1000],     // Liter to milliliter
            ['gal', 'qt', 4],      // Gallon to quart
            ['qt', 'cup', 4],      // Quart to cup
            ['cup', 'fl oz', 8],   // Cup to fluid ounce
            ['Tbsp', 'tsp', 3],    // Tablespoon to teaspoon

            // Count and portion units
            ['dozen', 'piece', 12],        // Dozen to single piece
            ['case', 'unit', 24],          // Case to unit (e.g., case of beer)
            ['portion', 'serving', 1],     // Portion to serving

            // Restaurant-specific measurements
            ['bottle', 'glass', 5],        // Bottle to glass (wine)
            ['scoop', 'Tbsp', 2],          // Ice cream scoop to tablespoons
            ['loaf', 'slice', 20],         // Bread loaf to slice
            ['block', 'slice', 10],        // Cheese block to slice

            // Measurement for ingredients
            ['bunch', 'sprig', 8],         // Bunch to sprig (herbs)
            ['head', 'clove', 10],         // Head to clove (garlic)
            ['stick', 'Tbsp', 8],          // Stick of butter to tablespoons
        ];

        $randomPair = $this->faker->randomElement($restaurantUnitPairs);

        return [
            'unit' => $randomPair[0],
            'child_unit' => $randomPair[1],
            'convert_rate' => $randomPair[2],
            'status' => $this->faker->boolean(),
            'user_id' => 1,
        ];
    }
}
