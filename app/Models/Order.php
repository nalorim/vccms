<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    
    protected $fillable = [
        'real_id', 'tax_id', 'order_id',
        'customer_id', 
        'phone', 'location', 'remark', 
        'salesperson_id', 'market_id', 
        'invoice_type', 'order_type',
        'order_date', 'due_date',
        'terms', 'discount', 'vat', 'rate',
        'amount', 
        'status', 'void_date', 'void_reason',
        'created_by'
    ];

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function salesperson(): BelongsTo
    {
        return $this->belongsTo(Salesperson::class);
    }

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function inouts(): HasMany
    {
        return $this->hasMany(Inout::class);
    }

    public function getContainAttribute()
    {
        $contains = $this->inouts()->pluck('brand_name')->unique();
        return $contains;
    }

    public function getQtyAttribute()
    {
        return $this->inouts()->whereNull('stockin_id')->sum('qty_ctn');
    }

    public function getAmountAttribute()
    {
        return $this->inouts()->whereNull('stockin_id')->sum('amount');
    }
    
}
