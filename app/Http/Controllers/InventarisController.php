<?php

namespace App\Http\Controllers;

class InventarisController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Inventory::query();

        if ($request->filled('category') && $request->category !== 'Semua') {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('part_code', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $inventories = $query->orderBy('name', 'asc')->get();
        $categories = ['Semua', 'Oli & Cairan', 'Suku Cadang CVT', 'Sistem Pengereman', 'Kelistrikan & Busi', 'Filter & Udara', 'Ban & Roda', 'Aksesoris & Bodypart'];

        return view('inventaris', compact('inventories', 'categories'));
    }
}
