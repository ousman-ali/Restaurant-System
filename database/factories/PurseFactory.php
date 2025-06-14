<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Purse;
use App\Models\PursesProduct;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Purse>
 */
class PurseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'purses_id' => $this->faker->unique()->randomNumber(),
            'supplier_id' => function () {
                return Supplier::query()->inRandomOrder()->first()->id;
            },
            'purses_value' => $this->faker->randomNumber(),
            'is_payed' => $this->faker->boolean(),
            'user_id' => function () {
                return User::query()->inRandomOrder()->first()->id;
            }
        ];
    }

    public function configure(): self
    {
        return $this->afterCreating(function (Purse $purse) {
            $numProducts = $this->faker->numberBetween(1, 5);

            // Get random products
            $products = Product::query()->inRandomOrder()->limit($numProducts)->get();

            // If not enough products exist, create some
            if ($products->count() < $numProducts) {
                $additionalProducts = Product::factory()->count($numProducts - $products->count())->create();
                $products = $products->merge($additionalProducts);
            }

            foreach ($products as $product) {
                // Assuming you have a many-to-many relationship with a pivot table
                // Method 1: If using a standard Laravel pivot


                // Method 2: If using a custom pivot model
                PursesProduct::factory()->create([
                    'purse_id' => $purse->id,
                    'product_id' => $product->id,
                ]);
            }

        });
    }


}
