<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockAlert;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class StockAlertController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index()
    {
        $alerts = StockAlert::with(['product', 'store'])
            ->where('status', 'pending')
            ->orderByRaw("
                CASE alert_type
                    WHEN 'expired' THEN 1
                    WHEN 'out_of_stock' THEN 2
                    WHEN 'expiring_soon' THEN 3
                    WHEN 'low_stock' THEN 4
                END
            ")
            ->latest()
            ->paginate(20);

        $summary = $this->inventoryService->getLowStockSummary();

        return view('admin.stock-alert.list', compact('alerts', 'summary'));
    }

    public function acknowledge($id)
    {
        $alert = StockAlert::findOrFail($id);
        $alert->acknowledge(auth()->id());

        Alert::success('Success', 'Alert acknowledged');
        return redirect()->back();
    }

    public function resolve(Request $request, $id)
    {
        $alert = StockAlert::findOrFail($id);

        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $alert->resolve($validated['notes'] ?? null);

        Alert::success('Success', 'Alert resolved');
        return redirect()->back();
    }

    public function runCheck()
    {
        $this->inventoryService->checkLowStockAlerts();
        $this->inventoryService->checkExpiryAlerts();

        Alert::success('Success', 'Stock check completed');
        return redirect()->back();
    }
}
