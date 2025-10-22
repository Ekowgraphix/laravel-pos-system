<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'email',
        'manager_id',
        'status',
        'timezone',
        'currency',
        'description',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the manager of the store
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get all products in this store
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get all orders from this store
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all purchase orders for this store
     */
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * Get all stock movements for this store
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Get all stock alerts for this store
     */
    public function stockAlerts()
    {
        return $this->hasMany(StockAlert::class);
    }

    /**
     * Get all users assigned to this store
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'store_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Check if store is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get low stock products for this store
     */
    public function getLowStockProducts()
    {
        return $this->products()
                    ->whereRaw('count <= low_stock_threshold')
                    ->where('alert_enabled', true)
                    ->get();
    }

    /**
     * Get expiring products for this store
     */
    public function getExpiringProducts($days = 30)
    {
        return $this->products()
                    ->whereNotNull('expiry_date')
                    ->whereDate('expiry_date', '<=', now()->addDays($days))
                    ->whereDate('expiry_date', '>=', now())
                    ->get();
    }
}
