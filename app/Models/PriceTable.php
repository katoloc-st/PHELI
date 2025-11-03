<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceTable extends Model
{
    protected $table = 'price_table';

    protected $fillable = [
        'waste_type_id',
        'price',
        'effective_date',
        'expired_date',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'effective_date' => 'date',
        'expired_date' => 'date',
        'is_active' => 'boolean',
        'price' => 'decimal:2'
    ];

    public function wasteType(): BelongsTo
    {
        return $this->belongsTo(WasteType::class);
    }
}
