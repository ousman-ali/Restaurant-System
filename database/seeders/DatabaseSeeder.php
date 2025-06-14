<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        if (App::isLocal()) {
            $this->call(StagingDataSeeder::class);
        } else {
            $this->call(ProductionDataSeeder::class);
        }
    }
}
