<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'type', 'reference_id', 'requested_by', 'current_quantity', 'threshold',
        'requested_quantity', 'status', 'admin_note'
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'reference_id');
    }

    public function readyDish()
    {
        return $this->belongsTo(ReadyDish::class, 'reference_id');
    }

    // Optional accessor for reference object:
    public function getReferenceAttribute()
    {
        return $this->type === 'recipe_product'
            ? $this->product
            : $this->readyDish;
    }
}
