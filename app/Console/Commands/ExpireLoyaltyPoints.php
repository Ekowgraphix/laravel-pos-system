<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LoyaltyService;

class ExpireLoyaltyPoints extends Command
{
    protected $signature = 'loyalty:expire-points';
    protected $description = 'Expire old loyalty points';

    public function handle(LoyaltyService $loyaltyService)
    {
        $this->info('Expiring old loyalty points...');
        
        $loyaltyService->expireOldPoints();
        
        $this->info('Loyalty points expiration completed.');
    }
}
