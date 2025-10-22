<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StoreApiController extends Controller
{
    public function index(): JsonResponse
    {
        $stores = Store::with('manager')->get();
        
        return response()->json([
            'success' => true,
            'data' => $stores,
        ]);
    }

    public function show($id): JsonResponse
    {
        $store = Store::with(['manager', 'products', 'stockAlerts'])->find($id);
        
        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $store,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:stores,code',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'manager_id' => 'nullable|exists:users,id',
            'currency' => 'nullable|string',
            'timezone' => 'nullable|string',
        ]);
        
        $store = Store::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Store created successfully',
            'data' => $store,
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $store = Store::find($id);
        
        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found'
            ], 404);
        }
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|unique:stores,code,' . $id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'status' => 'sometimes|in:active,inactive',
        ]);
        
        $store->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Store updated successfully',
            'data' => $store,
        ]);
    }

    public function inventory($id): JsonResponse
    {
        $store = Store::find($id);
        
        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found'
            ], 404);
        }
        
        $inventory = $store->products()->with('category')->get();
        
        return response()->json([
            'success' => true,
            'data' => $inventory,
        ]);
    }

    public function lowStock($id): JsonResponse
    {
        $store = Store::find($id);
        
        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found'
            ], 404);
        }
        
        $lowStock = $store->getLowStockProducts();
        
        return response()->json([
            'success' => true,
            'data' => $lowStock,
        ]);
    }
}
