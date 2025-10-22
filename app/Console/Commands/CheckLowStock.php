<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\InventoryService;
use App\Models\User;
use App\Models\StockAlert;
use App\Notifications\LowStockAlert;

class CheckLowStock extends Command
{
    protected $signature = 'inventory:check-low-stock';
    protected $description = 'Check for low stock items and send alerts';

    public function handle(InventoryService $inventoryService)
    {
        $this->info('Checking for low stock items...');
        
        // Check low stock
        $inventoryService->checkLowStockAlerts();
        
        // Check expiry
        $inventoryService->checkExpiryAlerts();
        
        // Send notifications to admins
        $pendingAlerts = StockAlert::where('status', 'pending')
            ->whereNull('sent_at')
            ->with('product')
            ->get();
        
        if ($pendingAlerts->isEmpty()) {
            $this->info('No new alerts to send.');
            return;
        }
        
        $admins = User::where('role', 'admin')->get();
        
        foreach ($pendingAlerts as $alert) {
            foreach ($admins as $admin) {
                $admin->notify(new LowStockAlert($alert->product, $alert->alert_type));
            }
            
            $alert->update(['sent_at' => now()]);
        }
        
        $this->info("Sent {$pendingAlerts->count()} alerts to {$admins->count()} admin(s).");
    }
}
