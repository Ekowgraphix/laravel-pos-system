<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Store;
use App\Models\StockAlert;
use App\Models\StockMovement;
use App\Services\InventoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InventoryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $inventoryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->inventoryService = new InventoryService();
    }

    public function test_can_detect_low_stock_products()
    {
        // Create low stock product
        Product::factory()->create([
            'count' => 5,
            'low_stock_threshold' => 10,
            'alert_enabled' => true
        ]);

        // Create normal stock product
        Product::factory()->create([
            'count' => 50,
            'low_stock_threshold' => 10,
            'alert_enabled' => true
        ]);

        $this->inventoryService->checkLowStockAlerts();

        $this->assertDatabaseHas('stock_alerts', [
            'alert_type' => 'low_stock',
            'status' => 'pending'
        ]);

        $alerts = StockAlert::where('alert_type', 'low_stock')->count();
        $this->assertEquals(1, $alerts);
    }

    public function test_can_detect_expiring_products()
    {
        // Create expiring soon product
        Product::factory()->create([
            'expiry_date' => now()->addDays(15),
            'alert_enabled' => true
        ]);

        // Create not expiring product
        Product::factory()->create([
            'expiry_date' => now()->addDays(60),
            'alert_enabled' => true
        ]);

        $this->inventoryService->checkExpiryAlerts();

        $this->assertDatabaseHas('stock_alerts', [
            'alert_type' => 'expiring_soon',
            'status' => 'pending'
        ]);

        $alerts = StockAlert::where('alert_type', 'expiring_soon')->count();
        $this->assertEquals(1, $alerts);
    }

    public function test_can_detect_expired_products()
    {
        Product::factory()->create([
            'expiry_date' => now()->subDays(1),
            'alert_enabled' => true
        ]);

        $this->inventoryService->checkExpiryAlerts();

        $this->assertDatabaseHas('stock_alerts', [
            'alert_type' => 'expired',
            'status' => 'pending'
        ]);
    }

    public function test_can_record_stock_movement()
    {
        $product = Product::factory()->create(['count' => 100]);
        
        $this->inventoryService->recordStockMovement(
            $product->id,
            'adjustment',
            10,
            100,
            110,
            'Added stock',
            null,
            1
        );

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'type' => 'adjustment',
            'quantity' => 10
        ]);
    }

    public function test_get_low_stock_summary()
    {
        Product::factory()->create(['count' => 5, 'low_stock_threshold' => 10]);
        Product::factory()->create(['count' => 0, 'low_stock_threshold' => 10]);
        Product::factory()->create(['expiry_date' => now()->addDays(10)]);

        $summary = $this->inventoryService->getLowStockSummary();

        $this->assertArrayHasKey('low_stock', $summary);
        $this->assertArrayHasKey('out_of_stock', $summary);
        $this->assertArrayHasKey('expiring_soon', $summary);
    }

    public function test_calculate_reorder_quantity()
    {
        $product = Product::factory()->create([
            'count' => 5,
            'reorder_level' => 20,
            'reorder_quantity' => 50
        ]);

        $quantity = $this->inventoryService->calculateReorderQuantity($product->id);

        $this->assertEquals(50, $quantity);
    }

    public function test_get_inventory_valuation()
    {
        Product::factory()->create([
            'count' => 10,
            'purchase_price' => 100
        ]);
        
        Product::factory()->create([
            'count' => 5,
            'purchase_price' => 200
        ]);

        $valuation = $this->inventoryService->getInventoryValuation();

        $this->assertEquals(2000, $valuation['total_value']);
        $this->assertEquals(2, $valuation['total_products']);
    }

    public function test_store_inventory_summary()
    {
        $store = Store::factory()->create();
        
        Product::factory()->count(3)->create([
            'store_id' => $store->id,
            'count' => 50
        ]);
        
        Product::factory()->create([
            'store_id' => $store->id,
            'count' => 5,
            'low_stock_threshold' => 10
        ]);

        $summary = $this->inventoryService->getStoreInventorySummary($store->id);

        $this->assertEquals(4, $summary['total_products']);
        $this->assertEquals(1, $summary['low_stock_count']);
    }

    public function test_duplicate_alerts_not_created()
    {
        $product = Product::factory()->create([
            'count' => 5,
            'low_stock_threshold' => 10,
            'alert_enabled' => true
        ]);

        // Create alert first time
        $this->inventoryService->checkLowStockAlerts();
        $firstCount = StockAlert::count();

        // Run again - should not create duplicate
        $this->inventoryService->checkLowStockAlerts();
        $secondCount = StockAlert::count();

        $this->assertEquals($firstCount, $secondCount);
    }

    public function test_alert_disabled_products_are_ignored()
    {
        Product::factory()->create([
            'count' => 5,
            'low_stock_threshold' => 10,
            'alert_enabled' => false
        ]);

        $this->inventoryService->checkLowStockAlerts();

        $alerts = StockAlert::count();
        $this->assertEquals(0, $alerts);
    }
}
