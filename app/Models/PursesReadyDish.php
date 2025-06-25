<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PursesReadyDish extends Model
{
    use HasFactory;
    protected $fillable = ['purse_id', 'ready_dish_id', 'quantity', 'unit_price', 'total_price'];

    public function readyDish()
    {
        return $this->belongsTo(ReadyDish::class);
    }

    public function purse()
    {
        return $this->belongsTo(Purse::class);
    }
}
