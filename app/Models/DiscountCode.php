<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'description', 'type', 'value', 'min_purchase',
        'max_discount', 'usage_limit', 'usage_count', 'per_user_limit',
        'starts_at', 'expires_at', 'is_active', 'created_by'
    ];

    protected $casts = [
        'starts_at' => 'date',
        'expires_at' => 'date',
        'is_active' => 'boolean',
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
    ];

    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function usages() { return $this->hasMany(DiscountUsage::class); }

    public function isValid($userId = null, $cartTotal = 0): array
    {
        if (!$this->is_active) return ['valid' => false, 'reason' => 'Code is inactive'];
        if ($this->starts_at && $this->starts_at->isFuture()) return ['valid' => false, 'reason' => 'Code not yet active'];
        if ($this->expires_at && $this->expires_at->isPast()) return ['valid' => false, 'reason' => 'Code expired'];
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) return ['valid' => false, 'reason' => 'Usage limit reached'];
        if ($this->min_purchase && $cartTotal < $this->min_purchase) return ['valid' => false, 'reason' => "Minimum purchase of {$this->min_purchase} required"];
        
        if ($userId) {
            $userUsageCount = $this->usages()->where('user_id', $userId)->count();
            if ($userUsageCount >= $this->per_user_limit) return ['valid' => false, 'reason' => 'You have already used this code'];
        }

        return ['valid' => true];
    }

    public function calculateDiscount($cartTotal): float
    {
        $discount = match($this->type) {
            'percentage' => ($cartTotal * $this->value) / 100,
            'fixed' => $this->value,
            'free_shipping' => 500,
            default => 0,
        };

        if ($this->max_discount && $discount > $this->max_discount) {
            $discount = $this->max_discount;
        }

        return min($discount, $cartTotal);
    }

    public function use($userId, $orderId, $discountAmount)
    {
        $this->increment('usage_count');
        
        DiscountUsage::create([
            'discount_code_id' => $this->id,
            'user_id' => $userId,
            'order_id' => $orderId,
            'discount_amount' => $discountAmount,
        ]);
    }
}
