<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
    
    protected $fillable = [
        'label', 'route', 'view', 'icon', 'param', 'parent_id', 'order'
    ];

    public function children(): HasMany
    {
        return $this->hasMany(Route::class, 'parent_id', 'id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Route::class, 'parent_id', 'id');
    }

}
