<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PursesPayment extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purses()
    {
        return $this->belongsTo(Purse::class,'purse_id');
    }
}
