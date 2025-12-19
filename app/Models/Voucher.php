<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_order_value',
        'max_discount',
        'usage_limit',
        'usage_count',
        'per_user_limit',
        'applies_to',
        'seller_id',
        'product_ids',
        'is_active',
        'starts_at',
        'expires_at',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'product_ids' => 'array',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the seller that owns the voucher
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the voucher usages
     */
    public function usages(): HasMany
    {
        return $this->hasMany(VoucherUsage::class);
    }

    /**
     * Check if voucher is valid
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        if ($this->expires_at && $now->gt($this->expires_at)) {
            return false;
        }

        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Check if user can use this voucher
     */
    public function canBeUsedBy($userId): bool
    {
        $userUsageCount = $this->usages()->where('user_id', $userId)->count();
        return $userUsageCount < $this->per_user_limit;
    }

    /**
     * Calculate discount amount
     * For freeship type, this returns the shipping fee amount that will be discounted
     */
    public function calculateDiscount($orderValue, $shippingFee = 0): float
    {
        if ($orderValue < $this->min_order_value) {
            return 0;
        }

        if ($this->type === 'percent') {
            $discount = $orderValue * ($this->value / 100);
            if ($this->max_discount) {
                $discount = min($discount, $this->max_discount);
            }
            return $discount;
        }

        if ($this->type === 'freeship') {
            // Return the shipping fee as discount amount
            return $shippingFee;
        }

        // Fixed discount
        return min($this->value, $orderValue);
    }
}
