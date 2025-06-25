<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    protected $appends = ['ready_dish_name'];

    public function getReadyDishNameAttribute()
    {
        return $this->readyDish ? $this->readyDish->name : null;
    }
    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }

    public function readyDish()
    {
        return $this->belongsTo(ReadyDish::class);
    }

    public function dishType()
    {
        return $this->belongsTo(DishPrice::class);
    }
}
