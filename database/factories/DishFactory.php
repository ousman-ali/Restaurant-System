<?php

namespace Database\Factories;

use App\Models\Dish;
use App\Models\DishCategory;
use App\Models\User;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dish>
 */
class DishFactory extends Factory
{
    /**
     * Array of restaurant dishes
     *
     * @var array
     */
    protected $dishes = [
        // Appetizers
        'Garlic Bread', 'Mozzarella Sticks', 'Potato Skins', 'Buffalo Wings', 'Calamari',
        'Nachos', 'Spinach Artichoke Dip', 'Shrimp Cocktail', 'Bruschetta', 'Onion Rings',

        // Soups & Salads
        'Caesar Salad', 'Greek Salad', 'Cobb Salad', 'Minestrone Soup', 'French Onion Soup',
        'Tomato Bisque', 'Chicken Noodle Soup', 'Garden Salad', 'Waldorf Salad', 'Clam Chowder',

        // Main Courses - Pasta
        'Spaghetti Bolognese', 'Fettuccine Alfredo', 'Lasagna', 'Chicken Parmesan', 'Shrimp Scampi',
        'Penne Arrabbiata', 'Carbonara', 'Ravioli', 'Lobster Mac and Cheese', 'Mushroom Risotto',

        // Main Courses - Meat & Fish
        'Grilled Salmon', 'Filet Mignon', 'Ribeye Steak', 'Roast Chicken', 'Lamb Chops',
        'Pork Tenderloin', 'Fish and Chips', 'Beef Wellington', 'Surf and Turf', 'BBQ Ribs',

        // Burgers & Sandwiches
        'Classic Burger', 'Cheeseburger', 'Bacon Burger', 'Mushroom Swiss Burger', 'Club Sandwich',
        'BLT Sandwich', 'Grilled Chicken Sandwich', 'Philly Cheesesteak', 'Reuben Sandwich', 'French Dip',

        // Pizza
        'Margherita Pizza', 'Pepperoni Pizza', 'Supreme Pizza', 'Hawaiian Pizza', 'Meat Lovers Pizza',
        'Vegetarian Pizza', 'BBQ Chicken Pizza', 'Buffalo Chicken Pizza', 'Four Cheese Pizza', 'Mushroom Pizza',

        // Asian Inspired
        'Pad Thai', 'Kung Pao Chicken', 'Sushi Platter', 'Beef Teriyaki', 'General Tso Chicken',
        'Vegetable Stir Fry', 'Chicken Tikka Masala', 'Butter Chicken', 'Beef Pho', 'Bibimbap',

        // Mexican Inspired
        'Chicken Fajitas', 'Beef Tacos', 'Cheese Enchiladas', 'Beef Burrito', 'Quesadilla',
        'Chili Con Carne', 'Guacamole and Chips', 'Shrimp Tacos', 'Taco Salad', 'Chimichanga',

        // Desserts
        'Chocolate Cake', 'Cheesecake', 'Apple Pie', 'Tiramisu', 'Crème Brûlée',
        'Ice Cream Sundae', 'Chocolate Mousse', 'Key Lime Pie', 'Bread Pudding', 'Brownie Sundae'
    ];

    /**
     * Array of dish types (sizes, variations, etc.)
     *
     * @var array
     */
    protected $dishTypes = [
        'Small', 'Medium', 'Large', 'Regular', 'Family Size',
        'Kids Portion', 'Appetizer Portion', 'Entree Portion',
        'Spicy', 'Mild', 'Extra Spicy', 'Gluten Free',
        'Vegetarian Option', 'Vegan Option', 'Low Carb Option'
    ];

    /**
     * Array of dish info titles
     *
     * @var array
     */
    protected $dishInfoTitles = [
        'Ingredients', 'Nutritional Info', 'Allergens', 'Preparation Method',
        'Chef\'s Notes', 'Pairing Suggestions', 'Serving Suggestion', 'Origin',
        'Featured Item', 'Customer Favorite', 'Seasonal Special', 'New Item'
    ];

    /**
     * Array of local food image paths
     *
     * @var array
     */
    protected $foodImages = [
        'uploads/dish/thumbnail/food-1.jpg',
        'uploads/dish/thumbnail/food-2.jpg',
        'uploads/dish/thumbnail/food-3.jpg',
        'uploads/dish/thumbnail/food-4.jpg',
        'uploads/dish/thumbnail/food-5.jpg',
        'uploads/dish/thumbnail/food-6.jpg',
        'uploads/dish/thumbnail/food-7.jpg',
        'uploads/dish/thumbnail/food-8.jpg',
        'uploads/dish/thumbnail/food-9.jpg',
        'uploads/dish/thumbnail/food-10.jpg',
        'uploads/dish/thumbnail/food-11.jpg',
        'uploads/dish/thumbnail/food-12.jpg',
        'uploads/dish/thumbnail/food-13.jpg',
        'uploads/dish/thumbnail/food-14.jpg',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dishName = $this->faker->unique()->randomElement($this->dishes);

        // Get a random image from the local images array
        $imagePath = $this->faker->randomElement($this->foodImages);

        return [
            'dish' => $dishName,
            'thumbnail' => $imagePath,
            'available' => $this->faker->boolean(80), // 80% chance of being available
            'status' => $this->faker->boolean(90),    // 90% chance of being active
            'category_id' => function () {
                return DishCategory::inRandomOrder()->first()->id;
            },
            'user_id' => function () {
                return User::query()->inRandomOrder()->first()->id;
            },
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Dish $dish) {
            // Create 2-4 prices for each dish (different sizes/options)
            $priceCount = $this->faker->numberBetween(2, 4);
            for ($i = 0; $i < $priceCount; $i++) {
                $dishType = $this->faker->unique(true)->randomElement($this->dishTypes);

                $dishPrice = $dish->dishPrices()->create([
                    'dish_type' => $dishType,
                    'price' => $this->faker->randomFloat(2, 5, 50),
                    'user_id' => $dish->user_id,
                ]);

                // Create recipes for each dish type (3-8 ingredients per recipe)
                $ingredientCount = $this->faker->numberBetween(3, 8);

                // Get the dish type ID from the created price
                $dishTypeId = $dishPrice->id;

                for ($j = 0; $j < $ingredientCount; $j++) {
                    // Get a random product and unit
                    $product = Product::query()->inRandomOrder()->first();
                    $unit = Unit::query()->inRandomOrder()->first();

                    // Create recipe entry
                    $dish->dishRecipes()->create([
                        'dish_type_id' => $dishTypeId,
                        'product_id' => $product->id,
                        'unit_needed' => $this->faker->randomFloat(2, 0.5, 10),
                        'child_unit_needed' => $this->faker->randomFloat(2, 1, 500),
                        'user_id' => $dish->user_id,
                    ]);
                }
            }

            // Reset unique tracker for the next dish
            $this->faker->unique(false);

            // Create 1-3 info entries for each dish
            $infoCount = $this->faker->numberBetween(1, 3);
            for ($i = 0; $i < $infoCount; $i++) {
                // Also use local images for dish info images
                $localImage = $this->faker->randomElement($this->foodImages);

                $dish->dishImages()->create([
                    'title' => $this->faker->randomElement($this->dishInfoTitles),
                    'image' => $localImage,
                    'user_id' => $dish->user_id,
                ]);
            }
        });
    }
}
