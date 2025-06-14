<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    public function purses(): HasMany
    {
        return $this->hasMany(Purse::class);
    }

    public function payment(): HasMany
    {
        return $this->hasMany(PursesPayment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
