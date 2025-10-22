<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\LoyaltyPoint;
use App\Models\DiscountCode;
use App\Services\LoyaltyService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoyaltyServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $loyaltyService;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loyaltyService = new LoyaltyService();
        $this->user = User::factory()->create();
    }

    public function test_user_can_earn_loyalty_points_from_order()
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'totalPrice' => 10000,
            'status' => '1',
            'payment_status' => 'success'
        ]);

        $this->loyaltyService->awardPointsForOrder($order);

        $this->assertDatabaseHas('loyalty_points', [
            'user_id' => $this->user->id,
        ]);

        $loyalty = LoyaltyPoint::where('user_id', $this->user->id)->first();
        $this->assertGreaterThan(0, $loyalty->points);
    }

    public function test_loyalty_tier_upgrades_based_on_lifetime_points()
    {
        $loyalty = LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 0,
            'lifetime_points' => 0,
            'tier' => 'bronze'
        ]);

        // Add enough points to reach silver
        $loyalty->update(['lifetime_points' => 5000]);
        $this->loyaltyService->updateUserTier($this->user->id);

        $loyalty->refresh();
        $this->assertEquals('silver', $loyalty->tier);

        // Add enough points to reach gold
        $loyalty->update(['lifetime_points' => 10000]);
        $this->loyaltyService->updateUserTier($this->user->id);

        $loyalty->refresh();
        $this->assertEquals('gold', $loyalty->tier);
    }

    public function test_can_redeem_points_for_discount()
    {
        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 1000,
            'lifetime_points' => 1000,
            'tier' => 'bronze'
        ]);

        $discountAmount = $this->loyaltyService->redeemPointsForDiscount($this->user->id, 500);

        $this->assertEquals(5, $discountAmount);
        
        $loyalty = LoyaltyPoint::where('user_id', $this->user->id)->first();
        $this->assertEquals(500, $loyalty->points);
    }

    public function test_cannot_redeem_more_points_than_available()
    {
        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 100,
            'lifetime_points' => 100,
            'tier' => 'bronze'
        ]);

        $this->expectException(\Exception::class);
        $this->loyaltyService->redeemPointsForDiscount($this->user->id, 500);
    }

    public function test_can_apply_valid_discount_code()
    {
        $code = DiscountCode::create([
            'code' => 'TEST20',
            'name' => 'Test Discount',
            'type' => 'percentage',
            'value' => 20,
            'is_active' => true,
            'created_by' => $this->user->id,
        ]);

        $result = $this->loyaltyService->applyDiscountCode('TEST20', $this->user->id, 1000);

        $this->assertTrue($result['success']);
        $this->assertEquals(200, $result['discount_amount']);
    }

    public function test_discount_code_respects_minimum_purchase()
    {
        $code = DiscountCode::create([
            'code' => 'SAVE50',
            'name' => 'Save 50',
            'type' => 'fixed',
            'value' => 50,
            'min_purchase' => 1000,
            'is_active' => true,
            'created_by' => $this->user->id,
        ]);

        // Should fail - cart total too low
        $result = $this->loyaltyService->applyDiscountCode('SAVE50', $this->user->id, 500);
        $this->assertFalse($result['success']);

        // Should succeed
        $result = $this->loyaltyService->applyDiscountCode('SAVE50', $this->user->id, 1500);
        $this->assertTrue($result['success']);
    }

    public function test_discount_code_respects_usage_limit()
    {
        $code = DiscountCode::create([
            'code' => 'LIMITED',
            'name' => 'Limited Use',
            'type' => 'percentage',
            'value' => 10,
            'usage_limit' => 1,
            'times_used' => 0,
            'is_active' => true,
            'created_by' => $this->user->id,
        ]);

        // First use - should succeed
        $result = $this->loyaltyService->applyDiscountCode('LIMITED', $this->user->id, 1000);
        $this->assertTrue($result['success']);

        // Second use - should fail
        $code->refresh();
        $result = $this->loyaltyService->applyDiscountCode('LIMITED', $this->user->id, 1000);
        $this->assertFalse($result['success']);
    }

    public function test_free_shipping_discount_type()
    {
        DiscountCode::create([
            'code' => 'FREESHIP',
            'name' => 'Free Shipping',
            'type' => 'free_shipping',
            'value' => 0,
            'is_active' => true,
            'created_by' => $this->user->id,
        ]);

        $result = $this->loyaltyService->applyDiscountCode('FREESHIP', $this->user->id, 1000);

        $this->assertTrue($result['success']);
        $this->assertEquals(0, $result['discount_amount']);
    }

    public function test_can_generate_unique_discount_code()
    {
        $code1 = $this->loyaltyService->generateDiscountCode();
        $code2 = $this->loyaltyService->generateDiscountCode();

        $this->assertNotEmpty($code1);
        $this->assertNotEmpty($code2);
        $this->assertNotEquals($code1, $code2);
    }

    public function test_get_user_loyalty_status()
    {
        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 500,
            'lifetime_points' => 2500,
            'tier' => 'bronze'
        ]);

        $status = $this->loyaltyService->getUserLoyaltyStatus($this->user->id);

        $this->assertEquals(500, $status['points']);
        $this->assertEquals(2500, $status['lifetime_points']);
        $this->assertEquals('bronze', $status['tier']);
        $this->assertArrayHasKey('benefits', $status);
    }
}
