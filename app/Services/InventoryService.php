<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockAlert;
use App\Models\StockMovement;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class InventoryService
{
    /**
     * Check and create stock alerts for low stock products
     */
    public function checkLowStockAlerts()
    {
        $lowStockProducts = Product::whereRaw('count <= low_stock_threshold')
            ->where('alert_enabled', true)
            ->get();
        
        foreach ($lowStockProducts as $product) {
            $existingAlert = StockAlert::where('product_id', $product->id)
                ->where('alert_type', $product->isOutOfStock() ? 'out_of_stock' : 'low_stock')
                ->where('status', 'pending')
                ->first();
            
            if (!$existingAlert) {
                StockAlert::create([
                    'product_id' => $product->id,
                    'store_id' => $product->store_id,
                    'alert_type' => $product->isOutOfStock() ? 'out_of_stock' : 'low_stock',
                    'current_quantity' => $product->count,
                    'threshold' => $product->low_stock_threshold,
                    'status' => 'pending',
                ]);
                
                $product->update(['last_alert_sent_at' => now()]);
            }
        }
    }

    /**
     * Check and create expiry alerts
     */
    public function checkExpiryAlerts()
    {
        $expiringProducts = Product::whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->where('expiry_date', '>=', now())
            ->get();
        
        foreach ($expiringProducts as $product) {
            $existingAlert = StockAlert::where('product_id', $product->id)
                ->where('alert_type', 'expiring_soon')
                ->where('status', 'pending')
                ->first();
            
            if (!$existingAlert) {
                StockAlert::create([
                    'product_id' => $product->id,
                    'store_id' => $product->store_id,
                    'alert_type' => 'expiring_soon',
                    'current_quantity' => $product->count,
                    'expiry_date' => $product->expiry_date,
                    'status' => 'pending',
                ]);
            }
        }
        
        // Check expired products
        $expiredProducts = Product::whereNotNull('expiry_date')
            ->where('expiry_date', '<', now())
            ->get();
        
        foreach ($expiredProducts as $product) {
            $existingAlert = StockAlert::where('product_id', $product->id)
                ->where('alert_type', 'expired')
                ->where('status', 'pending')
                ->first();
            
            if (!$existingAlert) {
                StockAlert::create([
                    'product_id' => $product->id,
                    'store_id' => $product->store_id,
                    'alert_type' => 'expired',
                    'current_quantity' => $product->count,
                    'expiry_date' => $product->expiry_date,
                    'status' => 'pending',
                ]);
            }
        }
    }

    /**
     * Get reorder suggestions
     */
    public function getReorderSuggestions()
    {
        return Product::whereRaw('count <= reorder_level')
            ->where('count', '>', 0)
            ->get()
            ->map(function ($product) {
                return [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'current_stock' => $product->count,
                    'reorder_level' => $product->reorder_level,
                    'suggested_quantity' => $product->reorder_quantity,
                    'supplier' => $product->supplier_name,
                    'estimated_cost' => $product->purchase_price * $product->reorder_quantity,
                ];
            });
    }

    /**
     * Create automatic purchase orders for low stock items
     */
    public function createAutomaticPurchaseOrders($userId)
    {
        $suggestions = $this->getReorderSuggestions();
        $ordersCreated = 0;
        
        foreach ($suggestions as $suggestion) {
            $product = Product::find($suggestion['product_id']);
            
            if ($product->supplier_name) {
                PurchaseOrder::create([
                    'store_id' => $product->store_id,
                    'product_id' => $product->id,
                    'supplier_name' => $product->supplier_name,
                    'supplier_contact' => $product->supplier_contact,
                    'quantity_ordered' => $suggestion['suggested_quantity'],
                    'unit_cost' => $product->purchase_price,
                    'total_cost' => $suggestion['estimated_cost'],
                    'status' => 'pending',
                    'order_date' => now(),
                    'expected_delivery_date' => now()->addDays(7),
                    'created_by' => $userId,
                ]);
                
                $ordersCreated++;
            }
        }
        
        return $ordersCreated;
    }

    /**
     * Record stock movement
     */
    public function recordStockMovement($productId, $type, $quantity, $userId, $notes = null, $referenceNumber = null, $referenceType = null)
    {
        $product = Product::findOrFail($productId);
        $quantityBefore = $product->count;
        
        // Update product stock
        if (in_array($type, ['purchase', 'return'])) {
            $product->increment('count', $quantity);
        } else {
            $product->decrement('count', abs($quantity));
            $quantity = -abs($quantity);
        }
        
        $product->refresh();
        
        // Create movement record
        return StockMovement::create([
            'product_id' => $productId,
            'store_id' => $product->store_id,
            'type' => $type,
            'quantity' => $quantity,
            'quantity_before' => $quantityBefore,
            'quantity_after' => $product->count,
            'reference_number' => $referenceNumber,
            'reference_type' => $referenceType,
            'notes' => $notes,
            'user_id' => $userId,
        ]);
    }

    /**
     * Transfer stock between stores
     */
    public function transferStock($productId, $fromStoreId, $toStoreId, $quantity, $userId)
    {
        DB::transaction(function () use ($productId, $fromStoreId, $toStoreId, $quantity, $userId) {
            // Deduct from source store
            $this->recordStockMovement(
                $productId,
                'transfer',
                $quantity,
                $userId,
                "Transfer to store {$toStoreId}",
                "TRF-" . time(),
                'transfer'
            );
            
            // Add to destination store
            // Note: This assumes separate product records per store
            // You may need to adjust based on your actual implementation
        });
    }

    /**
     * Get stock movement history
     */
    public function getStockHistory($productId, $days = 30)
    {
        return StockMovement::where('product_id', $productId)
            ->where('created_at', '>=', now()->subDays($days))
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get inventory valuation
     */
    public function getInventoryValuation($storeId = null)
    {
        $query = Product::query();
        
        if ($storeId) {
            $query->where('store_id', $storeId);
        }
        
        return $query->get()->map(function ($product) {
            return [
                'product' => $product->name,
                'quantity' => $product->count,
                'unit_cost' => $product->purchase_price,
                'unit_price' => $product->price,
                'cost_value' => $product->count * $product->purchase_price,
                'retail_value' => $product->count * $product->price,
                'potential_profit' => $product->count * ($product->price - $product->purchase_price),
            ];
        });
    }

    /**
     * Get low stock summary
     */
    public function getLowStockSummary()
    {
        return [
            'critical' => Product::where('count', 0)->count(),
            'low' => Product::whereRaw('count > 0 AND count <= low_stock_threshold')->count(),
            'expiring_soon' => Product::whereNotNull('expiry_date')
                ->whereBetween('expiry_date', [now(), now()->addDays(30)])
                ->count(),
            'expired' => Product::whereNotNull('expiry_date')
                ->where('expiry_date', '<', now())
                ->count(),
        ];
    }
}
