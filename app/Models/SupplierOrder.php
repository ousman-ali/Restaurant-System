<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_no',
        'order_by',
        'admin_id',
        'user_id',
        'status',
        'purchased_at',
        'accepted_at',
    ];

    protected $dates = [
        'purchased_at',
        'accepted_at',
    ];

    // Relationships
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
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
