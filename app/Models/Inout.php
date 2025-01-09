<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inout extends Model
{
    
    protected $fillable = [
        'order_id', 'stockin_id', 'item_id',
        'type', 'price', 'um', 'qty', 'qty_base', 'qty_ctn', 'discount',
        'vat', 'amount', 'market', 'cost', 'brand_name'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function stockin(): BelongsTo
    {
        return $this->belongsTo(Stockin::class, 'stockin_id', 'id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }


}
