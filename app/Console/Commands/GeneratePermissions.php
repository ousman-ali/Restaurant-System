<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class GeneratePermissions extends Command
{
    protected $signature = 'permissions:generate';
    protected $description = 'Generate permissions from all named routes';

    public function handle()
    {
        $routes = collect(Route::getRoutes())
            ->pluck('action.as') // get route names
            ->filter()           // remove null
            ->unique();

        foreach ($routes as $name) {
            Permission::firstOrCreate(['name' => $name]);
            $this->info("Permission created: " . $name);
        }

        $this->info("Generated " . count($routes) . " permissions.");
    }
}
