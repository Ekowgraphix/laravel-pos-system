<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\LoyaltyPoint;
use App\Models\DiscountCode;

class LoyaltyService
{
    /**
     * Calculate points earned from an order
     */
    public function calculatePointsFromOrder(Order $order): int
    {
        $basePoints = floor($order->totalPrice / 100); // 1 point per 100 currency units
        
        $loyalty = LoyaltyPoint::firstOrCreate(
            ['user_id' => $order->user_id],
            ['points' => 0, 'lifetime_points' => 0, 'tier' => 'bronze']
        );
        
        $benefits = $loyalty->getTierBenefits();
        $multiplier = $benefits['points_multiplier'];
        
        return floor($basePoints * $multiplier);
    }

    /**
     * Award points for an order
     */
    public function awardPointsForOrder(Order $order)
    {
        $points = $this->calculatePointsFromOrder($order);
        
        $loyalty = LoyaltyPoint::firstOrCreate(
            ['user_id' => $order->user_id],
            ['points' => 0, 'lifetime_points' => 0, 'tier' => 'bronze']
        );
        
        $loyalty->addPoints(
            $points,
            "Order #{$order->order_code}",
            'order',
            $order->id
        );
        
        return $points;
    }

    /**
     * Redeem points for discount
     */
    public function redeemPointsForDiscount($userId, int $points): float
    {
        $loyalty = LoyaltyPoint::where('user_id', $userId)->first();
        
        if (!$loyalty || $loyalty->points < $points) {
            throw new \Exception('Insufficient points');
        }
        
        // 100 points = 1 currency unit discount
        $discountAmount = $points / 100;
        
        $loyalty->redeemPoints(
            $points,
            "Redeemed for discount",
            'discount',
            null
        );
        
        return $discountAmount;
    }

    /**
     * Get user's loyalty status
     */
    public function getUserLoyaltyStatus($userId): array
    {
        $loyalty = LoyaltyPoint::where('user_id', $userId)->first();
        
        if (!$loyalty) {
            return [
                'points' => 0,
                'lifetime_points' => 0,
                'tier' => 'bronze',
                'benefits' => ['discount' => 0, 'points_multiplier' => 1, 'free_shipping' => false],
                'next_tier' => 'silver',
                'points_to_next_tier' => 2000,
            ];
        }
        
        $nextTierInfo = $this->getNextTierInfo($loyalty);
        
        return [
            'points' => $loyalty->points,
            'lifetime_points' => $loyalty->lifetime_points,
            'tier' => $loyalty->tier,
            'benefits' => $loyalty->getTierBenefits(),
            'next_tier' => $nextTierInfo['tier'],
            'points_to_next_tier' => $nextTierInfo['points_needed'],
        ];
    }

    /**
     * Get next tier information
     */
    private function getNextTierInfo(LoyaltyPoint $loyalty): array
    {
        return match($loyalty->tier) {
            'platinum' => ['tier' => 'platinum', 'points_needed' => 0],
            'gold' => ['tier' => 'platinum', 'points_needed' => max(0, 10000 - $loyalty->lifetime_points)],
            'silver' => ['tier' => 'gold', 'points_needed' => max(0, 5000 - $loyalty->lifetime_points)],
            default => ['tier' => 'silver', 'points_needed' => max(0, 2000 - $loyalty->lifetime_points)],
        };
    }

    /**
     * Apply discount code to order
     */
    public function applyDiscountCode(string $code, $userId, float $cartTotal): array
    {
        $discountCode = DiscountCode::where('code', strtoupper($code))->first();
        
        if (!$discountCode) {
            return ['success' => false, 'message' => 'Invalid discount code'];
        }
        
        $validation = $discountCode->isValid($userId, $cartTotal);
        
        if (!$validation['valid']) {
            return ['success' => false, 'message' => $validation['reason']];
        }
        
        $discountAmount = $discountCode->calculateDiscount($cartTotal);
        
        return [
            'success' => true,
            'discount_amount' => $discountAmount,
            'discount_code_id' => $discountCode->id,
            'message' => "Discount of {$discountAmount} applied!"
        ];
    }

    /**
     * Expire old points
     */
    public function expireOldPoints()
    {
        $expiredTransactions = \App\Models\PointsTransaction::where('type', 'earned')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->get();
        
        foreach ($expiredTransactions as $transaction) {
            $loyalty = LoyaltyPoint::where('user_id', $transaction->user_id)->first();
            
            if ($loyalty && $loyalty->points > 0) {
                $pointsToExpire = min($transaction->points, $loyalty->points);
                $balanceBefore = $loyalty->points;
                
                $loyalty->decrement('points', $pointsToExpire);
                
                \App\Models\PointsTransaction::create([
                    'user_id' => $transaction->user_id,
                    'type' => 'expired',
                    'points' => -$pointsToExpire,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $loyalty->points,
                    'description' => 'Points expired',
                ]);
            }
        }
    }

    /**
     * Generate unique discount code
     */
    public function generateDiscountCode(string $prefix = 'DISC'): string
    {
        do {
            $code = strtoupper($prefix . '-' . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8));
        } while (DiscountCode::where('code', $code)->exists());
        
        return $code;
    }
}
