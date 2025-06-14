<?php

namespace App\Models;

use App\Models\OrderDetails;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Dish extends Model
{
    use HasFactory;

    public function dishPrices(): HasMany
    {
        return $this->hasMany(DishPrice::class);
    }

    public function dishImages(): HasMany
    {
        return $this->hasMany(DishInfo::class);
    }

    public function dishRecipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }

    public function orderDish(): HasMany
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(DishCategory::class);
    }

    public function todaysOrderDish(): HasMany
    {
        return $this->hasMany(OrderDetails::class)
            ->where('created_at', 'like',
                \Carbon\Carbon::today()->format('Y-m-d') . '%');
    }
}
