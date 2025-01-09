<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Brand extends Model
{
    
    protected $fillable = [
        'name', 'description', 'vendor_id',
        'image', 'remark', 'status', 'created_by',
        'factor', 'um', 'ctn_price', 'base_price'
    ];

    public function admin():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'brand_id', 'id');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function getHistoryAttribute()
    {
        return Inout::where('brand_name', $this->name)->count();
    }

    public function getStockBaseAttribute()
    {
        $ins = Inout::where('brand_name', $this->name)->where('type', 'in')->sum('qty_base');
        $outs = Inout::where('brand_name', $this->name)->where('type', 'out')->sum('qty_base');
        return $ins - $outs;
    }

    public function getAvgCostAttribute()
    {
        $total_amount_in = Inout::where('brand_name', $this->name)->whereNotNull('stockin_id')->sum('amount');
        $total_qty = Inout::where('brand_name', $this->name)->whereNotNull('stockin_id')->sum('qty_base');
        $cost = $total_amount_in / ($total_qty ? $total_qty : 1);
        
        return $cost;
    }

    public function show_ctn_price($market_id = null)
    {
        $default = $this->ctn_price;
        $ctn_price = $this->prices()->where('market_id', $market_id)->value('ctn_price');

        $price = $market_id ? 
                        ($ctn_price > 0 ? $ctn_price : $default)
                    :   $default;

        return $price;
    }

    public function show_base_price($market_id = null)
    {
        $default = $this->base_price;
        $base_price = $this->prices()->where('market_id', $market_id)->value('base_price');
        $price = $market_id ? 
                    ($base_price > 0 ? $base_price : $default)
                :   $this->base_price;

        return $price;
    }
    
}
