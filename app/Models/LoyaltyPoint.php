<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyPoint extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'points', 'lifetime_points', 'tier'];

    public function user() { return $this->belongsTo(User::class); }
    public function transactions() { return $this->hasMany(PointsTransaction::class, 'user_id', 'user_id'); }

    public function addPoints($points, $description, $referenceType = null, $referenceId = null)
    {
        $balanceBefore = $this->points;
        $this->increment('points', $points);
        $this->increment('lifetime_points', $points);
        $this->updateTier();
        
        PointsTransaction::create([
            'user_id' => $this->user_id,
            'type' => 'earned',
            'points' => $points,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->points,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'description' => $description,
            'expires_at' => now()->addYear(),
        ]);
    }

    public function redeemPoints($points, $description, $referenceType = null, $referenceId = null)
    {
        if ($points > $this->points) {
            throw new \Exception('Insufficient points balance');
        }

        $balanceBefore = $this->points;
        $this->decrement('points', $points);
        
        PointsTransaction::create([
            'user_id' => $this->user_id,
            'type' => 'redeemed',
            'points' => -$points,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->points,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'description' => $description,
        ]);
    }

    public function updateTier()
    {
        $tier = match(true) {
            $this->lifetime_points >= 10000 => 'platinum',
            $this->lifetime_points >= 5000 => 'gold',
            $this->lifetime_points >= 2000 => 'silver',
            default => 'bronze',
        };
        
        $this->update(['tier' => $tier]);
    }

    public function getTierBenefits(): array
    {
        return match($this->tier) {
            'platinum' => ['discount' => 15, 'points_multiplier' => 3, 'free_shipping' => true],
            'gold' => ['discount' => 10, 'points_multiplier' => 2, 'free_shipping' => true],
            'silver' => ['discount' => 5, 'points_multiplier' => 1.5, 'free_shipping' => false],
            default => ['discount' => 0, 'points_multiplier' => 1, 'free_shipping' => false],
        };
    }
}
