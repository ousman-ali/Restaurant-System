<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProducedReadyDish extends Model
{
    use HasFactory;
    protected $fillable = ['ready_dish_id', 'ready_quantity', 'pending_quantity', 'user_id'];

    public function readyDish()
    {
        return $this->belongsTo(ReadyDish::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
