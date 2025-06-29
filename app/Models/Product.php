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

    public function materialRequests()
    {
        return $this->hasMany(MaterialRequest::class, 'reference_id')->where('type', 'recipe_product');
    }

    public function latestMaterialRequest()
    {
        return $this->hasOne(MaterialRequest::class, 'reference_id')
                    ->latestOfMany()
                    ->where('type', 'recipe_product');
    }




}
