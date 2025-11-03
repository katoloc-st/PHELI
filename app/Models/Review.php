<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'post_id',
        'user_id', 
        'rating',
        'content',
        'status'
    ];

    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scope cho các đánh giá đã được duyệt
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Scope sắp xếp theo mới nhất
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
