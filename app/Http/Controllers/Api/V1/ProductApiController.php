<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductApiController extends Controller
{
    /**
     * Get all products
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with(['category', 'store']);
        
        // Filters
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('store_id')) {
            $query->where('store_id', $request->store_id);
        }
        
        if ($request->has('low_stock')) {
            $query->whereRaw('count <= low_stock_threshold');
        }
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('barcode', $request->search)
                  ->orWhere('sku', $request->search);
            });
        }
        
        $perPage = $request->get('per_page', 15);
        $products = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * Get single product
     */
    public function show($id): JsonResponse
    {
        $product = Product::with(['category', 'store', 'stockMovements' => function($q) {
            $q->latest()->limit(10);
        }])->find($id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    /**
     * Create product
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'count' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'reorder_quantity' => 'nullable|integer|min:0',
            'barcode' => 'nullable|string|unique:products,barcode',
            'sku' => 'nullable|string|unique:products,sku',
            'supplier_name' => 'nullable|string',
            'expiry_date' => 'nullable|date',
        ]);
        
        $product = Product::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product,
        ], 201);
    }

    /**
     * Update product
     */
    public function update(Request $request, $id): JsonResponse
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'purchase_price' => 'sometimes|numeric|min:0',
            'category_id' => 'sometimes|exists:categories,id',
            'count' => 'sometimes|integer|min:0',
            'description' => 'nullable|string',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'barcode' => 'nullable|string|unique:products,barcode,' . $id,
            'sku' => 'nullable|string|unique:products,sku,' . $id,
        ]);
        
        $product->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product,
        ]);
    }

    /**
     * Delete product
     */
    public function destroy($id): JsonResponse
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        
        $product->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully',
        ]);
    }

    /**
     * Get product by barcode
     */
    public function findByBarcode($barcode): JsonResponse
    {
        $product = Product::where('barcode', $barcode)->first();
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    /**
     * Get low stock products
     */
    public function lowStock(): JsonResponse
    {
        $products = Product::whereRaw('count <= low_stock_threshold')
            ->with(['category', 'store'])
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * Get expiring products
     */
    public function expiring(Request $request): JsonResponse
    {
        $days = $request->get('days', 30);
        
        $products = Product::whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<=', now()->addDays($days))
            ->whereDate('expiry_date', '>=', now())
            ->with(['category', 'store'])
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }
}
