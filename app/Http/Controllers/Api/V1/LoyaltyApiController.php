<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\LoyaltyService;
use App\Models\LoyaltyPoint;
use App\Models\DiscountCode;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LoyaltyApiController extends Controller
{
    protected $loyaltyService;

    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Get user loyalty status
     */
    public function status($userId): JsonResponse
    {
        $status = $this->loyaltyService->getUserLoyaltyStatus($userId);
        
        return response()->json([
            'success' => true,
            'data' => $status,
        ]);
    }

    /**
     * Get points transactions
     */
    public function transactions($userId): JsonResponse
    {
        $loyalty = LoyaltyPoint::where('user_id', $userId)->first();
        
        if (!$loyalty) {
            return response()->json([
                'success' => true,
                'data' => [],
            ]);
        }
        
        $transactions = $loyalty->transactions()->latest()->paginate(20);
        
        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }

    /**
     * Redeem points
     */
    public function redeem(Request $request, $userId): JsonResponse
    {
        $validated = $request->validate([
            'points' => 'required|integer|min:100',
        ]);
        
        try {
            $discountAmount = $this->loyaltyService->redeemPointsForDiscount($userId, $validated['points']);
            
            return response()->json([
                'success' => true,
                'message' => 'Points redeemed successfully',
                'discount_amount' => $discountAmount,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Validate discount code
     */
    public function validateCode(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'cart_total' => 'required|numeric|min:0',
        ]);
        
        $result = $this->loyaltyService->applyDiscountCode(
            $validated['code'],
            $validated['user_id'],
            $validated['cart_total']
        );
        
        return response()->json($result);
    }

    /**
     * Get all discount codes
     */
    public function discountCodes(): JsonResponse
    {
        $codes = DiscountCode::where('is_active', true)
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', now());
            })
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $codes,
        ]);
    }

    /**
     * Create discount code
     */
    public function createDiscountCode(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:percentage,fixed,free_shipping',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
        ]);
        
        $validated['code'] = $this->loyaltyService->generateDiscountCode();
        $validated['created_by'] = auth()->id();
        
        $discountCode = DiscountCode::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Discount code created successfully',
            'data' => $discountCode,
        ], 201);
    }
}
