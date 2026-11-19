<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\PartReplacement;
use Illuminate\Http\Request;

class AdminInventoryController extends Controller
{
    /**
     * Display inventory management dashboard
     */
    public function index(Request $request)
    {
        $query = Inventory::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('part_code', 'like', "%{$search}%")
                  ->orWhere('supplier', 'like', "%{$search}%");
            });
        }

        $inventories = $query->orderBy('stock', 'asc')->paginate(15);

        $totalItems = Inventory::count();
        $totalStock = Inventory::sum('stock');
        $lowStockCount = Inventory::whereRaw('stock <= min_stock')->count();
        $totalAssetValue = Inventory::selectRaw('SUM(stock * purchase_price) as total')->value('total') ?? 0;

        $recentReplacements = PartReplacement::with(['inventory', 'vehicle'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.admin.inventory.index', [
            'title' => 'Manajemen Stok & Sparepart',
            'active' => 'inventaris',
            'inventories' => $inventories,
            'totalItems' => $totalItems,
            'totalStock' => $totalStock,
            'lowStockCount' => $lowStockCount,
            'totalAssetValue' => $totalAssetValue,
            'recentReplacements' => $recentReplacements,
        ]);
    }

    /**
     * Store a newly created spare part
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'part_code' => 'required|string|unique:inventories,part_code',
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:1',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'supplier' => 'nullable|string',
            'image_url' => 'nullable|string|max:500',
            'description' => 'nullable|string',
        ]);

        Inventory::create($validated);

        return redirect()->back()->with('success', 'Komponen sparepart baru berhasil ditambahkan ke inventaris!');
    }

    /**
     * Update existing spare part
     */
    public function update(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:1',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'supplier' => 'nullable|string',
            'image_url' => 'nullable|string|max:500',
            'description' => 'nullable|string',
        ]);

        $inventory->update($validated);

        return redirect()->back()->with('success', 'Data sparepart berhasil diperbarui!');
    }

    /**
     * Delete spare part item
     */
    public function destroy($id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();

        return redirect()->back()->with('success', 'Sparepart berhasil dihapus dari inventaris!');
    }
}

