<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stockin extends Model
{
    protected $fillable = [
        'name', 'note', 'date', 'created_by', 'cost', 'qty'
        
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function inouts(): HasMany
    {
        return $this->hasMany(Inout::class, 'stockin_id', 'id');
    }

    public function getContainsAttribute()
    {
        $contains = $this->inouts()->pluck('brand_name')->unique();
        return $contains;
    }

    public function getAvgCostAttribute()
    {
        $amount = $this->inouts()->sum('amount');
        $qty = $this->inouts()->sum('qty_ctn');
        return $amount / $qty;
    }

}
