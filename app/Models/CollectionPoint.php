<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectionPoint extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'detailed_address',
        'province',
        'district',
        'ward',
        'postal_code',
        'address_note',
        'contact_person',
        'contact_phone',
        'status',
        'latitude',
        'longitude'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function activePosts(): HasMany
    {
        return $this->hasMany(Post::class)->where('status', 'active');
    }

    // Lấy địa chỉ đầy đủ
    public function getFullAddressAttribute()
    {
        $address = $this->detailed_address;
        if ($this->ward) $address .= ', ' . $this->ward;
        if ($this->district) $address .= ', ' . $this->district;
        if ($this->province) $address .= ', ' . $this->province;
        return $address;
    }

    // Đếm số bài đăng đang hoạt động
    public function getActivePostsCountAttribute()
    {
        return $this->activePosts()->count();
    }
}
