<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Market extends Model
{
    
    protected $fillable = [
        'image', 'name', 'remark', 'status',
        'vat', 'discount', 'terms', 'slug'
    ];

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

}
