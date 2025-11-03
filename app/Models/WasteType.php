<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WasteType extends Model
{
    protected $fillable = [
        'name',
        'unit',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function priceTables(): HasMany
    {
        return $this->hasMany(PriceTable::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function getCurrentPrice()
    {
        return $this->priceTables()
            ->where('is_active', true)
            ->where('effective_date', '<=', now())
            ->where(function($query) {
                $query->whereNull('expired_date')
                      ->orWhere('expired_date', '>=', now());
            })
            ->orderBy('effective_date', 'desc')
            ->first();
    }
}
