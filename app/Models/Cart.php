<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'quantity',
        'is_fixed_quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'is_fixed_quantity' => 'boolean',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    // Helper methods
    public function getSubtotalAttribute()
    {
        return $this->post->price * $this->quantity;
    }
}
