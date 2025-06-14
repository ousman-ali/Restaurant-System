<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\DishCategory;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Purse;
use App\Models\Supplier;
use App\Models\Table;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StagingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 1,
            'active' => true,
        ]);
        User::factory()->create([
            'email' => 'manager@gmail.com',
            'password' => Hash::make('password'),
            'role' => 2,
            'active' => true,
        ]);
        User::factory()->create([
            'email' => 'waiter@gmail.com',
            'password' => Hash::make('password'),
            'role' => 3,
            'active' => true,
        ]);
        User::factory()->create([
            'email' => 'kitchen@gmail.com',
            'password' => Hash::make('password'),
            'role' => 4,
            'active' => true,
        ]);

        User::factory()->count(10)->create();
        Supplier::factory()->count(10)->create();
        Table::factory()->count(10)->create();
        Unit::factory()->count(3)->create();
        ProductType::factory()->count(5)->create();
        Product::factory()->count(40)->create();
        Purse::factory()->count(40)->create();
        DishCategory::factory()->count(5)->create();
        Dish::factory()->count(25)->create();

        $this->call(WebsiteSeeder::class);
    }
}
