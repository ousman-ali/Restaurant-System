<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Table>
 */
class TableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Sequential table numbers from 1-100 for consistent table numbering
        static $tableNumber = 1;

        // Common restaurant table capacities (most tables are 2, 4, or 6 seats)
        $capacities = [2, 2, 2, 4, 4, 4, 4, 6, 6, 8, 10, 12];

        return [
            'table_no' => 'Table ' . $tableNumber++,
            'table_capacity' => $this->faker->randomElement($capacities),
            'status' => $this->faker->boolean(), // 80% chance of being available
            'user_id' => function () {
                return User::query()->inRandomOrder()->first()->id;
            },
        ];
    }
}
