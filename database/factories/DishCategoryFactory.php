<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DishCategory>
 */
class DishCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Array of realistic food categories for a restaurant
        $categories = [
            'Appetizers',
            'Soups',
            'Salads',
            'Sandwiches',
            'Burgers',
            'Pasta',
            'Pizza',
            'Seafood',
            'Beef',
            'Chicken',
            'Vegetarian',
            'Vegan',
            'Rice Dishes',
            'Noodles',
            'Stir Fry',
            'Curry',
            'Grilled',
            'BBQ',
            'Breakfast',
            'Brunch',
            'Desserts',
            'Cakes',
            'Ice Cream',
            'Beverages',
            'Coffee',
            'Tea',
            'Smoothies',
            'Juices',
            'Alcoholic Drinks',
            'Wine',
            'Beer',
            'Cocktails',
            'Kids Menu',
            'Sides',
            'Finger Food',
            'Street Food',
            'Snacks',
            'Bread',
            'Sushi',
            'Dim Sum',
            'Tapas',
            'Mezze',
            'Specials',
            'Chef\'s Recommendations',
            'Seasonal',
            'Local Favorites',
            'Spicy',
            'Gluten-Free',
            'Low Carb',
            'Healthy Options',
            'Comfort Food'
        ];

        return [
            'name' => $this->faker->unique()->randomElement($categories),
            'status' => 1,
            'user_id' => function () {
                return User::query()->where('role',1)->inRandomOrder()->first()->id;
            },
        ];
    }
}
