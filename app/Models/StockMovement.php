<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'store_id',
        'type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'reference_number',
        'reference_type',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'quantity_before' => 'integer',
        'quantity_after' => 'integer',
    ];

    /**
     * Get the product for this movement
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the store for this movement
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the user who made this movement
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this is an inbound movement
     */
    public function isInbound(): bool
    {
        return in_array($this->type, ['purchase', 'return']);
    }

    /**
     * Check if this is an outbound movement
     */
    public function isOutbound(): bool
    {
        return in_array($this->type, ['sale', 'damage', 'expired']);
    }

    /**
     * Get movement type label
     */
    public function getTypeLabel(): string
    {
        return match($this->type) {
            'purchase' => 'Purchase',
            'sale' => 'Sale',
            'return' => 'Return',
            'adjustment' => 'Adjustment',
            'transfer' => 'Transfer',
            'damage' => 'Damage',
            'expired' => 'Expired',
            default => 'Unknown',
        };
    }

    /**
     * Get movement type color
     */
    public function getTypeColor(): string
    {
        return match($this->type) {
            'purchase', 'return' => 'success',
            'sale' => 'primary',
            'adjustment' => 'warning',
            'transfer' => 'info',
            'damage', 'expired' => 'danger',
            default => 'secondary',
        };
    }
}
