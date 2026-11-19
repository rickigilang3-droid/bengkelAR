<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerInventoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Inventory::query()->where('stock', '>', 0);

        if ($request->filled('category')) {
            $query->where('category', $request->string('category')->toString());
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($query) use ($search): void {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('part_code', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%');
            });
        }

        return view('dashboard.roles.inventory', [
            'inventories' => $query->orderBy('category')->orderBy('name')->paginate(12)->withQueryString(),
            'categories' => Inventory::query()
                ->where('stock', '>', 0)
                ->distinct()
                ->orderBy('category')
                ->pluck('category'),
            'filters' => $request->only(['search', 'category']),
        ]);
    }
}
