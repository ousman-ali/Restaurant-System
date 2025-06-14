<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\DishCategory;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Supplier;
use App\Models\Table;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductionDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'email' => 'admin@restulator.com',
            'password' => Hash::make('12345678'),
            'role' => 1,
            'active' => true,
        ]);

        Supplier::factory()->count(1)->create();
        Table::factory()->count(5)->create();
        Unit::factory()->count(3)->create();
        ProductType::factory()->count(3)->create();
        Product::factory()->count(7)->create();
        DishCategory::factory()->count(3)->create();
        Dish::factory()->count(7)->create();

        $this->call(WebsiteSeeder::class);
    }
}
