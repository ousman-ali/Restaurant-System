<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PursesProduct extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purse()
    {
        return $this->belongsTo(Purse::class);
    }

}
