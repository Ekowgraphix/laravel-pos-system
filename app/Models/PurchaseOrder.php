<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'store_id',
        'product_id',
        'supplier_name',
        'supplier_contact',
        'quantity_ordered',
        'quantity_received',
        'unit_cost',
        'total_cost',
        'status',
        'order_date',
        'expected_delivery_date',
        'actual_delivery_date',
        'notes',
        'created_by',
        'received_by',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    /**
     * Boot method to generate PO number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($purchaseOrder) {
            if (!$purchaseOrder->po_number) {
                $purchaseOrder->po_number = self::generatePONumber();
            }
        });
    }

    /**
     * Generate unique PO number
     */
    public static function generatePONumber(): string
    {
        $year = date('Y');
        $month = date('m');
        $lastPO = self::whereYear('created_at', $year)
                      ->whereMonth('created_at', $month)
                      ->latest()
                      ->first();

        $number = $lastPO ? intval(substr($lastPO->po_number, -4)) + 1 : 1;

        return 'PO-' . $year . $month . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the store for this PO
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the product for this PO
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who created this PO
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who received this PO
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Mark PO as completed
     */
    public function markAsCompleted($receivedBy)
    {
        $this->update([
            'status' => 'completed',
            'quantity_received' => $this->quantity_ordered,
            'actual_delivery_date' => now(),
            'received_by' => $receivedBy,
        ]);

        // Update product stock
        $this->product->increment('count', $this->quantity_ordered);
        $this->product->update(['last_restocked_at' => now()]);

        // Create stock movement record
        StockMovement::create([
            'product_id' => $this->product_id,
            'store_id' => $this->store_id,
            'type' => 'purchase',
            'quantity' => $this->quantity_ordered,
            'quantity_before' => $this->product->count - $this->quantity_ordered,
            'quantity_after' => $this->product->count,
            'reference_number' => $this->po_number,
            'reference_type' => 'purchase_order',
            'notes' => "PO received: {$this->po_number}",
            'user_id' => $receivedBy,
        ]);
    }

    /**
     * Mark PO as partial
     */
    public function receivePartial($quantity, $receivedBy)
    {
        $this->increment('quantity_received', $quantity);
        
        $status = $this->quantity_received >= $this->quantity_ordered ? 'completed' : 'partial';
        
        $this->update([
            'status' => $status,
            'actual_delivery_date' => $status === 'completed' ? now() : $this->actual_delivery_date,
            'received_by' => $receivedBy,
        ]);

        // Update product stock
        $this->product->increment('count', $quantity);

        // Create stock movement record
        StockMovement::create([
            'product_id' => $this->product_id,
            'store_id' => $this->store_id,
            'type' => 'purchase',
            'quantity' => $quantity,
            'quantity_before' => $this->product->count - $quantity,
            'quantity_after' => $this->product->count,
            'reference_number' => $this->po_number,
            'reference_type' => 'purchase_order',
            'notes' => "Partial PO received: {$quantity} units",
            'user_id' => $receivedBy,
        ]);
    }

    /**
     * Get remaining quantity to receive
     */
    public function getRemainingQuantity(): int
    {
        return $this->quantity_ordered - $this->quantity_received;
    }

    /**
     * Check if PO is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status !== 'completed' 
               && $this->expected_delivery_date 
               && $this->expected_delivery_date->isPast();
    }
}
