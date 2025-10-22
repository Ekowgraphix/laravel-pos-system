<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'store_id',
        'alert_type',
        'current_quantity',
        'threshold',
        'expiry_date',
        'status',
        'sent_at',
        'acknowledged_at',
        'resolved_at',
        'acknowledged_by',
        'notes',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'sent_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the product for this alert
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the store for this alert
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the user who acknowledged this alert
     */
    public function acknowledgedBy()
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }

    /**
     * Acknowledge the alert
     */
    public function acknowledge($userId)
    {
        $this->update([
            'status' => 'acknowledged',
            'acknowledged_at' => now(),
            'acknowledged_by' => $userId,
        ]);
    }

    /**
     * Resolve the alert
     */
    public function resolve($notes = null)
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'notes' => $notes ?? $this->notes,
        ]);
    }

    /**
     * Check if alert is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if alert is acknowledged
     */
    public function isAcknowledged(): bool
    {
        return $this->status === 'acknowledged';
    }

    /**
     * Check if alert is resolved
     */
    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }

    /**
     * Get alert type label
     */
    public function getTypeLabel(): string
    {
        return match($this->alert_type) {
            'low_stock' => 'Low Stock',
            'out_of_stock' => 'Out of Stock',
            'expiring_soon' => 'Expiring Soon',
            'expired' => 'Expired',
            default => 'Unknown',
        };
    }

    /**
     * Get alert type color
     */
    public function getTypeColor(): string
    {
        return match($this->alert_type) {
            'low_stock' => 'warning',
            'out_of_stock' => 'danger',
            'expiring_soon' => 'info',
            'expired' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get alert priority
     */
    public function getPriority(): int
    {
        return match($this->alert_type) {
            'expired', 'out_of_stock' => 1, // Critical
            'expiring_soon' => 2, // High
            'low_stock' => 3, // Medium
            default => 4, // Low
        };
    }
}
