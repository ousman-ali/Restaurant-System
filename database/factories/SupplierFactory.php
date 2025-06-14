<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'status' => $this->faker->boolean(),
            'user_id' => 1,
            'address' => $this->faker->address,
        ];
    }
}
