<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'collection_point_id',
        'waste_type_id',
        'title',
        'description',
        'quantity',
        'price',
        'type',
        'status',
        'images',
        'expired_at'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'price' => 'decimal:2',
        'images' => 'array',
        'expired_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wasteType(): BelongsTo
    {
        return $this->belongsTo(WasteType::class);
    }

    public function collectionPoint(): BelongsTo
    {
        return $this->belongsTo(CollectionPoint::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    // Tính điểm đánh giá trung bình
    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?: 0;
    }

    // Đếm số đánh giá
    public function getReviewsCountAttribute()
    {
        return $this->approvedReviews()->count();
    }

    // Lấy các bài đăng liên kết (cùng người bán, cùng vị trí, đang bán)
    public function getLinkedPostsAttribute()
    {
        return static::with(['user', 'wasteType'])
            ->where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->where('status', 'active')
            ->where('collection_point_id', $this->collection_point_id)
            ->latest()
            ->limit(6)
            ->get();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByWasteType($query, $wasteTypeId)
    {
        return $query->where('waste_type_id', $wasteTypeId);
    }

    public function scopeLinkedTo($query, Post $post)
    {
        return $query->where('user_id', $post->user_id)
            ->where('id', '!=', $post->id)
            ->where('status', 'active')
            ->where('collection_point_id', $post->collection_point_id);
    }

    // Accessor để lấy địa chỉ đầy đủ từ collection point
    public function getAddressAttribute()
    {
        return $this->collectionPoint ? $this->collectionPoint->full_address : 'Chưa có địa chỉ';
    }
}
