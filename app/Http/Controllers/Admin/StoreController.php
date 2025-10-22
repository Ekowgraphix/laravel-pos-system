<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::with('manager')->withCount('products')->get();
        return view('admin.store.list', compact('stores'));
    }

    public function create()
    {
        $managers = User::where('role', 'admin')->get();
        return view('admin.store.create', compact('managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:stores,code',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'manager_id' => 'nullable|exists:users,id',
            'currency' => 'required|string|max:3',
            'timezone' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        Store::create($validated);

        Alert::success('Success', 'Store created successfully');
        return redirect()->route('admin#storeList');
    }

    public function edit($id)
    {
        $store = Store::findOrFail($id);
        $managers = User::where('role', 'admin')->get();
        return view('admin.store.edit', compact('store', 'managers'));
    }

    public function update(Request $request, $id)
    {
        $store = Store::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:stores,code,' . $id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'manager_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive',
            'currency' => 'required|string|max:3',
        ]);

        $store->update($validated);

        Alert::success('Success', 'Store updated successfully');
        return redirect()->route('admin#storeList');
    }

    public function delete($id)
    {
        Store::findOrFail($id)->delete();
        Alert::success('Success', 'Store deleted successfully');
        return redirect()->back();
    }

    public function inventory($id)
    {
        $store = Store::with(['products.category'])->findOrFail($id);
        $lowStock = $store->getLowStockProducts();
        $expiring = $store->getExpiringProducts();

        return view('admin.store.inventory', compact('store', 'lowStock', 'expiring'));
    }
}
