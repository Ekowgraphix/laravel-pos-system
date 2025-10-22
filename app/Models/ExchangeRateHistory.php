<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRateHistory extends Model
{
    use HasFactory;

    protected $fillable = ['currency_id', 'rate', 'effective_date'];
    protected $casts = ['rate' => 'decimal:6', 'effective_date' => 'date'];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
