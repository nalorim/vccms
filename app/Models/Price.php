<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    
    protected $fillable = [
        'market_id', 'brand_id', 'item_id', 'ctn_price', 'base_price'
    ];

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
    
}
