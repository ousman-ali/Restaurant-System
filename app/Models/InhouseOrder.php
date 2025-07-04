<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InhouseOrder extends Model
{
    use HasFactory;

      protected $fillable = [
        'order_no',
        'order_by',
        'baker_id',
        'user_id',
        'status',
        'cook_start_at',
        'cook_complete_at',
        'accepted_at',
    ];

    protected $dates = [
        'cook_start_at',
        'cook_complete_at',
        'accepted_at',
    ];

    // Relationships
    public function baker()
    {
        return $this->belongsTo(User::class, 'baker_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orderBy()
    {
        return $this->belongsTo(User::class, 'order_by');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }
}
