<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadyDish extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'thumbnail', 'price', 'stock', 'source_type', 'supplier_id', 'status', 'user_id'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function producedBatches()
    {
        return $this->hasMany(ProducedReadyDish::class);
    }

    public function dishImages()
    {
        return $this->hasMany(DishInfo::class);
    }

    public function purchasedBatches()
    {
        return $this->hasMany(PursesReadyDish::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(DishCategory::class);
    }
    public function dishRecipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function orderDish()
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function getEmptyBatchCountAttribute()
    {
        if ($this->source_type === 'inhouse') {
            return $this->producedBatches()->where('ready_quantity', 0)->count();
        }

        if ($this->source_type === 'supplier') {
            return $this->purchasedBatches()->where('quantity', 0)->count();
        }

        return 0;
    }

    public function materialRequests()
        {
            return $this->hasMany(MaterialRequest::class, 'reference_id')->where('type', 'ready_dish');
        }


}
