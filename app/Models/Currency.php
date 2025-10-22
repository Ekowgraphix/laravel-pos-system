<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'symbol', 'exchange_rate',
        'is_default', 'is_active', 'decimal_places'
    ];

    protected $casts = [
        'exchange_rate' => 'decimal:6',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get exchange rate history
     */
    public function rateHistory()
    {
        return $this->hasMany(ExchangeRateHistory::class);
    }

    /**
     * Get default currency
     */
    public static function getDefault()
    {
        return static::where('is_default', true)->first() 
            ?? static::where('code', 'MMK')->first()
            ?? static::first();
    }

    /**
     * Convert amount from this currency to another
     */
    public function convertTo($amount, $toCurrency)
    {
        if (is_string($toCurrency)) {
            $toCurrency = static::where('code', $toCurrency)->firstOrFail();
        }

        // Convert to base currency first, then to target currency
        $baseAmount = $amount / $this->exchange_rate;
        return $baseAmount * $toCurrency->exchange_rate;
    }

    /**
     * Format amount with currency symbol
     */
    public function format($amount)
    {
        $formatted = number_format($amount, $this->decimal_places);
        return $this->symbol . ' ' . $formatted;
    }

    /**
     * Update exchange rate and log history
     */
    public function updateRate($newRate)
    {
        $this->rateHistory()->create([
            'rate' => $this->exchange_rate,
            'effective_date' => now()->toDateString(),
        ]);

        $this->update(['exchange_rate' => $newRate]);
    }
}
