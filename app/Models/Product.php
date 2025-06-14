<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    use HasFactory;

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function purses()
    {
        return $this->hasMany(PursesProduct::class);
    }

    public function cookedProducts()
    {
        return $this->hasMany(CookedProduct::class);
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
