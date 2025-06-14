<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }

    public function dishType()
    {
        return $this->belongsTo(DishPrice::class);
    }
}
