<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    
    protected $fillable = [
        'image', 'name', 
        'contact', 'phone', 'email',
        'location', 'map',
        'salesperson_id', 'market_id',
        'vat', 'discount', 'terms',
        'remark', 'status', 'created_by'
    ];

    public function salesperson(): BelongsTo
    {
        return $this->belongsTo(User::class, 'salesperson_id', 'id');
    }

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

}
