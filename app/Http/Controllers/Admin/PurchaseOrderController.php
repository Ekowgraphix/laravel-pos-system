<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $orders = PurchaseOrder::with(['product', 'store', 'creator'])
            ->latest()
            ->paginate(20);

        return view('admin.purchase-order.list', compact('orders'));
    }

    public function create()
    {
        $products = Product::whereNotNull('supplier_name')->get();
        $stores = Store::where('status', 'active')->get();

        return view('admin.purchase-order.create', compact('products', 'stores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'store_id' => 'nullable|exists:stores,id',
            'supplier_name' => 'required|string',
            'supplier_contact' => 'nullable|string',
            'quantity_ordered' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after:order_date',
            'notes' => 'nullable|string',
        ]);

        $validated['total_cost'] = $validated['quantity_ordered'] * $validated['unit_cost'];
        $validated['created_by'] = auth()->id();

        PurchaseOrder::create($validated);

        Alert::success('Success', 'Purchase order created successfully');
        return redirect()->route('admin#purchaseOrderList');
    }

    public function show($id)
    {
        $order = PurchaseOrder::with(['product', 'store', 'creator', 'receiver'])->findOrFail($id);
        return view('admin.purchase-order.detail', compact('order'));
    }

    public function receive(Request $request, $id)
    {
        $order = PurchaseOrder::findOrFail($id);

        $validated = $request->validate([
            'quantity_received' => 'required|integer|min:1|max:' . $order->getRemainingQuantity(),
        ]);

        if ($validated['quantity_received'] == $order->getRemainingQuantity()) {
            $order->markAsCompleted(auth()->id());
            Alert::success('Success', 'Purchase order completed');
        } else {
            $order->receivePartial($validated['quantity_received'], auth()->id());
            Alert::success('Success', 'Partial delivery recorded');
        }

        return redirect()->back();
    }

    public function cancel($id)
    {
        $order = PurchaseOrder::findOrFail($id);
        $order->update(['status' => 'cancelled']);

        Alert::success('Success', 'Purchase order cancelled');
        return redirect()->back();
    }
}
