<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use App\Models\LoyaltyPoint;
use App\Models\CustomerSegment;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class LoyaltyController extends Controller
{
    protected $loyaltyService;

    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->loyaltyService = $loyaltyService;
    }

    // Discount Codes
    public function discountCodeList()
    {
        $codes = DiscountCode::with('creator')->latest()->paginate(20);
        return view('admin.loyalty.discount-codes', compact('codes'));
    }

    public function createDiscountCode()
    {
        return view('admin.loyalty.create-discount');
    }

    public function storeDiscountCode(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:percentage,fixed,free_shipping',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'required|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
        ]);

        $validated['code'] = $this->loyaltyService->generateDiscountCode();
        $validated['created_by'] = auth()->id();

        DiscountCode::create($validated);

        Alert::success('Success', 'Discount code created: ' . $validated['code']);
        return redirect()->route('admin#discountCodeList');
    }

    public function toggleDiscountCode($id)
    {
        $code = DiscountCode::findOrFail($id);
        $code->update(['is_active' => !$code->is_active]);

        Alert::success('Success', 'Discount code ' . ($code->is_active ? 'activated' : 'deactivated'));
        return redirect()->back();
    }

    // Customer Segments
    public function segmentList()
    {
        $segments = CustomerSegment::withCount('users')->get();
        return view('admin.loyalty.segments', compact('segments'));
    }

    public function createSegment()
    {
        return view('admin.loyalty.create-segment');
    }

    public function storeSegment(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'criteria' => 'required|array',
        ]);

        $validated['slug'] = \Str::slug($validated['name']);

        $segment = CustomerSegment::create($validated);
        $segment->refreshCustomers();

        Alert::success('Success', 'Customer segment created');
        return redirect()->route('admin#segmentList');
    }

    public function refreshSegment($id)
    {
        $segment = CustomerSegment::findOrFail($id);
        $segment->refreshCustomers();

        Alert::success('Success', "Segment refreshed: {$segment->customer_count} customers");
        return redirect()->back();
    }

    // Loyalty Overview
    public function overview()
    {
        $stats = [
            'total_members' => LoyaltyPoint::count(),
            'bronze' => LoyaltyPoint::where('tier', 'bronze')->count(),
            'silver' => LoyaltyPoint::where('tier', 'silver')->count(),
            'gold' => LoyaltyPoint::where('tier', 'gold')->count(),
            'platinum' => LoyaltyPoint::where('tier', 'platinum')->count(),
            'total_points_issued' => LoyaltyPoint::sum('lifetime_points'),
            'total_points_available' => LoyaltyPoint::sum('points'),
            'active_discount_codes' => DiscountCode::where('is_active', true)->count(),
        ];

        $topMembers = LoyaltyPoint::with('user')
            ->orderBy('lifetime_points', 'desc')
            ->limit(10)
            ->get();

        return view('admin.loyalty.overview', compact('stats', 'topMembers'));
    }
}
