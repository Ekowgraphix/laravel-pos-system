<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointsTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'type', 'points', 'balance_before', 'balance_after',
        'reference_type', 'reference_id', 'description', 'expires_at'
    ];

    protected $casts = ['expires_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getTypeLabel(): string
    {
        return match($this->type) {
            'earned' => 'Earned',
            'redeemed' => 'Redeemed',
            'expired' => 'Expired',
            'adjusted' => 'Adjusted',
            default => 'Unknown',
        };
    }
}
