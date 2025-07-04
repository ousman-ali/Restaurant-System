<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
        protected $fillable = [
        'order_id',
        'dish_id',
        'dish_type_id',
        'ready_dish_id',
        'quantity',
        'net_price',
        'gross_price',
        'inhouse_order_id',
        'supplier_order_id',
    ];

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

    public function supplierOrder()
{
    return $this->belongsTo(SupplierOrder::class);
}

public function inhouseOrder()
{
    return $this->belongsTo(InhouseOrder::class);
}

}
