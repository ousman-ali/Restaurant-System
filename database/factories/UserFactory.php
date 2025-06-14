<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roles = [1, 2, 3, 4]; // 1 = admin, 2 = manager, 3 = waiter, 4 = kitchen

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail,
            'image' => '/dashboard/images/man-avatar.png',
            'active' => $this->faker->boolean(),
            'role' => $this->faker->randomElement($roles),
            'password' => Hash::make('password'),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            Employee::factory()->create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'thumbnail' => $user->image,
                'phone' => $this->faker->phoneNumber,
                'address' => $this->faker->address,
            ]);
        });
    }
}
