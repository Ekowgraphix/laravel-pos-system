<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name','price','category_id','description','count','image','purchase_price',
        'low_stock_threshold','batch_number','expiry_date','reorder_level','reorder_quantity',
        'store_id','barcode','sku','supplier_name','supplier_contact',
        'last_restocked_at','total_sold','alert_enabled','last_alert_sent_at'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'last_restocked_at' => 'datetime',
        'last_alert_sent_at' => 'datetime',
        'alert_enabled' => 'boolean',
    ];

    public function category() { return $this->belongsTo(Category::class); }
    public function store() { return $this->belongsTo(Store::class); }
    public function orders() { return $this->hasMany(Order::class); }
    public function purchaseOrders() { return $this->hasMany(PurchaseOrder::class); }
    public function stockMovements() { return $this->hasMany(StockMovement::class); }
    public function stockAlerts() { return $this->hasMany(StockAlert::class); }
    public function comments() { return $this->hasMany(Comment::class); }
    public function ratings() { return $this->hasMany(Rating::class); }

    public function isLowStock(): bool { return $this->count <= $this->low_stock_threshold; }
    public function isOutOfStock(): bool { return $this->count <= 0; }
    public function isExpiringSoon($days = 30): bool {
        return $this->expiry_date && $this->expiry_date->lte(now()->addDays($days)) && $this->expiry_date->gte(now());
    }
    public function isExpired(): bool { return $this->expiry_date && $this->expiry_date->lt(now()); }
    public function needsReorder(): bool { return $this->count <= $this->reorder_level; }
    public function getRemainingStock(): int { return max(0, $this->count); }
    public function getStockPercentage(): float {
        if ($this->reorder_level <= 0) return 100;
        return ($this->count / $this->reorder_level) * 100;
    }
}
