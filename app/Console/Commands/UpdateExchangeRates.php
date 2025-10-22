<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CurrencyService;

class UpdateExchangeRates extends Command
{
    protected $signature = 'currency:update-rates';
    protected $description = 'Update exchange rates from API';

    public function handle(CurrencyService $currencyService)
    {
        $this->info('Fetching latest exchange rates...');
        
        $result = $currencyService->fetchLatestRates();
        
        if ($result['success']) {
            $this->info($result['message']);
        } else {
            $this->error('Failed: ' . $result['message']);
        }
    }
}
