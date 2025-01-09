<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    
    protected $fillable = [
        'name', 'brand_id', 'factor', 'description', 'color',
        'remark', 'created_by', 'status', 'um',
        'sku', 'barcode', 'price'
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function inouts(): HasMany
    {
        return $this->hasMany(Inout::class, 'item_id', 'id');
    }

    public function getInStockAttribute()
    {
        $ins = $this->inouts()->where('type', 'in')->sum('qty_base');
        $outs = $this->inouts()->where('type', 'out')->sum('qty_base');

        // Return In-stock QTY
        return ($ins - $outs) / $this->factor;
    }

    public function refreshInoutQTY($factor)
    {
        foreach($this->inouts as $inout)
        {
            $qty_base = $inout->um == "ctn" ? ($inout->qty * $factor) : $inout->qty;
            $qty_ctn = $inout->um == "ctn" ? $inout->qty : ($inout->qty / $factor);
            
            $inout->update([
                'qty_base' => $qty_base,
                'qty_ctn' => $qty_ctn
            ]);
        }
        
    }

    public function getTotalBaseAttribute()
    {
        $ins = $this->inouts()->where('type', 'in')->sum('qty_base');
        $outs = $this->inouts()->where('type', 'out')->sum('qty_base');

        return $ins - $outs;
    }

    public function getInsAttribute()
    {
        return $this->inouts()->where('type', 'in')->sum('qty_base') / $this->factor;
    }

    public function getOutsAttribute()
    {
        return $this->inouts()->where('type', 'out')->sum('qty_base') / $this->factor;
    }

    public function getCostAttribute()
    {
        $total_amount_in = $this->inouts()->whereNotNull('stockin_id')->sum('amount');
        $total_qty = $this->inouts()->whereNotNull('stockin_id')->sum('qty');
        $cost = $total_amount_in / ($total_qty ? $total_qty : 1);
        return $cost;
    }
    
}
