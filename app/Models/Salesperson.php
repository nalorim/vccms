<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Salesperson extends Model
{
    
    protected $fillable = [
        'user_id', 'market_id', 'status'
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function orders()
    {
        return 0;
    }

    public function solds()
    {
        return 0;
    }

    public function getNameAttribute()
    {
        return $this->profile->name;
    }


}
