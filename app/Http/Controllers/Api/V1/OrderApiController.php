<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class OrderApiController extends Controller
{
    protected $loyaltyService;

    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Get all orders
     */
    public function index(Request $request): JsonResponse
    {
        $query = Order::with(['product', 'user']);
        
        // Filters
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $perPage = $request->get('per_page', 15);
        $orders = $query->latest()->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    /**
     * Get single order
     */
    public function show($id): JsonResponse
    {
        $order = Order::with(['product', 'user'])->find($id);
        
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    /**
     * Create order
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'count' => 'required|integer|min:1',
            'payment_method_id' => 'nullable|exists:payments,id',
        ]);
        
        $product = Product::find($validated['product_id']);
        
        // Check stock
        if ($product->count < $validated['count']) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock'
            ], 400);
        }
        
        $totalPrice = $product->price * $validated['count'];
        $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        
        $order = Order::create([
            'user_id' => $validated['user_id'],
            'product_id' => $validated['product_id'],
            'count' => $validated['count'],
            'totalPrice' => $totalPrice,
            'order_code' => $orderCode,
            'status' => '0', // Pending
            'payment_status' => 'pending',
        ]);
        
        // Deduct stock
        $product->decrement('count', $validated['count']);
        $product->increment('total_sold', $validated['count']);
        
        return response()->json([
            'success' => true,
            'message' => 'Order created successfully',
            'data' => $order,
        ], 201);
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $order = Order::find($id);
        
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }
        
        $validated = $request->validate([
            'status' => 'required|in:0,1,2', // 0=pending, 1=success, 2=reject
            'reject_reason' => 'required_if:status,2|nullable|string',
        ]);
        
        $order->update($validated);
        
        // Award loyalty points if order is successful
        if ($validated['status'] == '1' && $order->payment_status === 'success') {
            $this->loyaltyService->awardPointsForOrder($order);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'data' => $order,
        ]);
    }

    /**
     * Get order statistics
     */
    public function statistics(Request $request): JsonResponse
    {
        $dateFrom = $request->get('date_from', now()->subDays(30));
        $dateTo = $request->get('date_to', now());
        
        $stats = [
            'total_orders' => Order::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'pending_orders' => Order::where('status', '0')->whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'completed_orders' => Order::where('status', '1')->whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'rejected_orders' => Order::where('status', '2')->whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'total_revenue' => Order::where('status', '1')->whereBetween('created_at', [$dateFrom, $dateTo])->sum('totalPrice'),
            'average_order_value' => Order::where('status', '1')->whereBetween('created_at', [$dateFrom, $dateTo])->avg('totalPrice'),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}
