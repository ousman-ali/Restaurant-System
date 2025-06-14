<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebPageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'content',
        'section_id',
        'in_navbar',
        'is_active',
        'order'
    ];

    protected $casts = [
        'in_navbar' => 'boolean',
        'is_active' => 'boolean',
    ];
}
