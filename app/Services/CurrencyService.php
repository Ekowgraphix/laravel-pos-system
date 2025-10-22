<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CurrencyService
{
    /**
     * Get all active currencies
     */
    public function getActiveCurrencies()
    {
        return Currency::where('is_active', true)->get();
    }

    /**
     * Get default currency
     */
    public function getDefaultCurrency()
    {
        return Currency::getDefault();
    }

    /**
     * Convert amount between currencies
     */
    public function convert($amount, $fromCurrency, $toCurrency)
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        $from = is_string($fromCurrency) 
            ? Currency::where('code', $fromCurrency)->first() 
            : $fromCurrency;

        $to = is_string($toCurrency) 
            ? Currency::where('code', $toCurrency)->first() 
            : $toCurrency;

        if (!$from || !$to) {
            throw new \Exception('Invalid currency');
        }

        return $from->convertTo($amount, $to);
    }

    /**
     * Fetch latest exchange rates from API
     */
    public function fetchLatestRates($baseCurrency = 'USD')
    {
        // Using ExchangeRate-API (free tier available)
        // Register at: https://www.exchangerate-api.com/
        $apiKey = env('EXCHANGE_RATE_API_KEY');
        
        if (!$apiKey) {
            return ['success' => false, 'message' => 'API key not configured'];
        }

        try {
            $response = Http::get("https://v6.exchangerate-api.com/v6/{$apiKey}/latest/{$baseCurrency}");
            
            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['result'] === 'success') {
                    $rates = $data['conversion_rates'];
                    $updated = 0;

                    foreach ($rates as $code => $rate) {
                        $currency = Currency::where('code', $code)->first();
                        
                        if ($currency) {
                            $currency->updateRate($rate);
                            $updated++;
                        }
                    }

                    return [
                        'success' => true,
                        'message' => "Updated {$updated} exchange rates",
                        'updated_at' => now(),
                    ];
                }
            }

            return ['success' => false, 'message' => 'Failed to fetch rates'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get cached exchange rates
     */
    public function getCachedRates()
    {
        return Cache::remember('exchange_rates', 3600, function () {
            return Currency::where('is_active', true)
                ->pluck('exchange_rate', 'code')
                ->toArray();
        });
    }

    /**
     * Format amount with currency
     */
    public function format($amount, $currencyCode)
    {
        $currency = Currency::where('code', $currencyCode)->first();
        
        if (!$currency) {
            return number_format($amount, 2);
        }

        return $currency->format($amount);
    }

    /**
     * Get user's preferred currency
     */
    public function getUserCurrency($user = null)
    {
        $user = $user ?? auth()->user();

        if ($user && isset($user->preferred_currency)) {
            return Currency::where('code', $user->preferred_currency)->first();
        }

        return $this->getDefaultCurrency();
    }

    /**
     * Initialize default currencies
     */
    public function initializeDefaultCurrencies()
    {
        $currencies = [
            ['code' => 'GHS', 'name' => 'Ghana Cedis', 'symbol' => '₵', 'exchange_rate' => 1.000000, 'is_default' => true],
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'exchange_rate' => 0.081],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'exchange_rate' => 0.075],
            ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£', 'exchange_rate' => 0.064],
            ['code' => 'JPY', 'name' => 'Japanese Yen', 'symbol' => '¥', 'exchange_rate' => 11.85],
            ['code' => 'CNY', 'name' => 'Chinese Yuan', 'symbol' => '¥', 'exchange_rate' => 0.585],
            ['code' => 'NGN', 'name' => 'Nigerian Naira', 'symbol' => '₦', 'exchange_rate' => 125.50],
            ['code' => 'ZAR', 'name' => 'South African Rand', 'symbol' => 'R', 'exchange_rate' => 1.45],
            ['code' => 'KES', 'name' => 'Kenyan Shilling', 'symbol' => 'KSh', 'exchange_rate' => 10.50],
            ['code' => 'XOF', 'name' => 'West African CFA', 'symbol' => 'CFA', 'exchange_rate' => 49.20],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(
                ['code' => $currency['code']],
                $currency
            );
        }

        return count($currencies);
    }
}
