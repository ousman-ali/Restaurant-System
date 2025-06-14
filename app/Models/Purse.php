<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purse extends Model
{
    use HasFactory;

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function pursesProducts()
    {
        return $this->hasMany(PursesProduct::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pursesPayments()
    {
        return $this->hasMany(PursesPayment::class);
    }
}
