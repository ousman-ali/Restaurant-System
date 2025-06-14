<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DishPrice extends Model
{
    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class,'dish_type_id');
    }

}
